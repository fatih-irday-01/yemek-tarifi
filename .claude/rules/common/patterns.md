---
description: Design patterns — repository, API response format, service/action layer, DI
alwaysApply: false
---

<!-- Applies to: backend/**, API layers -->

# Design Patterns

## Repository Pattern
Isolate data access behind a unified interface. Controllers and services never query the DB directly.

```
Controller → Interface → Repository → Model
```

Standard operations: `getAll`, `getById`, `store`, `update`, `updateOrCreate`, `destroy`, `paginate`

## API Response Format
All responses: `status`, `data`, `message`, `pagination` (total, per_page, current_page).
Errors: `status: false`, `message`, `errors`.

## Service / Action Layer
- **Service**: stateless, reusable business logic.
- **Action**: single-purpose, multi-step operation (wraps in DB transaction).
- Neither touches HTTP request/response.

## Dependency Injection
Inject via constructor using interfaces, not concrete classes.
