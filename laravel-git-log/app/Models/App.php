<?php

namespace App\Models;

class App extends BaseModel
{
    protected $table = 'App';
    protected $primaryKey = 'AppId';

    protected $fillable = ['AppName', 'AppSlug', 'Description', 'ProfileId', 'AppStatusId'];
}
