# Regression Test

Bulunan bir bug için regression testi yaz — o bug bir daha geri dönmesin.

**Bug / Senaryo:** $ARGUMENTS

---

## Temel Problem

```
AI kod yazar → AI kodu review eder → AI "doğru görünüyor" der → Bug hâlâ vardır
```

AI, yazarken taşıdığı varsayımları review ederken de taşır. Test bu döngüyü kırar.

## En Yaygın AI Kör Noktaları

| Pattern | Ne Olur |
|---|---|
| Sandbox/prod yolu uyumsuzluğu | Bir code path'te düzeltme yapılır, diğeri unutulur |
| Eksik kolon SELECT'i | Migration'la eklenen kolon query'ye dahil edilmez |
| Temizlenmeyen hata durumu | Error state bir işlemden sonrakine taşar |
| Eksik rollback | Başarısız optimistic update önceki durumu geri getirmiyor |
| Null maskelenmiş undefined | `undefined` field `null` dönüyor, client hata yakalamıyor |

## Adımlar

1. Bug'ı reproduce eden testi yaz (`@regression` group, `BUG-RX:` prefix)
2. Çalıştır — kırmızı olduğunu doğrula
3. Fix uygula
4. Çalıştır — yeşil olduğunu doğrula
5. PR'a testi ekle

## Test Şablonları

```php
it('BUG-R1: sandbox ve production response aynı fields içermeli', function () {
    $requiredFields = ['id', 'status', 'total', 'items', 'created_at'];
    $response = $this->postJson('/api/v1/orders', $payload);
    foreach ($requiredFields as $field) {
        expect($response->json("data.$field"))->not->toBeNull("$field eksik");
    }
})->group('regression');

it('BUG-R2: başarısız işlem sonrası hata state temizleniyor', function () {
    $this->postJson('/api/v1/orders', $invalidPayload)->assertStatus(422);
    $this->postJson('/api/v1/orders', $validPayload)->assertStatus(200);
})->group('regression');

it('BUG-R3: kısmi başarısız işlemde DB tutarlı kalıyor', function () {
    $initialCount = Order::count();

    $this->mock(PaymentService::class)
        ->shouldReceive('charge')
        ->andThrow(new PaymentException('Card declined'));

    $this->postJson('/api/v1/orders', $validPayload)->assertStatus(422);

    expect(Order::count())->toBe($initialCount);
})->group('regression');
```

## Çalıştırma

```bash
vendor/bin/pest --group=regression   # sadece regression testleri
vendor/bin/pest                      # tüm testler
```

**Kural:** Bir kez bulunan bug, test olmadan kapanmaz.
