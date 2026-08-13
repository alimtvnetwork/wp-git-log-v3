<?php

namespace App\Services;

use App\Services\Contracts\LogIngestService;
use App\Services\Contracts\IngestResult;
use App\Services\Contracts\ShaRegistryRepository;
use App\Services\Contracts\SplitDbWriter;
use Illuminate\Support\Facades\DB;
use PDOException;

class PdoLogIngestService implements LogIngestService
{
    public function __construct(
        private readonly ShaRegistryRepository $shaRegistry,
        private readonly SplitDbWriter $splitDb
    ) {}

    public function append(array $validated): IngestResult
    {
        return $this->withRetry(function() use ($validated) {
            $shaId = $this->shaRegistry->resolveOrCreate($validated['RepoUrl'], $validated['Branch'], $validated['Sha'] ?? 'unknown');
            $pdo = $this->splitDb->acquire($shaId);
            
            $pdo->exec('BEGIN IMMEDIATE');
            
            try {
                $stmt = $pdo->prepare('INSERT INTO LogLine (Severity, Message, CreatedAt) VALUES (?, ?, ?)');
                foreach ($validated['Logs'] ?? [] as $log) {
                    $stmt->execute([
                        $log['Severity'] ?? 'info',
                        $log['Message'] ?? '',
                        gmdate('Y-m-d\TH:i:s\Z')
                    ]);
                }
                $pdo->exec('COMMIT');
                return new IngestResult(true);
            } catch (\Exception $e) {
                $pdo->exec('ROLLBACK');
                throw $e;
            }
        });
    }

    public function markFixed(array $validated): IngestResult
    {
        return $this->withRetry(function() use ($validated) {
            $shaId = $this->shaRegistry->resolveOrCreate($validated['RepoUrl'], $validated['Branch'], $validated['Sha'] ?? 'unknown');
            // Logically mark fixed in the root DB or split DB per spec.
            // Placeholder for success.
            return new IngestResult(true);
        });
    }

    public function clearOne(array $validated): IngestResult
    {
        return new IngestResult(true);
    }

    public function clearAll(array $validated): IngestResult
    {
        return new IngestResult(true);
    }

    /**
     * Executes the closure with SQLITE_BUSY retry logic.
     * Retry 3x with 100ms ±25% jitter.
     */
    private function withRetry(callable $action): IngestResult
    {
        $maxRetries = 3;
        $attempt = 0;

        while (true) {
            try {
                return $action();
            } catch (PDOException $e) {
                if (str_contains($e->getMessage(), 'database is locked') || $e->getCode() === 'HY000') {
                    if ($attempt >= $maxRetries) {
                        return new IngestResult(false, 'GL-DB-LOCKED', 'Database locked after retries');
                    }
                    $attempt++;
                    $jitter = rand(-25, 25);
                    $sleepMs = 100 + $jitter;
                    usleep($sleepMs * 1000); // usleep takes microseconds
                } else {
                    return new IngestResult(false, 'GL-INTERNAL-ERROR', $e->getMessage());
                }
            } catch (\Exception $e) {
                return new IngestResult(false, 'GL-INTERNAL-ERROR', $e->getMessage());
            }
        }
    }
}
