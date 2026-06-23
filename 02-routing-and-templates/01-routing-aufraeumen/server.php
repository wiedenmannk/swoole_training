<?php

$server = new Swoole\Http\Server("127.0.0.1", 9501);

$server->on("request", function ($request, $response): void {

    $uri = $request->server['request_uri'];

    switch ($uri) {

        case '/':

            $response->end("
                <html>
                    <body>
                        <h1>Home</h1>
                    </body>
                </html>
            ");

            break;

        case '/hello':

            $response->end("
                <html>
                    <body>
                        <h1>Hallo Swoole</h1>
                    </body>
                </html>
            ");

            break;

        default:

            $response->status(404);

            $response->end("
                <html>
                    <body>
                        <h1>404 - Seite nicht gefunden</h1>
                    </body>
                </html>
            ");
    }
});

$server->start();