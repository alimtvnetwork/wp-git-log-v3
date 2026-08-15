<?php

namespace App\Models;

class SshKey extends BaseModel
{
    protected $table = 'SshKey';
    protected $primaryKey = 'SshKeyId';
    public $timestamps = false;
    
    protected $fillable = ['Fingerprint', 'RepoId', 'KeyType', 'PublicKey', 'Label', 'OwnedByProfileId', 'IsActive', 'LastUsedAt', 'CreatedAt', 'RevokedAt'];
}
