<?php

namespace App\Services\Database;

use App\Services\Contracts\ShaRegistryRepository;
use Illuminate\Support\Facades\DB;

class SqliteShaRegistryRepository implements ShaRegistryRepository
{
    public function resolveOrCreate(string $repoUrl, string $branch, string $sha): int
    {
        $pdo = DB::connection('sqlite')->getPdo(); // Assuming 'sqlite' is the root db
        
        // Ensure immediate write lock for writes
        $pdo->exec('BEGIN IMMEDIATE');
        
        try {
            $stmt = $pdo->prepare('SELECT ShaId FROM ShaRegistry WHERE RepoUrl = ? AND Branch = ? AND Sha = ?');
            $stmt->execute([$repoUrl, $branch, $sha]);
            $id = $stmt->fetchColumn();

            if ($id !== false) {
                $pdo->exec('COMMIT');
                return (int) $id;
            }

            $stmt = $pdo->prepare('INSERT INTO ShaRegistry (RepoUrl, Branch, Sha, CreatedAt) VALUES (?, ?, ?, ?)');
            $stmt->execute([$repoUrl, $branch, $sha, gmdate('Y-m-d\TH:i:s\Z')]);
            $id = (int) $pdo->lastInsertId();
            
            $pdo->exec('COMMIT');
            return $id;
            
        } catch (\Exception $e) {
            $pdo->exec('ROLLBACK');
            throw $e;
        }
    }

    public function findByCompositeKey(string $repoUrl, string $branch, string $sha): ?object
    {
        $pdo = DB::connection('sqlite')->getPdo();
        $stmt = $pdo->prepare('SELECT * FROM ShaRegistry WHERE RepoUrl = ? AND Branch = ? AND Sha = ?');
        $stmt->execute([$repoUrl, $branch, $sha]);
        $row = $stmt->fetch(\PDO::FETCH_OBJ);
        return $row ?: null;
    }
}
