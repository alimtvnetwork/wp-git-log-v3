<?php

namespace App\Models;

class AppLink extends BaseModel
{
    protected $table = 'AppLink';
    protected $primaryKey = 'AppLinkId';
    public $timestamps = false;
    
    protected $fillable = ['AppId', 'AppLinkTypeId', 'GitProfileId', 'RepoId', 'CreatedAt'];
}
