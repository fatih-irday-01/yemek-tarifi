---
description: PHP architecture patterns — thin controllers, DTOs, interface injection, adapter pattern
globs: ["**/*.php"]
alwaysApply: false
---

# PHP Patterns

## Controller Responsibility
Controllers handle **transport only**: authentication, validation delegation, response serialization, HTTP status codes.

Business logic goes in Services or Actions. Controllers must not contain:
- Direct DB queries
- Complex conditionals
- Multi-step business operations

## Data Transfer Objects (DTOs)
- Replace associative arrays with DTOs at service boundaries.
- Use `final readonly class` with constructor property promotion.
- Include `fromArray(array $data): self` and `toArray(): array` methods.
- Throw exceptions on invalid input — never return null.

## Value Objects
Use value objects for constrained domain concepts (e.g., Money, DateRange, Email). They enforce invariants in the constructor.

## Dependency Injection
- Inject dependencies via constructor using interfaces.
- Never use `app()` helper or `resolve()` inside business logic.
- Register bindings in `RepositoryServiceProvider`.

## Domain / ORM Separation
- Keep ORM models (Eloquent) separate from domain logic.
- Models handle persistence, casts, and relationships only.
- Domain logic lives in Actions, Services, or Domain objects.

## Third-Party Adapters
Wrap external SDKs behind adapters/interfaces. This isolates vendor-specific code and makes testing easier.

## Reference
- For full Laravel implementation patterns: see `skills/laravel-patterns/SKILL.md`
