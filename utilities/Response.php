<?php

class Response
{
    public static function success(string $message, array $data = []): array
    {
        return [
            "success" => true,
            "message" => $message,
            "data" => $data
        ];
    }

    public static function error(string $message): array
    {
        return [
            "success" => false,
            "message" => $message
        ];
    }
}