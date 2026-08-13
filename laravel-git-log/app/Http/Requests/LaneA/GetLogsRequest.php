<?php

namespace App\Http\Requests\LaneA;

use App\Http\Requests\BaseLaneAFormRequest;

final class GetLogsRequest extends BaseLaneAFormRequest
{
    protected array $errorCodes = [
        'RepoUrl'            => 'GL-VALIDATION-MISSING-FIELD',
        'RepoUrl.url'        => 'GL-VALIDATION-REPOURL-MALFORMED',
        'Branch'             => 'GL-VALIDATION-MISSING-FIELD',
    ];

    public function rules(): array
    {
        return [
            'RepoUrl' => ['required', 'url', 'max:2048'],
            'Branch'  => ['required', 'string', 'max:255'],
            'Limit'   => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
