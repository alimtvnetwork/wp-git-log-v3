<?php

namespace App\Models;

class RoleAssignment extends BaseModel
{
    protected $table = 'RoleAssignment';
    protected $primaryKey = 'RoleAssignmentId';
    public $timestamps = false;
    
    protected $fillable = ['ProfileId', 'RoleId'];
}
