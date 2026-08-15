<?php

namespace App\Models;

class RepoVersion extends BaseModel
{
    protected $table = 'RepoVersion';
    protected $primaryKey = 'RepoVersionId';
    
    public const UPDATED_AT = null;

    protected $fillable = ['RepoId', 'VersionSuffix', 'RepoUrl'];

    public function repo()
    {
        return $this->belongsTo(Repo::class, 'RepoId', 'RepoId');
    }
}
