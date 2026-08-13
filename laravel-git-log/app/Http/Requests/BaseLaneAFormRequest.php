<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\GlValidationException;

abstract class BaseLaneAFormRequest extends FormRequest
{
    protected array $errorCodes = [];

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Expand the §04 ?q= URL-style shorthand into explicit RepoUrl + Branch.
     * MUST run BEFORE rules() validation. Idempotent: explicit fields win.
     */
    protected function prepareForValidation(): void
    {
        $q = $this->query('q');
        if ($q === null || $q === '') return;

        if (!preg_match('#^(?<host>[^/]+)/(?<org>[^/]+)/(?<repo>[^/@]+)(?:@(?<branch>.+))?$#', $q, $m)) {
            throw new GlValidationException('GL-VALIDATION-REPOURL-MALFORMED', [
                'q' => $q,
                'reason' => 'shorthand_parse_failed',
            ]);
        }

        $merge = [];
        if (!$this->has('RepoUrl')) {
            $merge['RepoUrl'] = "https://{$m['host']}/{$m['org']}/{$m['repo']}";
        }
        if (!$this->has('Branch') && !empty($m['branch'])) {
            $merge['Branch'] = $m['branch'];
        }
        if ($merge !== []) $this->merge($merge);
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
