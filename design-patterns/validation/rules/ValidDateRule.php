<?php

require_once __DIR__ . '/../contracts/ValidationRuleInterface.php';

class ValidDateRule implements ValidationRuleInterface
{
    public function __construct(
        private string $field,
        private string $message
    ) {}

    public function validate(array $data): ?string
    {
        $value = $data[$this->field] ?? '';

        if (trim((string)$value) === '') {
            return $this->message;
        }

        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);

        if (!$date || $date->format('Y-m-d') !== $value) {
            return $this->message;
        }

        return null;
    }
}