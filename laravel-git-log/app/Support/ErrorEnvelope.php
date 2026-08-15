<?php

declare(strict_types=1);

namespace App\Support;

class ErrorEnvelope
{
    public function __construct(
        public readonly string $code,
        public readonly string $message,
        public readonly string $severity, // fatal|error|warning|info
        public readonly string $timestamp, // ISO-8601
        public readonly ?string $traceId = null,
        public readonly array $details = [],
    ) {}

    public function validate(): void
    {
        if (!\preg_match('/^[A-Z]{2,5}-[A-Z]+-\d{2,4}$/', $this->code)) {
            throw new \InvalidArgumentException("invalid code: {$this->code}");
        }
        if ($this->message === '') {
            throw new \InvalidArgumentException('message is required');
        }
        if (!\in_array($this->severity, ['fatal','error','warning','info'], true)) {
            throw new \InvalidArgumentException("invalid severity: {$this->severity}");
        }
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'message' => $this->message,
            'severity' => $this->severity,
            'timestamp' => $this->timestamp,
            'trace_id' => $this->traceId,
            'details' => $this->details,
        ];
    }
}
