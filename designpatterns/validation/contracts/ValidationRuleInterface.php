<?php

namespace App\DesignPatterns\Validation\Contracts;

interface ValidationRuleInterface
{
    public function validate(array $data): ?string;
}