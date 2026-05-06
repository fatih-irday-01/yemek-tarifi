---
description: Testing — TDD workflow, 80% coverage, AAA pattern, test naming
alwaysApply: false
globs: ["**/tests/**", "**/*.test.*", "**/*.spec.*", "**/features/**", "**/*.feature"]
---

# Testing

## Non-Negotiables
- Minimum 80% test coverage (unit + feature). Enforced in CI.
- Write tests first (RED → GREEN → REFACTOR). Not optional.
- Tests fail: fix the implementation, not the test.

## Test Types Required
- **Unit**: isolated business logic, pure functions
- **Feature/Integration**: API endpoints, database interactions
- **E2E**: critical user workflows (Playwright for UI, Behat for API)

## Test Structure — Arrange-Act-Assert
- **Arrange**: test data and preconditions
- **Act**: execute the function/endpoint
- **Assert**: verify expected outcome

## Naming
Test names describe behavior: `"returns empty array when no users match the filter"`

## TDD Workflow
1. Write failing test (RED)
2. Confirm it fails for the right reason
3. Minimal implementation to pass (GREEN)
4. Refactor, keep tests green (REFACTOR)
5. Verify coverage threshold met
