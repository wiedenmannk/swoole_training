<?php

// HTTP-Server auf localhost:9501 starten
$server = new Swoole\Http\Server("127.0.0.1", 9501);

// Diese Funktion wird bei jedem HTTP-Request aufgerufen
$server->on("request", function ($request, $response): void {

    // Im Request-Objekt liefert Swoole Informationen
    // über die Anfrage des Browsers.
    //
    // request_uri enthält den Teil der URL hinter dem Hostnamen:
    //
    // http://localhost:9501/test
    //                        ^^^^^
    //
    // Ergebnis: /test
    $uri = $request->server['request_uri'];
    print_r($request->server);
    echo "get Server context\n";
    var_dump($request->server);

    // Antwort an den Browser senden
    $response->end("URI: {$uri}\n");
});

// Server starten
$server->start();