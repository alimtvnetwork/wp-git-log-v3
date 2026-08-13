<?php

namespace App\Services\Contracts;

/**
 * Root DB only. Owns the (RepoUrl, Branch, Sha) → ShaId resolution
 * referenced by §39 split-DB log storage. Reads MAY use Eloquent reads
 * (Lane A read paths exempt per AC-81); writes MUST use raw PDO per AC-81.
 */
interface ShaRegistryRepository
{
    public function resolveOrCreate(string $repoUrl, string $branch, string $sha): int;
    public function findByCompositeKey(string $repoUrl, string $branch, string $sha): ?object;
}
