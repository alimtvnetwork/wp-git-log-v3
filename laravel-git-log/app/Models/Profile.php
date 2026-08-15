<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class Profile extends BaseModel implements AuthenticatableContract
{
    use HasApiTokens, Authenticatable;

    protected $table = 'Profile';
    protected $primaryKey = 'ProfileId';
    
    protected $fillable = ['UserName', 'Email', 'GeneratedKeyApi', 'Token', 'TempToken', 'UserStatusId', 'CreatedAt', 'UpdatedAt'];
}
