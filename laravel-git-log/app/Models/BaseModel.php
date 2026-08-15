<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

abstract class BaseModel extends Model
{
    /**
     * Disable Eloquent's default snake_case mapping.
     */
    public static $snakeAttributes = false;

    /**
     * Override standard created_at / updated_at column names.
     */
    public const CREATED_AT = 'CreatedAt';
    public const UPDATED_AT = 'UpdatedAt';

    /**
     * Set Unix timestamp format as dictated by the schema.
     */
    protected $dateFormat = 'U';

    /**
     * Automatically allow camelCase access to PascalCase columns.
     */
    public function getAttribute($key)
    {
        if (parent::getAttribute($key) !== null || array_key_exists($key, $this->attributes) || $this->hasGetMutator($key)) {
            return parent::getAttribute($key);
        }

        $pascalKey = ucfirst($key);
        return parent::getAttribute($pascalKey);
    }

    public function setAttribute($key, $value)
    {
        $pascalKey = ucfirst($key);
        return parent::setAttribute($pascalKey, $value);
    }

    /**
     * Turn off incrementing by default unless specified.
     */
    public $incrementing = true;
}
