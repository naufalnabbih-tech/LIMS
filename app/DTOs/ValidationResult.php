<?php

namespace App\DTOs;

class ValidationResult
{
    private function __construct(
        public readonly bool $isValid,
        public readonly ?string $errorMessage,
        public readonly int $passedSpecs,
        public readonly int $failedSpecs
    ) {
    }

    public static function success(int $passedSpecs, int $failedSpecs): self
    {
        return new self(true, null, $passedSpecs, $failedSpecs);
    }

    public static function failed(string $message): self
    {
        return new self(false, $message, 0, 0);
    }
}
