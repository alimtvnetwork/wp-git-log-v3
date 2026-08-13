<?php

namespace App\Services\Contracts;

class IngestResult
{
    public function __construct(
        public bool $success,
        public string $errorCode = '',
        public string $message = '',
        public array $details = []
    ) {}

    public function toEnvelope(): array
    {
        return [
            'ErrorCode' => $this->errorCode ?: ($this->success ? 'GL-SUCCESS' : 'GL-INTERNAL-ERROR'),
            'TraceId'   => (string) \Illuminate\Support\Str::uuid(),
            'Message'   => $this->message,
            'Details'   => $this->details,
        ];
    }
}
