# Laravel Patterns

Laravel mimari pattern rehberi. Katman sınırları, route organizasyonu, model, cache, queue ve event kalıpları.

$ARGUMENTS

---

## Mimari Sınır Kuralı

```
HTTP Request
    → FormRequest (validation + DTO dönüşümü)
        → Controller (thin — sadece yönlendirme)
            → Interface (contract)
                → Repository (Eloquent, veri erişimi)
            → Action (çok adımlı iş mantığı + DB transaction)
                → DTO (tip-güvenli veri transferi)
```

**Asla ihlal edilmez:**
- Controller'da iş mantığı yok, query yok
- Model doğrudan controller'da kullanılmaz
- Action = DB transaction gerektiren çok adımlı iş

## Route Organizasyonu

```php
Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function () {
    Route::controller(UserController::class)->group(function () {
        Route::apiResource('/users', UserController::class);
        Route::post('/users/paginate', 'paginate');
    });
});
```

- UUID için route model binding: `->parameters(['resource' => 'model:uuid'])`
- Cross-tenant erişimi önlemek için scoped binding kullan
- Farklı auth gereksinimleri → ayrı middleware bloğu

## Eloquent Model

```php
class User extends Authenticatable
{
    use SoftDeletes, HasFactory;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'settings'          => 'array',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', StatusEnum::Active->value);
    }

    // N+1 önleme — sık kullanılan ilişkiyi eager load et
    protected $with = ['company'];
}
```

## Form Request → DTO Dönüşümü

```php
class OrderRequest extends FormRequest
{
    use FailedValidationTrait;

    public function rules(): array { ... }

    public function toData(): OrderData
    {
        return OrderData::fromArray($this->validated());
    }
}
```

## Caching

```php
$users = Cache::remember('users.all', 3600, fn() => $this->user->getAll());

protected static function booted(): void
{
    static::saved(fn() => Cache::forget('users.all'));
    static::deleted(fn() => Cache::forget('users.all'));
}
```

## Queue Jobs

```php
class SendWelcomeEmail implements ShouldQueue
{
    use InteractsWithQueue;

    public int $tries = 3;
    public int $backoff = 30;

    public function handle(): void
    {
        if ($this->user->welcome_sent_at) return; // idempotent

        Mail::to($this->user)->send(new WelcomeMail($this->user));
        $this->user->update(['welcome_sent_at' => now()]);
    }
}
```

## Domain Events

```php
event(new UserRegistered($user)); // Action içinde fırlat

class SendWelcomeEmailListener
{
    public function handle(UserRegistered $event): void
    {
        dispatch(new SendWelcomeEmail($event->user));
    }
}
```

## Configuration

```php
// config/services.php — env() burada
'stripe' => ['key' => env('STRIPE_KEY'), 'secret' => env('STRIPE_SECRET')],

// Uygulama içinde config() kullan, env() değil
config('services.stripe.key')
```

Production: `php artisan config:cache && php artisan route:cache && php artisan view:cache`
