---
description: Tasarım desenleri, mimari yapılar ve performans kuralları — kod yazarken aktif
alwaysApply: false
---

# Design Patterns

## Repository Pattern
```
Controller → Interface → Repository → Model
```
Standard operations: `getAll`, `getById`, `store`, `update`, `updateOrCreate`, `destroy`, `paginate`

## API Response Format
All responses: `status`, `data`, `message`, `pagination`.
Errors: `status: false`, `message`, `errors`.

## Service / Action Layer
- **Service**: stateless, reusable business logic.
- **Action**: single-purpose, multi-step operation (wraps in DB transaction).
- Neither touches HTTP request/response.

## Dependency Injection
Inject via constructor using interfaces, not concrete classes.

## Value Objects
Use for constrained domain concepts. Enforce invariants in constructor.
```
Money(amount, currency)   → enforce non-negative, valid currency code
DateRange(start, end)     → enforce start ≤ end
Email(address)            → enforce valid format, lowercase normalization
```

---

# Performance

## Database
- Eager-load relationships — prevent N+1 queries.
- Index columns used in `WHERE`, `ORDER BY`, `JOIN`.
- Use `select()` to limit fetched columns. Wrap multi-step writes in transactions.

## Caching & API
- Cache expensive read queries. Invalidate via model events. Queue non-blocking jobs.
- Paginate all list endpoints (default 50/page). Return only needed fields via API Resources.

## Agent Model Selection
- **Haiku**: lightweight sub-tasks | **Sonnet**: default | **Opus**: architectural decisions

## Context Window
- Large refactors: preserve final 20% of context for synthesis.