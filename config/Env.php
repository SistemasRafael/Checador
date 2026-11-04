<?php

class Env
{
    private static array $variables = [];

    public static function load(string $path): void
    {
        if (!file_exists($path)) {
            throw new Exception("Archivo .env no encontrado");
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {

            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);

            self::$variables[trim($key)] = trim($value);
        }
    }

    public static function get(string $key, $default = null)
    {
        return self::$variables[$key] ?? $default;
    }
}