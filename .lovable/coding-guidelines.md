# Coding Guidelines

- Runtime: Node.js (React) / PHP (Laravel) / Golang (CLI: `rlogger` / `RiseUp-Git-Logger`)
- Formatter: Prettier (JS/TS), Pint (PHP), gofmt (Go)
- Naming rules: PascalCase for Enums/Database, camelCase for TS variables, snake_case or camelCase per Laravel standard.
- CLI Name: Full Name = **RiseUp-Git-Logger** (`Rise Up Git-Logger`), Short/Binary Name = **rlogger** (`RLogger`).
- Function Size: Strict limit (<8 lines preferred, 15 lines max).
- Booleans: Must start with positive prefixes (`is`, `has`, `can`, `should`, `was`, `will`, `did`, `must`). No negative naming (`isNot`) and no inverted booleans.
- Golang: Return single wrapped `Result` struct (`IsSuccess`, `IsFailed`, `Data`, `AppError`) via constructors (`NewSuccess`/`NewFailure`).
- Codebase Integrity: Do NOT copy past coding violations from external projects (like Git Map). Strictly adhere to all non-negotiable coding guidelines.
- Error handling rules: Errors must be explicitly logged with context (operation name + key inputs), Universal Response Envelope format.
- Restrictions: See `.lovable/strictly-avoid.md`.
