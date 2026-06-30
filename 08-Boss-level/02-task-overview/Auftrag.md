# Arbeitsauftrag – Boss Level 02: Task Overview

## Ausgangslage

Im vorherigen Boss-Level wurde ein einzelner Task über Logs verfolgt.

Jetzt soll eine kleine Diagnose-API entstehen, mit der der aktuelle Zustand des Swoole-Servers per `curl` abgefragt werden kann.

Das Restaurant-Modell bleibt bestehen:

```text
Worker      = Kellner
Task Worker = Koch
Swoole Table = gemeinsames Notizbrett
```

---

## Ziel der Übung

Baue eine strukturierte Swoole-Anwendung, die Informationen über Worker und Task Worker in einer `Swoole\Table` speichert und über HTTP-Routen als JSON ausgibt.

---

## Verzeichnisstruktur

Erstelle folgende Struktur:

```text
02-task-overview/
├── server.php
└── src/
    ├── Dto/
    │   └── StaffMember.php
    ├── Handler/
    │   ├── StaffHandler.php
    │   └── RouteHandler.php
    ├── Response/
    │   └── JsonResponse.php
    └── Table/
        └── StaffTable.php
```

---

## Zu erfassende Daten

Für jeden Worker oder Task Worker sollen folgende Daten gespeichert werden:

| Feld         | Bedeutung                                             |
| ------------ | ----------------------------------------------------- |
| `key`        | eindeutiger Schlüssel, z. B. `worker-0` oder `cook-2` |
| `type`       | `waiter` oder `cook`                                  |
| `pid`        | Prozess-ID                                            |
| `status`     | aktueller Status, zunächst `idle`                     |
| `started_at` | Startzeit des Prozesses                               |

---

## Klassen

### 1. `StaffMember`

Aufgabe:

* beschreibt einen Mitarbeiter
* enthält die Daten `key`, `type`, `pid`, `status`, `startedAt`
* kann sich für die `Swoole\Table` in ein Array umwandeln
* kann sich für die JSON-Ausgabe in ein Array umwandeln

Benötigte Methoden:

```php
toTableRow(): array
toResponseArray(): array
```

---

### 2. `StaffHandler`

Aufgabe:

* erzeugt aus einem Swoole-Worker ein `StaffMember`-Objekt

Benötigte Methode:

```php
createFromWorker(int $workerId, bool $isTaskWorker): StaffMember
```

Regel:

```text
Wenn $isTaskWorker true ist:
    type = cook
    key = cook-{workerId}

Wenn $isTaskWorker false ist:
    type = waiter
    key = worker-{workerId}
```

---

### 3. `StaffTable`

Aufgabe:

* erstellt die `Swoole\Table`
* speichert `StaffMember`
* liest gespeicherte Mitarbeiter wieder aus

Benötigte Methoden:

```php
add(StaffMember $staffMember): void
findAll(): array
findByType(?string $type): array
```

Die Methode `findByType("waiter")` soll nur Worker zurückgeben.

Die Methode `findByType("cook")` soll nur Task Worker zurückgeben.

---

### 4. `JsonResponse`

Aufgabe:

* setzt den HTTP-Header `Content-Type: application/json`
* gibt ein Array als formatiertes JSON zurück

Benötigte Methode:

```php
send(Response $response, array $data): void
```

---

### 5. `RouteHandler`

Aufgabe:

* verarbeitet eingehende Requests
* entscheidet anhand der Route, welche Daten ausgegeben werden

Benötigte Routen:

| Route      | Ausgabe                                  |
| ---------- | ---------------------------------------- |
| `/`        | kurze Übersicht                          |
| `/status`  | Serverstatus, Worker-ID, Uhrzeit, Memory |
| `/workers` | alle Request Worker                      |
| `/cooks`   | alle Task Worker                         |

---

## Server-Konfiguration

Der Server soll mit folgender Konfiguration starten:

```php
$server->set([
    "worker_num" => 2,
    "task_worker_num" => 1,
]);
```

---

## Erwartetes Verhalten

Beim Start des Servers sollen die Worker und Task Worker in der `StaffTable` registriert werden.

Beispielausgabe im Terminal:

```text
waiter 0 gestartet. PID=12345
waiter 1 gestartet. PID=12346
cook 2 gestartet. PID=12347
```

---

## Testbefehle

Server starten:

```bash
php server.php
```

Status prüfen:

```bash
curl http://127.0.0.1:9501/status | jq
```

Worker anzeigen:

```bash
curl http://127.0.0.1:9501/workers | jq
```

Köche anzeigen:

```bash
curl http://127.0.0.1:9501/cooks | jq
```

---

## Denkfragen

1. Warum speichern wir Worker-Daten in einer `Swoole\Table`?
2. Warum ist `StaffMember` ein eigenes Objekt?
3. Warum gibt `StaffTable` `StaffMember`-Objekte zurück und nicht direkt JSON?
4. Warum kennt `server.php` möglichst wenig Geschäftslogik?
5. Was ist der Unterschied zwischen `require_once`, `namespace` und `use`?

---

## Zielbild

Am Ende soll klar erkennbar sein:

```text
server.php
    startet den Server

StaffHandler
    erzeugt Mitarbeiter

StaffTable
    speichert Mitarbeiter

RouteHandler
    verarbeitet Routen

JsonResponse
    gibt JSON aus

StaffMember
    beschreibt die Datenstruktur
```

Merksatz:

```text
Wir bauen kein schönes Demo-Projekt.
Wir bauen eine kleine Diagnose-API für ein laufendes Swoole-System.
```
