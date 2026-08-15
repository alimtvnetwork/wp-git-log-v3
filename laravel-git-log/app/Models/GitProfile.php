<?php

namespace App\Models;

class GitProfile extends BaseModel
{
    protected $table = 'GitProfile';
    protected $primaryKey = 'GitProfileId';
    
    protected $fillable = [
        'ProfileUrl', 'ProviderId', 'OwnerName', 'IsOrganization',
        'AcceptanceId', 'SelectedRepoUrl', 'IsRestrictInBranch', 'StrictBranch'
    ];

    public function repos()
    {
        return $this->hasMany(Repo::class, 'GitProfileId', 'GitProfileId');
    }
}
