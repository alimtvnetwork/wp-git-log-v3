<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\GlValidationException;

abstract class BaseLaneBFormRequest extends FormRequest
{
    protected array $errorCodes = [];

    public function authorize(): bool
    {
        return true; // Authorization is handled in middleware
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->toArray();
        $firstField = array_key_first($errors);
        $failedRules = $validator->failed()[$firstField] ?? [];
        $firstRule = array_key_first($failedRules);

        $errorCode = 'GL-INTERNAL-MAPPING-MISSING';
        
        if (isset($this->errorCodes["{$firstField}.{$firstRule}"])) {
            $errorCode = $this->errorCodes["{$firstField}.{$firstRule}"];
        } elseif (isset($this->errorCodes[$firstField])) {
            $errorCode = $this->errorCodes[$firstField];
        }

        throw new GlValidationException($errorCode, $errors);
    }
}
