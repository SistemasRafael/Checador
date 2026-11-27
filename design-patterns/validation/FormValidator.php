<?php

require_once __DIR__ . '/contracts/ValidationRuleInterface.php';

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