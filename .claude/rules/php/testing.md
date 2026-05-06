---
description: PHP testing rules — Pest/PHPUnit, factories, database traits, Inertia assertions
globs: ["**/*.php", "**/tests/**", "**/features/**"]
alwaysApply: false
---

# PHP Testing

## Framework
- Default to **Pest** for new tests.
- Use **PHPUnit** only if the project already standardizes on it.
- Never mix both in the same project.

## Coverage
- Run with `pcov` or `XDEBUG_MODE=coverage` in CI.
- Enforce thresholds in CI config (not informally).
- Target: 80%+ on unit + feature tests.

## Database Traits
| Trait | When to use |
|---|---|
| `RefreshDatabase` | Default — handles migrations + wraps each test in a transaction |
| `DatabaseTransactions` | Schema already migrated, need per-test rollback only |
| `DatabaseMigrations` | Full fresh migration required per test (slow — use sparingly) |

## Test Data
- Use **factories** for all test data — never manually build arrays.
- Define factory states for edge cases (`->state(['is_active' => false])`).
- Use `assertDatabaseHas` and `assertDatabaseMissing` for DB assertions.

## Fakes (isolate side effects)
```php
Queue::fake();
Mail::fake();
Notification::fake();
Http::fake();
Storage::fake();
```

## Inertia.js
Use `assertInertia` with `AssertableInertia` — never assert raw JSON for Inertia responses.

## Controller / HTTP Tests
- Focus on request/response handling and input validation.
- Business logic belongs in service-level unit tests, not HTTP tests.

## Reference
- For the full TDD cycle: see `skills/laravel-tdd/SKILL.md`
