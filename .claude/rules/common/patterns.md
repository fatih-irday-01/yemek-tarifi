---
description: Common design patterns — repository, API response format, separation of concerns
alwaysApply: false
---

# Design Patterns

## Repository Pattern
Isolate data access behind a unified interface. Controllers and services never query the DB directly.

```
Controller → Interface → Repository → Model
```

Standard operations: `getAll`, `getById`, `store`, `update`, `updateOrCreate`, `destroy`, `paginate`

## API Response Format
All API responses follow a consistent structure:
```json
{
  "status": true,
  "data": {...} or [...],
  "message": "...",
  "pagination": { "total": 0, "per_page": 50, "current_page": 1 }
}
```
Errors:
```json
{
  "status": false,
  "message": "Validation failed",
  "errors": "..."
}
```

## Service / Action Layer
- **Service**: stateless, reusable business logic
- **Action**: single-purpose, multi-step operation (wraps in DB transaction)
- Neither touches HTTP request/response — that belongs to the Controller

## Dependency Injection
Always inject via constructor using interfaces, not concrete classes. This enables testing and swapping implementations.

## Core Principles
- Separation of concerns: each layer has one job
- Program to interfaces, not implementations
- Prefer composition over inheritance
