
# PowerShell Integration

**Version:** 3.4.0  **Updated:** 2026-04-27

---

## Overview

PowerShell integration guidelines, scripting conventions, and best practices for cross-platform automation within the project ecosystem.

---

## Contents

_No content yet. Add PowerShell-related specs as numbered files within this folder._

---

## Cross-References

| Reference | Location |
|-----------|----------|
| Cross-Language Guidelines | [../01-cross-language/00-overview.md](../01-cross-language/00-overview.md) |
| Coding Guidelines Spec | [../00-overview.md](../00-overview.md) |


---

## PowerShell integration contracts (Phase 54)

This module previously had no machine-readable contracts. Phase 54 introduces a
minimal but complete contract surface so a mediocre AI generator can implement
PowerShell automation scripts that integrate with the project ecosystem
(CI/CD, build pipelines, deployment) without reading sibling specs.

### Script descriptor — JSON Schema 2020-12

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "$id": "https://spec.local/02-coding-guidelines/09-powershell-integration/script-descriptor.schema.json",
  "title": "PowerShellScriptDescriptor",
  "type": "object",
  "required": ["name", "purpose", "platform", "min_pwsh_version", "exit_codes"],
  "additionalProperties": false,
  "properties": {
    "name":              { "type": "string", "pattern": "^[A-Z][a-zA-Z0-9-]+\\.ps1$" },
    "purpose":           { "type": "string", "minLength": 10, "maxLength": 280 },
    "platform":          { "enum": ["windows", "linux", "macos", "cross"] },
    "min_pwsh_version":  { "type": "string", "pattern": "^\\d+\\.\\d+$" },
    "requires_elevation":{ "type": "boolean", "default": false },
    "parameters": {
      "type": "array",
      "items": {
        "type": "object",
        "required": ["name", "type", "mandatory"],
        "additionalProperties": false,
        "properties": {
          "name":      { "type": "string", "pattern": "^[A-Z][a-zA-Z0-9]+$" },
          "type":      { "enum": ["string", "int", "bool", "switch", "path", "uri"] },
          "mandatory": { "type": "boolean" },
          "default":   {}
        }
      }
    },
    "exit_codes": {
      "type": "object",
      "patternProperties": {
        "^[0-9]+$": { "type": "string", "minLength": 3 }
      },
      "minProperties": 2
    }
  }
}
```

### Verb / parameter-type enums (TypeScript)

```ts
export enum PowerShellApprovedVerb {
  Get    = "Get",     // retrieve information
  Set    = "Set",     // modify state
  New    = "New",     // create resource
  Remove = "Remove",  // delete resource
  Test   = "Test",    // assertion
  Invoke = "Invoke",  // perform action
  Sync   = "Sync",    // align two states
  Export = "Export",  // serialize out
  Import = "Import",  // deserialize in
}

export enum PowerShellParameterType {
  String = "string",
  Int    = "int",
  Bool   = "bool",
  Switch = "switch",
  Path   = "path",
  Uri    = "uri",
}

export enum PowerShellExitCode {
  Success            = 0,
  GenericFailure     = 1,
  InvalidArguments   = 2,
  PermissionDenied   = 3,
  DependencyMissing  = 4,
  RemoteUnreachable  = 5,
  Timeout            = 124,
}
```

### CI workflow integration — GitHub Actions YAML

```yaml
# .github/workflows/powershell-lint.yml
name: powershell-lint
on:
  pull_request:
    paths: ['**/*.ps1', '**/*.psm1']
jobs:
  pssa:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - name: Install PSScriptAnalyzer
        shell: pwsh
        run: Install-Module -Name PSScriptAnalyzer -Force -Scope CurrentUser
      - name: Lint
        shell: pwsh
        run: Invoke-ScriptAnalyzer -Path . -Recurse -Severity Error,Warning -EnableExit
```

```yaml
# .github/workflows/powershell-test.yml
name: powershell-test
on: [push, pull_request]
jobs:
  pester:
    strategy:
      matrix:
        os: [ubuntu-latest, windows-latest, macos-latest]
    runs-on: ${{ matrix.os }}
    steps:
      - uses: actions/checkout@v4
      - name: Run Pester
        shell: pwsh
        run: |
          Install-Module Pester -Force -Scope CurrentUser
          Invoke-Pester -CI
```

```yaml
# .github/workflows/powershell-publish.yml
name: powershell-publish
on:
  release:
    types: [published]
jobs:
  publish:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - name: Publish to PSGallery
        shell: pwsh
        env:
          NUGET_API_KEY: ${{ secrets.PSGALLERY_API_KEY }}
        run: Publish-Module -Path ./module -NuGetApiKey $env:NUGET_API_KEY
```

```yaml
# .github/workflows/powershell-format.yml
name: powershell-format
on: pull_request
jobs:
  format:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - name: Format check
        shell: pwsh
        run: |
          Install-Module PSScriptAnalyzer -Force -Scope CurrentUser
          Invoke-Formatter -ScriptDefinition (Get-Content -Raw ./scripts/*.ps1) | Out-Null
```

```yaml
# .github/workflows/powershell-security.yml
name: powershell-security
on:
  schedule: [{ cron: '0 6 * * 1' }]
jobs:
  audit:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - name: Security audit
        shell: pwsh
        run: |
          Install-Module PSScriptAnalyzer -Force -Scope CurrentUser
          Invoke-ScriptAnalyzer -Path . -Recurse -Severity Error -IncludeRule PSAvoidUsingPlainTextForPassword,PSAvoidUsingConvertToSecureStringWithPlainText -EnableExit
```
