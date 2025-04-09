<?php

namespace App;

class Response
{
    public static function makeSuccessResponse()
    {
        echo self::getJsonResponse();
    }

    private static function getJsonResponse(bool $success = true, string $errorMessage = '', string $content = ''): string
    {
        header('Content-Type: application/json');
        $response = [
            'success' => $success,
            'errorMessage' => $errorMessage,
            'content' => $content
        ];
        return json_encode($response);
    }

    public static function makeErrorResponse(string $errorMessage)
    {
        echo self::getJsonResponse(false, $errorMessage);
    }

    public static function makeContentResponse(string $content)
    {
        echo self::getJsonResponse(true, '', $content);

    }

    public static function makeJsonResponse(array $data)
    {
        echo self::getJsonResponse(true, '', json_encode($data));
    }
}