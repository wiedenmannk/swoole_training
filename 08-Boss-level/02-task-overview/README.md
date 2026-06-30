# Boss Level 02 – Task Overview

## Ziel

In dieser Übung bauen wir eine kleine Diagnose-API für unseren Swoole-Server.

Während `01-task-tracing` den Lebenszyklus eines einzelnen Tasks über Logs sichtbar macht, zeigt `02-task-overview` den aktuellen Zustand des Servers.

Wir wollen per `curl` abfragen können:

* Läuft der Server?
* Welche Worker sind gestartet?
* Welche Task Worker sind gestartet?
* Welche PID haben die Prozesse?
* Wie viel Speicher verbraucht der aktuelle PHP-Prozess?

---

## Server starten

Im Verzeichnis der Übung:

```bash
php server.php
```

Der Server läuft danach unter:

```text
http://127.0.0.1:9501
```

---

## Routen testen

### Startseite

```bash
curl http://127.0.0.1:9501
```

Zeigt eine kurze Übersicht der verfügbaren Routen.

---

### Serverstatus

```bash
curl http://127.0.0.1:9501/status | jq
```

Beispielausgabe:

```json
{
  "status": "ok",
  "data": {
    "server": "running",
    "worker_id": 0,
    "time": "2026-06-30 18:30:00",
    "memory": {
      "usage_mb": 2,
      "peak_mb": 2
    }
  }
}
```

---

### Request Worker anzeigen

```bash
curl http://127.0.0.1:9501/workers | jq
```

Zeigt die Kellner des Restaurants.

---

### Task Worker anzeigen

```bash
curl http://127.0.0.1:9501/cooks | jq
```

Zeigt die Köche des Restaurants.

---

## Architektur

Die Übung ist bewusst in kleine Bausteine zerlegt.

```text
server.php
src/
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

## Verantwortlichkeiten

### `server.php`

Startet den Swoole-Server, registriert Events und übergibt Requests an den `RouteHandler`.

### `StaffMember`

Beschreibt einen Mitarbeiter im Restaurant.

Ein Mitarbeiter kann ein Worker oder ein Task Worker sein.

### `StaffHandler`

Erzeugt aus einem Swoole-Worker ein `StaffMember`-Objekt.

### `StaffTable`

Verwaltet die `Swoole\Table` und speichert die Mitarbeiterinformationen.

### `RouteHandler`

Verarbeitet die Routen `/status`, `/workers` und `/cooks`.

### `JsonResponse`

Gibt Daten als JSON aus.

---

## Merksatz

```text
Teil 1: Was ist mit einem Task passiert?
Teil 2: Was passiert gerade im Server?
```
