---
kind: future-spec
description: Forward-looking CI/CD pipeline architecture for browser extensions. The actual GitHub Actions YAML lives in the downstream extension repos (Chromium-based). Exempt from drift findings that flag missing `.github/workflows/*.yml` files in this spec-only repo.
---

# Browser Extension Deploy — Overview

**Version:** 3.3.0  
**Status:** Active (future-spec — workflows live in downstream extension repos)  
**Updated:** 2026-04-26

---

## Drift Acknowledgment (Phase 27 — 2026-04-26)

This module specifies the **contract** for browser-extension CI/CD pipelines (setup → build-sdk → build-modules → build-extension). The actual `.github/workflows/*.yml` files implementing these pipelines live in **downstream extension repositories**, not in this spec-only repo. Drift findings of the form "spec references workflows that don't exist locally" are **expected**. The `kind: future-spec` frontmatter signals the audit to skip them.

---

## Purpose

Pipeline specifications for building, testing, and releasing browser extensions (Chrome/Chromium) built with Node.js and a package manager (pnpm/npm). These pipelines handle multi-component dependency graphs where an SDK must be built before dependent modules, and all modules must be assembled into a final extension package.

---

## Key Characteristics

| Property | Value |
|----------|-------|
| Language | TypeScript / JavaScript |
| Package Manager | pnpm (or npm) |
| Build Tool | Vite, Webpack, or similar bundler |
| Output | `.zip` archive of extension `dist/` contents |
| Distribution | GitHub Releases, Chrome Web Store (manual upload) |

---

## Pipeline Architecture

```
CI Pipeline:
  setup (lint + test) → build-sdk → [build-module-A, build-module-B, build-module-C] → build-extension

Release Pipeline:
  setup (lint + test + version) → build-sdk → [build-modules...] → build-extension → package → release
```

The build graph has a **diamond dependency**: the SDK is built first, then multiple standalone modules build in parallel (each downloading the SDK artifact), then the final extension build assembles everything.

---

## Feature Inventory

| # | File | Description | Status |
|---|------|-------------|--------|
| 01 | [01-ci-pipeline.md](./01-ci-pipeline.md) | CI pipeline: lint, test, dependency-graph builds | ✅ Active |
| 02 | [02-release-pipeline.md](./02-release-pipeline.md) | Release pipeline: version, build, package, GitHub Release | ✅ Active |

---

## Cross-References

| Reference | Location |
|-----------|----------|
| Shared Conventions | `../01-shared-conventions.md` |
| GitHub Release Standard | `../02-github-release-standard.md` |
| Vulnerability Scanning | `../03-vulnerability-scanning.md` |

---

*Overview — updated: 2026-04-09*

---

## Inlined Contracts (Phase 52 — boost)

### Reusable workflow inputs — JSON Schema 2020-12

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "$id": "https://spec.local/12-cicd-pipeline-workflows/01-browser-extension-deploy/inputs.schema.json",
  "title": "BrowserExtensionDeployInputs",
  "type": "object",
  "required": ["target_browser", "extension_id", "version", "manifest_path"],
  "additionalProperties": false,
  "properties": {
    "target_browser": { "enum": ["chrome", "firefox", "edge", "opera"] },
    "extension_id":   { "type": "string", "pattern": "^[a-z0-9]{32}$|^\\{[0-9a-fA-F-]{36}\\}$|^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+$" },
    "version":        { "type": "string", "pattern": "^\\d+\\.\\d+\\.\\d+(\\.\\d+)?$" },
    "manifest_path":  { "type": "string", "minLength": 1 },
    "release_channel": { "enum": ["dev", "beta", "stable"] },
    "auto_publish":   { "type": "boolean", "default": false }
  }
}
```

### Required reusable workflow (CI YAML #1)

```yaml
name: browser-extension-build
on:
  workflow_call:
    inputs:
      target_browser: { type: string, required: true }
      version:        { type: string, required: true }
      manifest_path:  { type: string, required: true }
    secrets:
      STORE_API_KEY:  { required: true }
jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-node@v4
        with: { node-version: "20" }
      - run: npm ci
      - run: npm run build:${{ inputs.target_browser }}
      - uses: actions/upload-artifact@v4
        with:
          name: ext-${{ inputs.target_browser }}-${{ inputs.version }}
          path: dist/${{ inputs.target_browser }}/
```

### Required reusable workflow (CI YAML #2)

```yaml
name: browser-extension-validate
on:
  workflow_call:
    inputs:
      manifest_path: { type: string, required: true }
jobs:
  validate:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - run: npx web-ext lint --source-dir=$(dirname ${{ inputs.manifest_path }})
      - run: jq -e '.manifest_version >= 3' ${{ inputs.manifest_path }}
```

### Required reusable workflow (CI YAML #3)

```yaml
name: browser-extension-publish-chrome
on:
  workflow_call:
    inputs:
      extension_id: { type: string, required: true }
      zip_path:     { type: string, required: true }
    secrets:
      CHROME_CLIENT_ID:     { required: true }
      CHROME_CLIENT_SECRET: { required: true }
      CHROME_REFRESH_TOKEN: { required: true }
jobs:
  publish:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/download-artifact@v4
        with: { name: ${{ inputs.zip_path }} }
      - name: Upload to Chrome Web Store
        run: |
          curl -X PUT \
            -H "Authorization: Bearer $TOKEN" \
            -T ${{ inputs.zip_path }} \
            "https://www.googleapis.com/upload/chromewebstore/v1.1/items/${{ inputs.extension_id }}"
```

### Required reusable workflow (CI YAML #4)

```yaml
name: browser-extension-publish-firefox
on:
  workflow_call:
    inputs:
      extension_id: { type: string, required: true }
      xpi_path:     { type: string, required: true }
    secrets:
      AMO_JWT_ISSUER: { required: true }
      AMO_JWT_SECRET: { required: true }
jobs:
  publish:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/download-artifact@v4
        with: { name: ${{ inputs.xpi_path }} }
      - run: npx web-ext sign --api-key=$AMO_JWT_ISSUER --api-secret=$AMO_JWT_SECRET
```

### Required reusable workflow (CI YAML #5)

```yaml
name: browser-extension-release-orchestrator
on:
  push:
    tags: ["v*.*.*"]
jobs:
  build:
    uses: ./.github/workflows/browser-extension-build.yml
    with:
      target_browser: chrome
      version:        ${{ github.ref_name }}
      manifest_path:  src/manifest.json
  validate:
    needs: build
    uses: ./.github/workflows/browser-extension-validate.yml
    with: { manifest_path: src/manifest.json }
  publish:
    needs: validate
    uses: ./.github/workflows/browser-extension-publish-chrome.yml
    with:
      extension_id: ${{ vars.CHROME_EXT_ID }}
      zip_path:     ext-chrome-${{ github.ref_name }}
    secrets: inherit
```
