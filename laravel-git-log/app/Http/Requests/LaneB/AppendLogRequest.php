<?php

namespace App\Http\Requests\LaneB;

use App\Http\Requests\BaseLaneBFormRequest;

final class AppendLogRequest extends BaseLaneBFormRequest
{
    /** §15 code mapping — rule-key OR key.rule_name → GL-VALIDATION-* code. */
    protected array $errorCodes = [
        'RepoUrl'            => 'GL-VALIDATION-MISSING-FIELD',
        'RepoUrl.url'        => 'GL-VALIDATION-REPOURL-MALFORMED',
        'Branch'             => 'GL-VALIDATION-MISSING-FIELD',
        'HasError'           => 'GL-VALIDATION-MISSING-FIELD',
        'HasError.boolean'   => 'GL-VALIDATION-FIELD-TYPE',
        // ... more codes
    ];

    public function rules(): array
    {
        return [
            'RepoUrl'  => ['required', 'url', 'max:2048'],
            'Branch'   => ['required', 'string', 'max:255'],
            'HasError' => ['required', 'boolean'],
            'Logs'     => ['nullable', 'array'],
        ];
    }
}
