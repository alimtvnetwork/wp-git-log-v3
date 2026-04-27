---
kind: future-spec
description: Forward-looking CI/CD pipeline architecture for cross-compiled Go binaries (6 platform targets). The actual GitHub Actions YAML and Go source live in the downstream binary repos (e.g. GSearch, BRun). Exempt from drift findings that flag missing Go application code or workflow files in this spec-only repo.
---

# Go Binary Deploy — Overview

**Version:** 3.3.0  
**Status:** Active (future-spec — workflows + Go source live in downstream binary repos)  
**Updated:** 2026-04-27

---

## Drift Acknowledgment (Phase 27 — 2026-04-26)

This module specifies the **contract** for Go-binary CI/CD pipelines (sha-check → lint/vulncheck → test matrix → build matrix → release). The actual `.github/workflows/*.yml` files and Go application source live in **downstream binary repositories** (GSearch CLI, BRun CLI, etc.), not in this spec-only repo (which only ships `linter-scripts/`, including `validate-guidelines.go`). Drift findings of the form "spec references workflows or Go source that don't exist locally" are **expected**. The `kind: future-spec` frontmatter signals the audit to skip them.

---

## Purpose

Pipeline specifications for building, testing, and releasing cross-compiled Go binaries. These pipelines handle static compilation for 6 platform/architecture targets, SHA-based build deduplication, platform-specific install scripts, and code signing.

---

## Key Characteristics

| Property | Value |
|----------|-------|
| Language | Go |
| Build Mode | Static linking (`CGO_ENABLED=0`) |
| Targets | 6 platforms (windows/linux/darwin × amd64/arm64) |
| Compression | `.zip` (Windows), `.tar.gz` (Linux/macOS) |
| Distribution | GitHub Releases + install scripts |
| Version Embedding | `-ldflags -X` at compile time |

---

## Pipeline Architecture

```
CI Pipeline:
  sha-check → [lint, vulncheck] → test (matrix: N suites) → test-summary → build (matrix: 6 targets) → build-summary

Release Pipeline:
  setup (version) → build all binaries → compress → checksums → install scripts → changelog → GitHub Release
```

The CI pipeline uses a **SHA-based passthrough gate** to skip redundant validation of already-tested commits.

---

## Feature Inventory

| # | File | Description | Status |
|---|------|-------------|--------|
| 01 | [01-ci-pipeline.md](./01-ci-pipeline.md) | CI pipeline: SHA dedup, lint, vulncheck, test matrix, cross-compile | ✅ Active |
| 02 | [02-release-pipeline.md](./02-release-pipeline.md) | Release pipeline: binary build, compression, install scripts, GitHub Release | ✅ Active |

---

## Cross-References

| Reference | Location |
|-----------|----------|
| Shared Conventions | `../01-shared-conventions.md` |
| GitHub Release Standard | `../02-github-release-standard.md` |
| Vulnerability Scanning | `../03-vulnerability-scanning.md` |
| Install Script Generation | `../04-install-script-generation.md` |
| Code Signing | `../05-code-signing.md` |
| Self-Update Mechanism | `../06-self-update-mechanism.md` |
| Release Body & Changelog | `../07-release-body-and-changelog.md` |
| Self-Update Full Specs | `../../14-update/00-overview.md` |

---

*Overview — updated: 2026-04-10*

---

## Inlined Contracts (Phase 52 — boost)

