<?php

interface ValidationRuleInterface
{
    public function validate(array $data): ?string;
}