<?php

namespace App\Models;

class Permission extends BaseModel
{
    protected $table = 'Permission';
    protected $primaryKey = 'PermissionId';
    public $timestamps = false;
    
    protected $fillable = ['Name'];
}
