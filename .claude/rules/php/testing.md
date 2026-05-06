---
description: PHP testing — Pest/PHPUnit, factories, database traits, Inertia assertions
globs: ["**/*.php", "**/tests/**", "**/features/**"]
alwaysApply: false
---

<!-- Applies to: **/*.php, tests/** -->

# PHP Testing

## Framework
- Default to **Pest** for new tests.
- **PHPUnit** only if project already standardizes on it.
- Never mix both in the same project.

## Coverage
- Run with `pcov` or `XDEBUG_MODE=coverage` in CI.
- Enforce thresholds in CI config (not informally).

## Database Traits
| Trait | When to use |
|---|---|
| `RefreshDatabase` | Default — migrations + per-test transaction |
| `DatabaseTransactions` | Schema migrated, per-test rollback only |
| `DatabaseMigrations` | Full fresh migration per test (slow — sparingly) |

## Test Data
- **Factories** for all test data — never manually build arrays.
- Factory states for edge cases.
- `assertDatabaseHas` / `assertDatabaseMissing` for DB assertions.

## Fakes (isolate side effects)
Call `::fake()` on: `Queue`, `Mail`, `Notification`, `Http`, `Storage`.

## Inertia.js
Use `assertInertia` with `AssertableInertia` — never assert raw JSON for Inertia responses.

## Controller / HTTP Tests
- Focus on request/response handling and input validation.
- Business logic → service-level unit tests.
