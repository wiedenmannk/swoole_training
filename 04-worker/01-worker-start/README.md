# Worker Start

## Lernziel

- Verstehen, was ein Worker ist
- Das Event `WorkerStart` kennenlernen
- Beobachten, wie viele Worker gestartet werden
- Die Einstellung `worker_num` verstehen

## Start

Aus dem aktuellen Verzeichnis:

```bash
php server.php
```

Oder vom Projektverzeichnis aus:

```bash
php ./04-workers/01-worker-start/server.php
```

## Beispiel

```php
$server = new Swoole\Http\Server("127.0.0.1", 9501);

$server->set([
    "worker_num" => 4
]);

$server->on("WorkerStart", function ($server, $workerId): void {

    echo "Worker {$workerId} gestartet\n";

});

$server->on("request", function ($request, $response): void {

    $response->end("Hallo Worker\n");

});

$server->start();
```

## Erwartete Ausgabe

```text
Worker 0 gestartet
Worker 1 gestartet
Worker 2 gestartet
Worker 3 gestartet
```

## Was ist ein Worker?

Ein Worker ist ein eigener Prozess.

Worker bearbeiten eingehende Requests.

Vereinfacht:

```text
Browser
↓
Request
↓
Worker
↓
Response
```

## Master und Worker

Beim Start erzeugt Swoole mehrere Prozesse:

```text
Master Prozess
├── Worker 0
├── Worker 1
├── Worker 2
└── Worker 3
```

Der Master verwaltet die Worker.

Die Worker bearbeiten die eigentliche Arbeit.

## Die Einstellung worker_num

```php
$server->set([
    "worker_num" => 4
]);
```

bestimmt die Anzahl der Worker-Prozesse.

### Experiment

Kommentiere die Einstellung aus:

```php
// $server->set([
//     "worker_num" => 4
// ]);
```

Starte den Server erneut.

Beobachte die Ausgabe.

In vielen Umgebungen wird nun nur ein Worker gestartet.

## Das Event WorkerStart

```php
$server->on("WorkerStart", function ($server, $workerId): void {

});
```

Dieses Event wird aufgerufen, wenn ein Worker gestartet wird.

## Woher kommen die Parameter?

Die Parameter werden von Swoole übergeben.

```php
function ($server, $workerId)
```

Der Callback wird nicht von uns aufgerufen.

Swoole ruft ihn automatisch auf.

## Vereinfacht dargestellt

Intern passiert ungefähr Folgendes:

```php
callback($server, 0);
callback($server, 1);
callback($server, 2);
callback($server, 3);
```

Dadurch entstehen die Ausgaben:

```text
Worker 0 gestartet
Worker 1 gestartet
Worker 2 gestartet
Worker 3 gestartet
```

## Vergleich mit Express.js

Express:

```javascript
app.get("/", function(req, res) {

});
```

Express übergibt:

```javascript
req
res
```

Swoole:

```php
$server->on("request", function ($request, $response): void {

});
```

Swoole übergibt:

```php
$request
$response
```

Das Konzept ist identisch.

## Wichtiges Konzept

```text
Event
↓
Callback registrieren
↓
Ereignis tritt ein
↓
Swoole ruft Callback auf
↓
Parameter werden übergeben
```

## Was haben wir gelernt?

- Worker sind eigene Prozesse
- Swoole startet mehrere Worker
- Die Anzahl wird über `worker_num` gesteuert
- Das Event `WorkerStart` wird beim Start eines Workers ausgelöst
- Swoole übergibt Parameter automatisch an Callbacks
- Callbacks sind ein zentrales Konzept in Swoole

## Ausblick

Im nächsten Schritt werden wir herausfinden:

```text
Welcher Worker bearbeitet meinen Request?
```

Dazu protokollieren wir die Worker-ID bei eingehenden Requests.

## Beenden

Im Terminal:

```bash
CTRL + C
```