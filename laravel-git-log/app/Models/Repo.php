<?php

namespace App\Models;

class Repo extends BaseModel
{
    protected $table = 'Repo';
    protected $primaryKey = 'RepoId';
    
    public const UPDATED_AT = null;

    protected $fillable = ['GitProfileId', 'RootRepoName', 'RepoUrl'];

    public function gitProfile()
    {
        return $this->belongsTo(GitProfile::class, 'GitProfileId', 'GitProfileId');
    }

    public function repoVersions()
    {
        return $this->hasMany(RepoVersion::class, 'RepoId', 'RepoId');
    }
}
