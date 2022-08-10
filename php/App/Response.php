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
}