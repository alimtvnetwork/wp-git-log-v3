<?php

namespace App\Services\Contracts;

/**
 * Per-SHA file DB writer. Maintains the LRU handle pool sized by
 * ConfigKv.MaxOpenShaDbHandles per §39. acquire() MUST issue
 * `PRAGMA journal_mode=WAL; busy_timeout=5000` on first open of any
 * file DB (idempotent — WAL persists in the file header).
 */
interface SplitDbWriter
{
    public function acquire(int $shaId): \PDO;        // raw PDO; pooled, LRU-evicted
    public function release(int $shaId): void;        // returns handle to pool
    public function flushAndCloseAll(): void;         // shutdown handler binding
}
