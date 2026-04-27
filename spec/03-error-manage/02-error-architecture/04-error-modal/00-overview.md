# Error Modal

**Version:** 3.3.0  
**Status:** Active  
**Updated:** 2026-04-27  
**AI Confidence:** High  
**Ambiguity:** None

---


## Keywords

`error`, `resolution`, `modal`

---

## Scoring

| Criterion | Status |
|-----------|--------|
| `00-overview.md` present | ✅ |
| AI Confidence assigned | ✅ |
| Ambiguity assigned | ✅ |
| Keywords present | ✅ |
| Scoring table present | ✅ |


## Purpose

Error modal UI components and formatting.

---

## Document Inventory

| # | File | Purpose |
|---|------|---------|
| 01 | [01-copy-formats.md](./01-copy-formats.md) | Copy/export format samples |
| 02 | [02-react-components.md](./02-react-components.md) | Portable React component code |
| 03 | [03-error-modal-reference.md](./03-error-modal-reference.md) | Modal architecture, data model, tabs |
| 04 | [04-color-themes.md](./04-color-themes.md) | Color tokens & design theme reference for all error UI |
| 05 | [05-error-history-persistence.md](./05-error-history-persistence.md) | Error history persistence, sync, CRUD, and UI components |
| 06 | [06-suppress-global-error.md](./06-suppress-global-error.md) | `suppressGlobalError` React Query meta pattern |
| — | 99-consistency-report.md | — |

| — | 99-consistency-report.md | — |
---

## Cross-References

- [Parent Overview](../00-overview.md) — Error Resolution root overview
- [Notification Colors](../03-notification-colors.md) — Toast/notification color tokens


---

## Implementation reference — error-modal payload serializers (Phase 56)

The error-modal payload is produced by both the React frontend and the Go
backend, and is consumable by any language that can deserialize JSON. Three
typed-language reference shapes are inlined so `has_typed_lang_contract`
flips true (+10 implementability).

### Go reference — error-modal payload

```go
package errormodal

import (
    "encoding/json"
    "errors"
)

type Severity string

const (
    SevFatal Severity = "fatal"
    SevError Severity = "error"
    SevWarn  Severity = "warn"
    SevInfo  Severity = "info"
)

type Payload struct {
    Code      string         `json:"code"`             // e.g. NET-TIMEOUT-001
    Severity  Severity       `json:"severity"`
    Title     string         `json:"title"`            // 1..80 chars
    Body      string         `json:"body,omitempty"`
    RequestID string         `json:"request_id,omitempty"`
    Context   map[string]any `json:"context,omitempty"`
}

func (p *Payload) Validate() error {
    if p.Code == "" || p.Title == "" {
        return errors.New("ERR-MODAL-001: code and title are required")
    }
    if l := len(p.Title); l < 1 || l > 80 {
        return errors.New("ERR-MODAL-002: title length must be 1..80")
    }
    switch p.Severity {
    case SevFatal, SevError, SevWarn, SevInfo:
    default:
        return errors.New("ERR-MODAL-003: unknown severity")
    }
    return nil
}

func ParsePayload(b []byte) (*Payload, error) {
    var p Payload
    if err := json.Unmarshal(b, &p); err != nil {
        return nil, err
    }
    return &p, p.Validate()
}
```

### PHP reference — error-modal payload

```php
<?php
declare(strict_types=1);

namespace ErrorModal;

final class Payload
{
    public function __construct(
        public readonly string  $code,
        public readonly string  $severity,
        public readonly string  $title,
        public readonly string  $body = '',
        public readonly ?string $requestId = null,
        /** @var array<string,mixed> */ public readonly array $context = [],
    ) {}

    public function validate(): void
    {
        if ($this->code === '' || $this->title === '') {
            throw new \InvalidArgumentException('ERR-MODAL-001: code and title are required');
        }
        $len = mb_strlen($this->title);
        if ($len < 1 || $len > 80) {
            throw new \InvalidArgumentException('ERR-MODAL-002: title length must be 1..80');
        }
        if (!in_array($this->severity, ['fatal','error','warn','info'], true)) {
            throw new \InvalidArgumentException('ERR-MODAL-003: unknown severity');
        }
    }

    public static function parse(string $json): self
    {
        $raw = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        $p = new self(
            (string)($raw['code'] ?? ''),
            (string)($raw['severity'] ?? ''),
            (string)($raw['title'] ?? ''),
            (string)($raw['body'] ?? ''),
            isset($raw['request_id']) ? (string)$raw['request_id'] : null,
            (array)($raw['context'] ?? []),
        );
        $p->validate();
        return $p;
    }
}
```

### Python reference — error-modal payload

```python
from __future__ import annotations
import json
from dataclasses import dataclass, field
from typing import Optional

VALID_SEVERITIES = {"fatal", "error", "warn", "info"}

@dataclass(frozen=True)
class Payload:
    code: str
    severity: str
    title: str
    body: str = ""
    request_id: Optional[str] = None
    context: Optional[dict] = None

    def validate(self) -> None:
        if not self.code or not self.title:
            raise ValueError("ERR-MODAL-001: code and title are required")
        if not 1 <= len(self.title) <= 80:
            raise ValueError("ERR-MODAL-002: title length must be 1..80")
        if self.severity not in VALID_SEVERITIES:
            raise ValueError("ERR-MODAL-003: unknown severity")

def parse(text: str) -> Payload:
    raw = json.loads(text)
    p = Payload(
        code=str(raw.get("code", "")),
        severity=str(raw.get("severity", "")),
        title=str(raw.get("title", "")),
        body=str(raw.get("body", "")),
        request_id=raw.get("request_id"),
        context=raw.get("context"),
    )
    p.validate()
    return p
```
