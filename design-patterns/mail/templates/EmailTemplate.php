<?php

abstract class EmailTemplate
{
    protected array $data;

    protected string $viewsPath;

    protected string $cssPath;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    abstract public function subject(): string;

    abstract public function render(): string;
}