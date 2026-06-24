# Worker State Count

## Lernziel

- Verstehen, dass jeder Worker seinen eigenen Speicher besitzt
- Beobachten, dass Variablen zwischen Requests erhalten bleiben
- Den Unterschied zwischen einem globalen und einem Worker-lokalen Zustand verstehen
- Die Grundlage für Store und State kennenlernen

## Start

Aus dem aktuellen Verzeichnis:

```bash
php server.php
```

Oder vom Projektverzeichnis aus:

```bash
php ./04-workers/05-worker-state-count/server.php
```

## Beispiel

```php
<?php

$server = new Swoole\Http\Server("127.0.0.1", 9501);

$server->set([
    "worker_num" => 4
]);

$count = 0;

$server->on("request", function ($request, $response) use ($server, &$count): void {

    $workerId = $server->worker_id;

    $count++;

    $response->end(
        "Worker {$workerId} Count: {$count}\n"
    );
});

$server->start();
```

## Test

Einzelne Requests:

```bash
curl http://127.0.0.1:9501
```

Mehrere Requests:

```bash
for i in {1..10}
do
    curl http://127.0.0.1:9501
done
```

## Beispielausgabe

```text
Worker 0 Count: 1
Worker 2 Count: 1
Worker 1 Count: 1
Worker 0 Count: 2
Worker 3 Count: 1
Worker 2 Count: 2
```

## Erste Beobachtung

Der Zähler wird nicht nach jedem Request zurückgesetzt.

Beispiel:

```text
Worker 0 Count: 1
Worker 0 Count: 2
Worker 0 Count: 3
```

Die Variable bleibt erhalten.

## Warum?

Der Worker-Prozess läuft dauerhaft.

Die Variable wird nur einmal beim Start erzeugt:

```php
$count = 0;
```

Danach bleibt sie im Speicher des Workers erhalten.

## Zweite Beobachtung

Es gibt keinen gemeinsamen Zähler.

Beispiel:

```text
Worker 0 Count: 2
Worker 1 Count: 1
Worker 2 Count: 3
Worker 3 Count: 1
```

Jeder Worker zählt unabhängig.

## Warum?

Jeder Worker ist ein eigener Prozess.

Vereinfacht:

```text
Worker 0
└── count = 2

Worker 1
└── count = 1

Worker 2
└── count = 3

Worker 3
└── count = 1
```

Jeder Prozess besitzt seinen eigenen Speicherbereich.

## Vergleich zu klassischem PHP

Klassisches PHP:

```text
Request
↓
count = 0
↓
count = 1
↓
Script endet
```

Nächster Request:

```text
count = 0
↓
count = 1
```

Ausgabe:

```text
1
1
1
```

## Vergleich zu Swoole

Swoole:

```text
Worker startet
↓
count = 0
↓
Request
↓
count = 1
↓
Request
↓
count = 2
↓
Request
↓
count = 3
```

Ausgabe:

```text
1
2
3
```

## Wichtiges Konzept

```text
Worker
↓
lebt dauerhaft
↓
behält Variablen im Speicher
↓
State möglich
```

## Was haben wir gelernt?

- Worker sind langlebige Prozesse
- Variablen bleiben zwischen Requests erhalten
- Jeder Worker besitzt seinen eigenen Speicher
- Es gibt keinen automatisch gemeinsamen Zustand
- State ist in Swoole möglich

## Warum ist das wichtig?

Diese Erkenntnis bildet die Grundlage für:

- Sessions
- Caches
- In-Memory-Stores
- Redis
- Swoole\Table
- Shared Memory

## Ausblick

Im nächsten Schritt werden wir untersuchen:

```text
Wie können mehrere Worker Daten gemeinsam nutzen?
```

Damit verlassen wir den lokalen Worker-Speicher und betreten die Welt von:

```text
Shared State
```

## Beenden

```bash
CTRL + C
```