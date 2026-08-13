<?php

namespace App\Exceptions;

use Exception;

class GlValidationException extends Exception
{
    public function __construct(
        public readonly string $errorCode,
        public readonly array $details = [],
        string $message = 'Validation failed'
    ) {
        parent::__construct($message);
    }
}
