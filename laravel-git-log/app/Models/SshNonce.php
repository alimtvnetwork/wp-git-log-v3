<?php

namespace App\Models;

class SshNonce extends BaseModel
{
    protected $table = 'SshNonce';
    protected $primaryKey = 'SshNonceId';
    public $timestamps = false;
    
    protected $fillable = ['SshKeyId', 'Nonce', 'SeenAt'];
}
