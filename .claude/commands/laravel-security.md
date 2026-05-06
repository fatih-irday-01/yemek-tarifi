# Laravel Security

Laravel güvenlik sertleştirme rehberi. Auth, input validation, rate limiting, file upload, header ve dependency güvenliği.

$ARGUMENTS

---

## Authentication & Token Yönetimi

```php
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/users', UserController::class);
});

$token = $user->createToken('api-token', ['*'], now()->addDay());
```

- Hassas işlemler için refresh token akışı kur
- Logout'ta token'ı revoke et: `$user->currentAccessToken()->delete()`

## Authorization — Policy Zorunluluğu

```php
public function update(UserRequest $request, int $id): JsonResponse
{
    $user = $this->user->getById($id);
    $this->authorize('update', $user); // her mutating işlemde zorunlu
    ...
}

class UserPolicy
{
    public function update(User $auth, User $target): bool
    {
        return $auth->hasRole('admin') || $auth->id === $target->id;
    }
}
```

## Input Validation (Form Request zorunlu)

```php
public function rules(): array
{
    return [
        'email'    => ['required', 'email', 'max:191', Rule::unique('users')->ignore($this->id)],
        'password' => ['sometimes', 'nullable', 'min:8', 'confirmed'],
        'role_id'  => ['required', 'integer', Rule::exists('roles', 'id')],
    ];
}
```

**Asla** doğrudan `$request->all()` veya validate edilmemiş `$request->input()` kullanma.

## Session Güvenliği

```env
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict
SESSION_SECURE_COOKIE=true
```

## File Upload Güvenliği

```php
$request->validate([
    'document' => ['required', 'file', 'max:10240', 'mimes:pdf,docx'],
]);

$path = $request->file('document')->store('documents', 'private');
Storage::temporaryUrl($path, now()->addMinutes(30)); // erişim için signed URL
```

## Rate Limiting

```php
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(...);
Route::middleware('throttle:5,1')->post('/login', [AuthController::class, 'login']);

RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
});
```

## Hassas Kolon Şifreleme

```php
protected function casts(): array
{
    return [
        'national_id'  => 'encrypted',
        'bank_account' => 'encrypted',
    ];
}
```

## Güvenlik Header'ları

```php
return $next($request)
    ->header('X-Frame-Options', 'DENY')
    ->header('X-Content-Type-Options', 'nosniff')
    ->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains')
    ->header('Content-Security-Policy', "default-src 'self'");
```

## .env Production Kontrolü

```env
APP_ENV=production
APP_DEBUG=false   # CRITICAL — asla true bırakma
```

## Dependency Audit

```bash
composer audit   # HIGH/CRITICAL varsa CI bloklayıcı olmalı
```

## Güvenlik Checklist (Her PR öncesi)

- [ ] `APP_DEBUG=false` production'da
- [ ] Tüm input'lar Form Request ile validate ediliyor
- [ ] Tüm mutating endpoint'lerde `authorize()` var
- [ ] Rate limiting uygulandı
- [ ] `composer audit` temiz
- [ ] Hardcoded secret yok
- [ ] Dosya upload'ları private storage'a gidiyor
- [ ] Güvenlik header'ları mevcut
