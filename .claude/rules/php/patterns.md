---
description: PHP architecture patterns — thin controllers, DTOs, interface injection, adapter pattern
globs: ["**/*.php"]
alwaysApply: false
---

<!-- Applies to: **/*.php -->

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
Use for constrained domain concepts (Money, DateRange, Email). Enforce invariants in constructor.

## Dependency Injection
- Inject via constructor using interfaces.
- Never use `app()` or `resolve()` inside business logic.
- Register bindings in `RepositoryServiceProvider`.

## Domain / ORM Separation
- Eloquent models: persistence, casts, relationships only.
- Domain logic: Actions, Services, or Domain objects.

## Third-Party Adapters
Wrap external SDKs behind adapters/interfaces.
