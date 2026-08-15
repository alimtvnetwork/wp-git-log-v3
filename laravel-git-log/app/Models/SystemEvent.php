<?php

namespace App\Models;

class SystemEvent extends BaseModel
{
    protected $table = 'SystemEvent';
    protected $primaryKey = 'SystemEventId';
    public $timestamps = false;
    
    protected $fillable = ['SystemEventTypeId', 'ActorProfileId', 'TargetType', 'TargetId', 'Summary', 'DetailJson', 'OccurredAt'];
}
