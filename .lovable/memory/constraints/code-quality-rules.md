# Code Quality and Error Management Rules

**Status:** Active
**Updated:** 2026-08-11

## 1. Error Management
- All caught errors must be explicitly logged following the guidelines in the error manage folder.
- Query wrapper for PHP/Python/TS must automatically log failures to reduce scattered logging code.
- Ensure all try-catch blocks log errors according to the error manage folder.

## 2. Typing and Enums
- Replace TypeScript string union types with Enums (e.g., avoid `"pass" | "fail" | "fallback"`). Enums are required.
- Every single Enum must end with the suffix `Type` (e.g., `StatusType` instead of `Status`).

## 3. Code Conventions
- Use explicit boolean state checks like `response.isFail`. NEVER use inverted success booleans (e.g., `!response.isSuccess`).
- Do not introduce any magic strings or magic numbers anywhere unless it is explicitly for the logger, and mention that in the typing.
- All code must run standalone in CI/CD and locally.
- Group similar code changes into single commits with nice commit messages.
