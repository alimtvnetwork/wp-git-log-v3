<?php

namespace App\Models;

class ShaRegistry extends BaseModel
{
    protected $table = 'ShaRegistry';
    protected $primaryKey = 'ShaRegistryId';
    
    public const CREATED_AT = null;
    public const UPDATED_AT = null;

    protected $fillable = [
        'PipelineId', 'Sha', 'DbFilePath', 'RowCount',
        'FirstSeenAt', 'LastSeenAt', 'FileSizeBytes', 'Sha256'
    ];
}
