---
description: Testing requirements — TDD workflow, 80% coverage, AAA pattern
alwaysApply: true
---

# Testing

## Non-Negotiables
- Minimum 80% test coverage (unit + feature). Enforced in CI.
- **Write tests first** (RED → GREEN → REFACTOR). Not optional.
- When tests fail: fix the implementation, not the test.

## Test Types Required
- **Unit**: isolated business logic, pure functions
- **Feature/Integration**: API endpoints, database interactions
- **E2E**: critical user workflows (Playwright for UI, Behat for API)

## Test Structure — Arrange-Act-Assert
```
Arrange: set up test data and preconditions
Act:     execute the function/endpoint being tested
Assert:  verify the expected outcome
```

## Naming
Test names must describe behavior, not implementation:
- Good: `"returns empty array when no users match the filter"`
- Bad: `"test_get_users"` or `"testUserFilter"`

## TDD Workflow
1. Write a failing test that describes the expected behavior (RED)
2. Run it — confirm it fails for the right reason
3. Write the minimal implementation to make it pass (GREEN)
4. Refactor while keeping tests green (REFACTOR)
5. Verify coverage meets threshold

## Agent Support
Use the **tdd-guide** agent (if available) for new features to enforce test-first methodology.
