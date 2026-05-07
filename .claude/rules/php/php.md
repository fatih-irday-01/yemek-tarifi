---
description: PHP kuralları — PSR-12, strict_types, PHP 8.x, DTOs, güvenlik, Pest testleri
globs: ["**/*.php", "**/tests/**"]
alwaysApply: false
---

# PHP Coding Style

## Standards
- Follow **PSR-12**. Use **Laravel Pint** to auto-format.
- All files begin with `declare(strict_types=1);`
- Type hints on all parameters, return types, and class properties.
- `readonly` properties and `final readonly class` for DTOs and Actions.
- DB columns: `snake_case`.

## Imports
- Always explicit `use` statements. Group: PHP core → Laravel → App.

## Error Handling
- Throw exceptions — never return `null` or `false` to signal failure.
- Convert Form Requests into validated DTOs before passing to services.

## Static Analysis
- Run **PHPStan** (level 8). Add Composer scripts for `lint` and `analyse`.

## PHP 8.x Features (use actively)
Constructor property promotion, named arguments, match expressions, readonly properties, backed enums (with EnumMethods trait), nullsafe `?->`.

## PHPDoc — Required on all classes and methods
- **Class**: `@package` with full namespace
- **Method**: `@param TypeHint $name`, `@return TypeHint`
- **Model**: `@property TypeHint $column` for all columns
- **Constructor**: `@param` for each injected dependency
- Overrides the system-wide "no comments" rule — PHPDoc is API documentation (PSR-5).

---

# PHP Patterns

## Controller Responsibility
Controllers: transport only (auth, validation delegation, response serialization, HTTP status).
Must not contain: direct DB queries, complex conditionals, multi-step business operations.

## Data Transfer Objects (DTOs)
- Replace associative arrays with DTOs at service boundaries.
- `final readonly class` with constructor property promotion.
- Include `fromArray(array $data): self` and `toArray(): array`.
- Throw exceptions on invalid input — never return null.

## Value Objects
Use for constrained domain concepts. Enforce invariants in constructor.

## Dependency Injection
- Inject via constructor using interfaces. Register bindings in `RepositoryServiceProvider`.
- Never use `app()` or `resolve()` inside business logic.

## Domain / ORM Separation
- Eloquent models: persistence, casts, relationships only.
- Domain logic: Actions, Services, or Domain objects.

## Third-Party Adapters
Wrap external SDKs behind adapters/interfaces.

---

# PHP Security

## Input Validation
- Validate ALL input at the framework boundary (Form Requests).
- Treat query parameters, cookies, headers, and file metadata as untrusted.
- Never use raw `$_GET`, `$_POST`, or `$request->input()` without validation rules.

## Database Security
- Eloquent ORM or parameterized queries — never concatenate user input into SQL.
- Guard models with `$fillable` — never use `$guarded = []`. Manage mass-assignment explicitly.

## Authentication
- `password_hash()` / `password_verify()` — never `md5` or `sha1`.
- Regenerate session IDs after login and privilege escalation. Enforce CSRF via `VerifyCsrfToken`.

## Dependencies
- `composer audit` in CI — block on HIGH/CRITICAL.
- Verify new package maintainers before adoption.
- Never commit `.env` files or credentials.

## Output
- Escape template output by default. Redact PII from logs.

---

# PHP Testing

## Framework
- Default to **Pest**. Never mix Pest and PHPUnit in the same project.
- **Exception**: If a project already standardizes on PHPUnit (existing test suite, CI config, team convention), continue with PHPUnit — do not force a migration. Mixing is still forbidden.

## Coverage
- Run with `pcov` or `XDEBUG_MODE=coverage` in CI. Enforce thresholds.

## Database Traits
| Trait | When |
|---|---|
| `RefreshDatabase` | Default |
| `DatabaseTransactions` | Schema already migrated |
| `DatabaseMigrations` | Sparingly — slow |

## Test Data
- **Factories** only — never manually build arrays.
- Factory states for edge cases.
- `assertDatabaseHas` / `assertDatabaseMissing` for DB assertions.

## Fakes
Call `::fake()` on: `Queue`, `Mail`, `Notification`, `Http`, `Storage`.

## Inertia.js
Use `assertInertia` with `AssertableInertia` — never assert raw JSON.

## HTTP Tests
- Focus on request/response and input validation.
- Business logic → service-level unit tests.