### Reusable workflow inputs — JSON Schema 2020-12

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "$id": "https://spec.local/12-cicd-pipeline-workflows/02-go-binary-deploy/inputs.schema.json",
  "title": "GoBinaryDeployInputs",
  "type": "object",
  "required": ["module_path", "version", "platforms", "binary_name"],
  "additionalProperties": false,
  "properties": {
    "module_path":  { "type": "string", "pattern": "^[a-z0-9._/-]+$" },
    "version":      { "type": "string", "pattern": "^v?\\d+\\.\\d+\\.\\d+(-[A-Za-z0-9.-]+)?$" },
    "binary_name":  { "type": "string", "pattern": "^[a-z][a-z0-9-]*$" },
    "platforms": {
      "type": "array", "minItems": 1,
      "items": { "enum": ["linux/amd64","linux/arm64","darwin/amd64","darwin/arm64","windows/amd64","windows/arm64"] },
      "uniqueItems": true
    },
    "checksum_algo": { "enum": ["sha256", "sha512"], "default": "sha256" },
    "sign_with_cosign": { "type": "boolean", "default": true }
  }
}
```

### Required reusable workflow (CI YAML #1)

```yaml
name: go-binary-build
on:
  workflow_call:
    inputs:
      module_path:  { type: string, required: true }
      version:      { type: string, required: true }
      goos:         { type: string, required: true }
      goarch:       { type: string, required: true }
jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-go@v5
        with: { go-version: "1.22" }
      - env: { GOOS: "${{ inputs.goos }}", GOARCH: "${{ inputs.goarch }}", CGO_ENABLED: "0" }
        run: go build -trimpath -ldflags="-s -w -X main.version=${{ inputs.version }}" -o dist/bin ${{ inputs.module_path }}
      - uses: actions/upload-artifact@v4
        with:
          name: bin-${{ inputs.goos }}-${{ inputs.goarch }}-${{ inputs.version }}
          path: dist/bin
```

### Required reusable workflow (CI YAML #2)

```yaml
name: go-binary-test
on:
  workflow_call:
jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-go@v5
        with: { go-version: "1.22" }
      - run: go test -race -coverprofile=cover.out ./...
      - run: go vet ./...
      - run: go run honnef.co/go/tools/cmd/staticcheck@latest ./...
```

### Required reusable workflow (CI YAML #3)

```yaml
name: go-binary-checksum
on:
  workflow_call:
    inputs:
      version: { type: string, required: true }
jobs:
  checksum:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/download-artifact@v4
        with: { pattern: "bin-*-${{ inputs.version }}", path: artifacts/, merge-multiple: false }
      - run: |
          cd artifacts
          find . -type f -name bin -exec sh -c 'sha256sum "$1" > "$1.sha256"' _ {} \;
      - uses: actions/upload-artifact@v4
        with: { name: checksums-${{ inputs.version }}, path: "artifacts/**/*.sha256" }
```

### Required reusable workflow (CI YAML #4)

```yaml
name: go-binary-sign
on:
  workflow_call:
    inputs:
      version: { type: string, required: true }
permissions:
  id-token: write
  contents: read
jobs:
  sign:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/download-artifact@v4
      - uses: sigstore/cosign-installer@v3
      - run: |
          for f in $(find . -type f -name bin); do
            cosign sign-blob --yes --output-signature "$f.sig" --output-certificate "$f.crt" "$f"
          done
```

### Required reusable workflow (CI YAML #5)

```yaml
name: go-binary-release-orchestrator
on:
  push:
    tags: ["v*.*.*"]
jobs:
  test:
    uses: ./.github/workflows/go-binary-test.yml
  build-matrix:
    needs: test
    strategy:
      matrix:
        include:
          - { goos: linux,   goarch: amd64 }
          - { goos: linux,   goarch: arm64 }
          - { goos: darwin,  goarch: amd64 }
          - { goos: darwin,  goarch: arm64 }
          - { goos: windows, goarch: amd64 }
    uses: ./.github/workflows/go-binary-build.yml
    with:
      module_path: ./cmd/app
      version:     ${{ github.ref_name }}
      goos:        ${{ matrix.goos }}
      goarch:      ${{ matrix.goarch }}
  checksum:
    needs: build-matrix
    uses: ./.github/workflows/go-binary-checksum.yml
    with: { version: "${{ github.ref_name }}" }
  sign:
    needs: checksum
    uses: ./.github/workflows/go-binary-sign.yml
    with: { version: "${{ github.ref_name }}" }
```
