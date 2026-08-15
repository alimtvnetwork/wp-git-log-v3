<?php

namespace App\Models;

class Pipeline extends BaseModel
{
    protected $table = 'Pipeline';
    protected $primaryKey = 'PipelineId';
    
    protected $fillable = [
        'RepoVersionId', 'AppId', 'Branch', 'Pipeline', 'HasError', 'PreviousHasError',
        'CreatedAt', 'UpdatedAt'
    ];
}
