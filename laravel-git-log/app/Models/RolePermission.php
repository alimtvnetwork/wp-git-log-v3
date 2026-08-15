<?php

namespace App\Models;

class RolePermission extends BaseModel
{
    protected $table = 'RolePermission';
    protected $primaryKey = 'RolePermissionId';
    public $timestamps = false;
    
    protected $fillable = ['RoleId', 'PermissionId'];
}
