# Phase 2 — Enums and Coding Style

**Version:** 1.1.0  
**Status:** Complete  
**Updated:** 2026-04-26  
**AI Confidence:** High  
**Ambiguity:** None

> **Purpose:** Define enum patterns, coding style, and naming conventions for WordPress plugins.

---

## Index

| File | Purpose |
|------|---------|
| [01-enum-architecture.md](01-enum-architecture.md) | Core enum pattern, standard categories, comparison methods, coding style, naming |
| [02-enum-metadata-pattern.md](02-enum-metadata-pattern.md) | `match`-based metadata methods (label, icon, cssClass) and `is*()` helpers |
| [03-self-update-status-enum.md](03-self-update-status-enum.md) | `SelfUpdateStatusType` — reference impl (17 cases, deployment domain) |
| [04-action-type-enum.md](04-action-type-enum.md) | `ActionType` — reference impl (40+ cases, transaction logging domain) |

---

## Quick Reference

### Standard Enum Template

```php
enum ExampleType: string
{
    case SomeName  = 'some_value';
    case OtherName = 'other_value';

    // Per-case helpers
    public function isSomeName(): bool  { return $this->isEqual(self::SomeName); }
    public function isOtherName(): bool { return $this->isEqual(self::OtherName); }

    // Standard comparison methods
    public function isEqual(self $other): bool { return $this === $other; }
    public function isOtherThan(self $other): bool { return $this !== $other; }
    public function isAnyOf(self ...$others): bool { return in_array($this, $others, true); }
}
```

### Metadata via `match` (PHP)

```php
public function label(): string
{
    return match ($this) {
        self::SomeName  => 'Some Label',
        self::OtherName => 'Other Label',
    };
}
```

See [02-enum-metadata-pattern.md](02-enum-metadata-pattern.md) for the full pattern.

---

## Reference Implementation (NORMATIVE)

This is the **single inlined reference contract** for the WP plugin enum
pattern. Every PHP enum across plugin domains MUST mirror this shape — backed
string type, per-case `is{CaseName}()` helpers, the three comparison methods,
`match`-based `label()`, and the JSON wire form. Deviations are non-conformant.

### PHP reference: `src/Enums/SelfUpdateStatusType.php`

```php
<?php
/**
 * Reference enum implementation per
 * spec/18-wp-plugin-how-to/02-enums-and-coding-style/00-overview.md.
 *
 * Wire form: lower_snake_case string. Width is part of the contract.
 */
declare(strict_types=1);

namespace RiseupAsia\WpPlugin\Enums;

use JsonSerializable;

enum SelfUpdateStatusType: string implements JsonSerializable
{
    case Idle        = 'idle';
    case Checking    = 'checking';
    case UpToDate    = 'up_to_date';
    case Available   = 'available';
    case Downloading = 'downloading';
    case Installing  = 'installing';
    case Failed      = 'failed';

    // ----- Per-case helpers (one method per case) -----
    public function isIdle(): bool        { return $this->isEqual(self::Idle); }
    public function isChecking(): bool    { return $this->isEqual(self::Checking); }
    public function isUpToDate(): bool    { return $this->isEqual(self::UpToDate); }
    public function isAvailable(): bool   { return $this->isEqual(self::Available); }
    public function isDownloading(): bool { return $this->isEqual(self::Downloading); }
    public function isInstalling(): bool  { return $this->isEqual(self::Installing); }
    public function isFailed(): bool      { return $this->isEqual(self::Failed); }

    // ----- Standard comparison methods (REQUIRED on every enum) -----
    public function isEqual(self $other): bool      { return $this === $other; }
    public function isOtherThan(self $other): bool  { return $this !== $other; }
    public function isAnyOf(self ...$others): bool  { return in_array($this, $others, true); }

    // ----- Metadata via match (single source of truth for human label) -----
    public function label(): string
    {
        return match ($this) {
            self::Idle        => 'Idle',
            self::Checking    => 'Checking for updates…',
            self::UpToDate    => 'Up to date',
            self::Available   => 'Update available',
            self::Downloading => 'Downloading…',
            self::Installing  => 'Installing…',
            self::Failed      => 'Update failed',
        };
    }

    // ----- Collection helpers -----
    /** @return list<self> */
    public static function all(): array { return self::cases(); }

    /**
     * Strict parse; throws on unknown wire value (never silently returns null).
     * Callers that want a nullable result should use `tryFrom` directly.
     */
    public static function parse(string $wire): self
    {
        $v = self::tryFrom($wire);
        if ($v === null) {
            throw new \ValueError(sprintf('SelfUpdateStatusType: unknown wire value %s', var_export($wire, true)));
        }
        return $v;
    }

    // ----- JSON wire form (string, never integer) -----
    public function jsonSerialize(): string { return $this->value; }
}
```

