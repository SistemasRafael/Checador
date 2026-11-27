<?php
namespace App\DesignPatterns\Validation;

use App\DesignPatterns\Validation\Contracts\ValidationRuleInterface;

class FormValidator
{
    private array $rules;

    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    public function validate(array $data): array
    {
        $errors = [];

        foreach ($this->rules as $rule) {

            if (!$rule instanceof ValidationRuleInterface) {
                continue;
            }

            $error = $rule->validate($data);

            if ($error !== null) {
                $errors[] = $error;
            }
        }

        return $errors;
    }
}