# Laravel Verify

PR öncesi, bağımlılık güncellemesi sonrası ve her deployment öncesi 7 aşamalı verification loop.

**Kural:** Her aşama bir öncekinin kapısıdır. Birinde başarısız olursan dur, düzelt, devam et.

$ARGUMENTS

---

## Aşama 1 — Ortam Kontrolü

```bash
test -f .env || echo "HATA: .env eksik"

php -r "
\$required = ['APP_KEY', 'DB_HOST', 'DB_DATABASE', 'DB_USERNAME'];
foreach(\$required as \$key) {
    if (empty(getenv(\$key))) echo 'EKSİK: ' . \$key . PHP_EOL;
}
"

php artisan about | grep -E "Environment|Debug"
```

## Aşama 1.5 — Composer Bütünlüğü

```bash
composer validate --strict
composer dump-autoload --optimize
```

## Aşama 2 — Kod Kalitesi

```bash
vendor/bin/pint --test
vendor/bin/phpstan analyse --memory-limit=512M
```

## Aşama 3 — Test Koşusu

```bash
XDEBUG_MODE=coverage vendor/bin/pest --coverage --min=80
```

## Aşama 4 — Güvenlik Denetimi

```bash
composer audit
grep -rn "password\s*=\s*['\"][^$]" --include="*.php" app/
grep -rn "api_key\s*=\s*['\"]" --include="*.php" app/
```

HIGH veya CRITICAL açık varsa merge bloklayıcı.

## Aşama 5 — Migration Güvenliği

```bash
php artisan migrate --pretend
php artisan migrate:status
```

Kolon silen migration'larda backup zorunlu.

## Aşama 6 — Production Hazırlığı

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
chmod -R 755 storage bootstrap/cache
```

## Aşama 7 — Queue & Scheduler

```bash
php artisan schedule:list
php artisan queue:failed
php artisan horizon:status  # Horizon kullanılıyorsa
```

---

## Minimal Kontrol (Hızlı PR)

```bash
vendor/bin/pint --test && vendor/bin/pest && composer audit
```

## CI Pipeline (GitHub Actions)

```yaml
- name: Verify
  run: |
    composer validate --strict
    vendor/bin/pint --test
    vendor/bin/phpstan analyse --memory-limit=512M
    XDEBUG_MODE=coverage vendor/bin/pest --coverage --min=80
    composer audit
    php artisan migrate --pretend
```
