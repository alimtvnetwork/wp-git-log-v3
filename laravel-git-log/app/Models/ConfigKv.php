<?php

namespace App\Models;

class ConfigKv extends BaseModel
{
    protected $table = 'ConfigKv';
    protected $primaryKey = 'KeyName';
    public $incrementing = false;
    protected $keyType = 'string';
    
    public const CREATED_AT = null;

    protected $fillable = ['KeyName', 'ValueText', 'UpdatedAt'];
}
