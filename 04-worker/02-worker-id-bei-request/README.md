# Worker ID bei Request

## Lernziel

- Verstehen, welcher Worker einen Request bearbeitet
- Die Eigenschaft `worker_id` kennenlernen
- Beobachten, wie Requests auf Worker verteilt werden
- Den Unterschied zwischen Browser und curl erkennen

## Start

Aus dem aktuellen Verzeichnis:

```bash
php server.php
```

Oder vom Projektverzeichnis aus:

```bash
php ./04-workers/02-worker-id-bei-request/server.php
```

## Beispiel

```php
<?php

$server = new Swoole\Http\Server("127.0.0.1", 9501);

$server->set([
    "worker_num" => 4
]);

$server->on("WorkerStart", function ($server, $workerId): void {
    echo "Worker {$workerId} gestartet\n";
});

$server->on("request", function ($request, $response) use ($server): void {

    $workerId = $server->worker_id;
    $uri = $request->server["request_uri"];

    $response->end(
        "Worker {$workerId} hat {$uri} bearbeitet\n"
    );
});

$server->start();
```

## Test

Browser:

```text
http://127.0.0.1:9501/debug
```

oder:

```text
http://127.0.0.1:9501/hello
```

## Test mit curl

```bash
curl http://127.0.0.1:9501/debug
```

Beispiel:

```text
Worker 0 hat /debug bearbeitet
```

Erneuter Aufruf:

```bash
curl http://127.0.0.1:9501/debug
```

Beispiel:

```text
Worker 2 hat /debug bearbeitet
```

## Mehrere Requests testen

```bash
for i in {1..10}
do
    curl http://127.0.0.1:9501/debug
done
```

Beispielausgabe:

```text
Worker 0 hat /debug bearbeitet
Worker 2 hat /debug bearbeitet
Worker 1 hat /debug bearbeitet
Worker 3 hat /debug bearbeitet
Worker 0 hat /debug bearbeitet
```

## Die Eigenschaft worker_id

```php
$workerId = $server->worker_id;
```

liefert die ID des Workers, der den aktuellen Request bearbeitet.

Beispiele:

```text
0
1
2
3
```

## Warum wechseln die Worker?

Swoole verteilt eingehende Requests auf die verfügbaren Worker.

Vereinfacht:

```text
Request 1 → Worker 0
Request 2 → Worker 2
Request 3 → Worker 1
Request 4 → Worker 3
```

Dadurch können mehrere Requests parallel verarbeitet werden.

## Browser vs. curl

Während der Übung fällt häufig auf:

### Browser

Mehrere Aufrufe landen oft beim gleichen Worker.

Beispiel:

```text
Worker 2
Worker 2
Worker 2
```

### curl

Mehrere Aufrufe landen häufig bei unterschiedlichen Workern.

Beispiel:

```text
Worker 0
Worker 2
Worker 1
```

## Warum?

Browser verwenden häufig dieselbe Verbindung weiter.

Vereinfacht:

```text
Browser
↓
Verbindung öffnen
↓
Mehrere Requests
↓
Verbindung bleibt bestehen
```

Dieses Verhalten wird als:

```text
Keep-Alive
```

bezeichnet.

### curl

Jeder Aufruf startet normalerweise einen neuen Prozess:

```text
curl starten
↓
Verbindung aufbauen
↓
Request senden
↓
Verbindung schließen
↓
curl beenden
```

Dadurch sieht Swoole häufiger neue Verbindungen und verteilt die Requests auf unterschiedliche Worker.

## Was haben wir gelernt?

- Jeder Request wird von einem Worker bearbeitet
- Die aktuelle Worker-ID kann über `$server->worker_id` gelesen werden
- Swoole verteilt Requests auf mehrere Worker
- Browser und curl können sich unterschiedlich verhalten
- Mehrere Worker ermöglichen parallele Verarbeitung

## Wichtiges Konzept

```text
Master
├── Worker 0
├── Worker 1
├── Worker 2
└── Worker 3

Request
↓
einer dieser Worker bearbeitet ihn
```

## Ausblick

Im nächsten Schritt beobachten wir mehrere Requests gleichzeitig.

Dabei werden wir sehen:

```text
Wie verteilt Swoole Last auf mehrere Worker?
```

Dies bildet die Grundlage für spätere Themen wie:

- Store und State
- Shared Memory
- File Locking
- WebSockets

## Beenden

Im Terminal:

```bash
CTRL + C
```