### Cross-language wire-format mirror (TypeScript)

Any TypeScript consumer of an API returning this enum MUST mirror the wire
strings exactly. Both sides ship in the same commit (G-CON-01 lockstep rule).

```ts
// Mirror of src/Enums/SelfUpdateStatusType.php
export const SelfUpdateStatus = {
  Idle:        "idle",
  Checking:    "checking",
  UpToDate:    "up_to_date",
  Available:   "available",
  Downloading: "downloading",
  Installing:  "installing",
  Failed:      "failed",
} as const;

export type SelfUpdateStatus =
  (typeof SelfUpdateStatus)[keyof typeof SelfUpdateStatus];

export const ALL_SELF_UPDATE_STATUSES: readonly SelfUpdateStatus[] = [
  SelfUpdateStatus.Idle,
  SelfUpdateStatus.Checking,
  SelfUpdateStatus.UpToDate,
  SelfUpdateStatus.Available,
  SelfUpdateStatus.Downloading,
  SelfUpdateStatus.Installing,
  SelfUpdateStatus.Failed,
] as const;

export function isSelfUpdateStatus(s: string): s is SelfUpdateStatus {
  return (ALL_SELF_UPDATE_STATUSES as readonly string[]).includes(s);
}
```

### JSON Schema (Draft 2020-12) for the wire form

Use this schema at any HTTP/REST boundary that accepts a `selfUpdateStatus`
field:

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "$id": "https://riseup.asia/schemas/self-update-status.json",
  "title": "SelfUpdateStatus",
  "type": "string",
  "enum": [
    "idle",
    "checking",
    "up_to_date",
    "available",
    "downloading",
    "installing",
    "failed"
  ]
}
```

### Forbidden shapes (lint-enforced)

| Shape | Why forbidden |
|---|---|
| `enum X { case A; }` (untyped / non-backed) | Wire form is mandatory string. |
| `enum X: int` | Wire form must be string for cross-language stability. |
| Missing `isEqual` / `isOtherThan` / `isAnyOf` | Comparison contract incomplete. |
| `label()` not using `match` | Drift between cases and labels. |
| `jsonSerialize` returning anything other than `$this->value` | Breaks wire format. |
| Silent `parse()` (returns null on unknown) | Use `tryFrom` instead — `parse` MUST throw. |

---

## Cross-References

- [Go Enum Specification](../../02-coding-guidelines/03-golang/01-enum-specification/00-overview.md) — equivalent pattern for Go
- [Go Info-Object Pattern](../../02-coding-guidelines/03-golang/01-enum-specification/05-info-object-pattern.md) — Go version of the metadata pattern (uses info-object, not `match`)
- [Phase 10 — Deployment Patterns](../10-deployment-patterns.md) — uses `SelfUpdateStatusType`

---

## Drift Acknowledgment

**Date:** 2026-04-26  
**Severity:** Low — doc-hygiene drift.

AC-01 mandates Version/Updated banner; current overview snippet is awaiting the banner backfill in a follow-up minor bump.

Tracked under Phase 27d. See `.lovable/memory/index.md`.

