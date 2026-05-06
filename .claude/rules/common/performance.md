---
description: Performance — N+1 prevention, caching, pagination, agent model selection
alwaysApply: false
---

<!-- Applies to: database queries, API endpoints -->

# Performance

## Database
- Eager-load relationships — prevent N+1 queries.
- Index columns used in `WHERE`, `ORDER BY`, `JOIN`.
- Use `select()` to limit fetched columns.
- Wrap multi-step writes in transactions.

## Caching
- Cache expensive read queries. Invalidate via model events.
- Queue jobs for non-blocking operations.

## API
- Paginate all list endpoints — default 50 items per page.
- Return only fields the client needs (use API Resources).

## Agent Model Selection
- **Haiku**: lightweight, frequently-invoked sub-tasks.
- **Sonnet**: main development and complex tasks (default).
- **Opus**: architectural decisions requiring deep reasoning.

## Context Window
- Large refactors: preserve final 20% of context for synthesis.
