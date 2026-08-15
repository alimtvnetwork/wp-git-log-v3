<?php

namespace App\Models;

class Role extends BaseModel
{
    protected $table = 'Role';
    protected $primaryKey = 'RoleId';
    public $timestamps = false;
    
    protected $fillable = ['Name'];
}
