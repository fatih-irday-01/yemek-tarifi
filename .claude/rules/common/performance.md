---
description: Performance optimization — N+1 prevention, caching, query optimization
alwaysApply: false
---

# Performance

## Database
- Always eager-load relationships to prevent N+1 queries.
- Add database indexes on columns used in `WHERE`, `ORDER BY`, and `JOIN`.
- Use `select()` to limit columns fetched from large tables.
- Wrap multi-step writes in transactions for consistency and atomicity.

## Caching
- Cache expensive read queries. Invalidate via model events.
- Use queue jobs for operations that don't need to block the HTTP response.

## API
- Paginate all list endpoints — default 50 items per page.
- Return only the fields the client needs (use API Resources).

## Agent Model Selection (Claude tasks)
- **Haiku**: lightweight, frequently-invoked sub-tasks
- **Sonnet**: main development and complex tasks (default)
- **Opus**: architectural decisions requiring deep reasoning

## Context Window
- For large-scale refactoring spanning many files, preserve the final 20% of context for synthesis and wrapping up.
