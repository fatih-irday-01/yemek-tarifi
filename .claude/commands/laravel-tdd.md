# Laravel TDD

Belirtilen özellik veya endpoint için TDD workflow'unu uygula: önce test yaz, sonra implement et.

**Hedef:** $ARGUMENTS

---

## Çalışma Döngüsü

```
RED   → Önce başarısız testi yaz
GREEN → Testi geçiren minimal kodu yaz
REFACTOR → Testler yeşilken kodu temizle
```

## Test Katmanları — Araç Ayrımı (kesin kural)

| Katman | Ne test eder | Araç | Kim yazar |
|---|---|---|---|
| Unit | Saf PHP logic, service metodları | **Pest** | Backend developer |
| Feature | HTTP request/response, validation, DB | **Pest** | Backend developer |
| Integration | DB + queue + external service | **Pest** | Backend developer |
| E2E / Kabul | Tam API akışı (Gherkin senaryoları) | **Behat** (API) / **Playwright** (Inertia) | QA engineer |

**Pest, Behat'ın yerini ALAMAZ.** Bu iki araç farklı katmanları test eder:
- Pest → backend developer'ın birim ve entegrasyon testleri
- Behat → QA engineer'ın dışarıdan yazdığı API kabul testleri (Given/When/Then)

## Framework Tercihi

Backend developer: **Pest** kullan. Proje zaten PHPUnit'e standardize ettiyse PHPUnit'te kal. İkisini karıştırma.
QA engineer: **Behat** (API projesi) veya **Playwright** (Inertia projesi) — Pest kullanma.

## Veritabanı Trait Seçimi

```php
use RefreshDatabase;      // Default — migration + her test için transaction
use DatabaseTransactions; // Schema hazır, sadece rollback gerekli
use DatabaseMigrations;   // Her test için tam migration (yavaş — zorunlu değilse kullanma)
```

## Factory ile Test Verisi

```php
$user = User::factory()->create();
$inactiveUser = User::factory()->inactive()->create();
$order = Order::factory()
    ->for($user)
    ->has(OrderItem::factory()->count(3))
    ->create();
```

## Side Effect İzolasyonu

```php
Queue::fake();
Mail::fake();
Notification::fake();
Http::fake(['https://api.example.com/*' => Http::response(['ok' => true])]);
Storage::fake('public');
```

## Feature Test Şablonu

```php
it('creates a user successfully', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)
        ->postJson('/api/v1/users', [
            'name'      => 'Test User',
            'email'     => 'test@example.com',
            'role_id'   => 1,
            'is_active' => 1,
        ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
});

it('rejects duplicate email', function () {
    $admin = User::factory()->admin()->create();
    User::factory()->create(['email' => 'existing@example.com']);

    $this->actingAs($admin)
        ->postJson('/api/v1/users', ['email' => 'existing@example.com'])
        ->assertStatus(422);
});
```

## Policy / Authorization Testi

```php
it('prevents non-admin from deleting users', function () {
    $user   = User::factory()->create();
    $target = User::factory()->create();

    $this->actingAs($user)
        ->deleteJson("/api/v1/users/{$target->id}")
        ->assertStatus(403);
});
```

## Inertia.js Assertion

```php
$response->assertInertia(fn (AssertableInertia $page) =>
    $page->component('Users/Index')
         ->has('users.data', 3)
         ->has('users.data.0', fn ($user) =>
             $user->where('email', 'test@example.com')->etc()
         )
);
```

## Coverage Kontrolü

```bash
XDEBUG_MODE=coverage vendor/bin/pest --coverage --min=80
```

**Kural:** Tüm yeni endpoint ve özellikler için test önce yazılır. Test yoksa özellik bitmemiştir.
