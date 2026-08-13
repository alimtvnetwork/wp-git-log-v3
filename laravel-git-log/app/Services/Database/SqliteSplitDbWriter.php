<?php

namespace App\Services\Database;

use App\Services\Contracts\SplitDbWriter;
use PDO;

class SqliteSplitDbWriter implements SplitDbWriter
{
    private array $pool = [];

    public function acquire(int $shaId): PDO
    {
        if (isset($this->pool[$shaId])) {
            return $this->pool[$shaId];
        }

        // Setup per-sha split db file
        $dbPath = storage_path("logs/split_{$shaId}.db");
        
        $pdo = new PDO("sqlite:{$dbPath}", null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        // MUST issue `PRAGMA journal_mode=WAL; busy_timeout=5000`
        $pdo->exec('PRAGMA journal_mode=WAL; PRAGMA busy_timeout=5000;');
        
        // Initial schema creation if necessary
        $pdo->exec('
            CREATE TABLE IF NOT EXISTS LogLine (
                LineId INTEGER PRIMARY KEY AUTOINCREMENT,
                Severity TEXT NOT NULL,
                Message TEXT NOT NULL,
                CreatedAt TEXT NOT NULL
            );
        ');

        $this->pool[$shaId] = $pdo;
        return $pdo;
    }

    public function release(int $shaId): void
    {
        // For simple LRU this can just return it to the pool 
        // In real LRU we'd keep track of usage time.
    }

    public function flushAndCloseAll(): void
    {
        $this->pool = [];
    }
}
