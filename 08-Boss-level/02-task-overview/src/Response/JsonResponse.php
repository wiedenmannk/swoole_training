<?php

declare(strict_types=1);

namespace App\Response;

use Swoole\Http\Response;

final class JsonResponse
{
    public static function send(
        Response $response,
        array $data
    ): void {
        $response->header("Content-Type", "application/json");
        $response->end(json_encode(
            $data,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        ));
    }
}