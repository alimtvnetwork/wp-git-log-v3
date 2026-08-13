<?php

namespace App\Services\Contracts;

/**
 * Owns the §04 §1.2 11-step pre-parse cap + validation order + dedup
 * window + per-SHA split-DB write. Concurrency contract: spec/13 §97 AC-22
 * (`BEGIN IMMEDIATE` + SQLITE_BUSY retry 3× 100 ms ±25 % jitter).
 */
interface LogIngestService
{
    public function append(array $validated): IngestResult;
    public function markFixed(array $validated): IngestResult;
    public function clearOne(array $validated): IngestResult;
    public function clearAll(array $validated): IngestResult;
}
