<?php

namespace App\Models;

class AuditTrail extends BaseModel
{
    protected $table = 'AuditTrail';
    protected $primaryKey = 'AuditTrailId';
    public $timestamps = false;
    
    protected $fillable = ['AuditActionTypeId', 'AuditOutcomeId', 'ProfileId', 'AppId', 'RouteName', 'RequestId', 'HttpStatus', 'Detail', 'OccurredAt'];
}
