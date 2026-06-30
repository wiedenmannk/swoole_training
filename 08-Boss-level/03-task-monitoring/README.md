# Boss Level 03 – Task Monitoring

## Ziel

In diesem Boss Level erweitern wir den Server um ein einfaches Monitoring für laufende Tasks.

Während im ersten Boss Level der Lebenszyklus eines einzelnen Tasks protokolliert wurde und im zweiten Boss Level der aktuelle Zustand des Servers sichtbar war, beobachten wir nun das Verhalten eines laufenden Systems unter Last.

Unser Ziel ist es, Antworten auf folgende Fragen zu erhalten:

* Welche Worker bearbeiten aktuell Requests?
* Welche Task Worker (Köche) arbeiten gerade?
* Welche Tasks warten?
* Welche Tasks werden gerade bearbeitet?
* Welche Tasks sind bereits abgeschlossen?
* Wie verändert sich der Zustand während neue Requests eintreffen?

---

## Restaurant-Modell

Wir verwenden weiterhin unser Restaurant-Beispiel.

```text
Gast
    │
    ▼
Kellner (Worker)
    │
    ▼
Bestellung (Task)
    │
    ▼
Koch (Task Worker)
```

Der Kellner nimmt die Bestellung entgegen.

Die Bestellung landet zunächst in der Warteschlange.

Ein freier Koch übernimmt anschließend die Bestellung.

Während der Bearbeitung verändert sich der Status der Bestellung.

---

## Status einer Bestellung

```text
waiting
    │
    ▼
running
    │
    ▼
finished
```

Diese Status werden in einer `Swoole\Table` gespeichert.

Jeder Statuswechsel überschreibt den bestehenden Eintrag.

Es existiert also **immer genau ein aktueller Zustand pro Task**.

---

## Projektstruktur

```text
03-task-monitoring/
├── server.php
└── src/
    ├── Dto/
    │   ├── StaffMember.php
    │   └── TaskMember.php
    ├── Handler/
    │   ├── StaffHandler.php
    │   ├── TaskHandler.php
    │   └── RouteHandler.php
    ├── Response/
    │   └── JsonResponse.php
    └── Table/
        ├── StaffTable.php
        └── TaskTable.php
```

---

## Server starten

```bash
php server.php
```

Standardmäßig läuft der Server auf

```text
http://127.0.0.1:9501
```

---

## Verfügbare Routen

### Übersicht

```bash
curl http://127.0.0.1:9501
```

---

### Serverstatus

```bash
curl http://127.0.0.1:9501/status | jq
```

Zeigt:

* aktuelle Worker-ID
* Speicherverbrauch
* Uhrzeit

---

### Worker anzeigen

```bash
curl http://127.0.0.1:9501/workers | jq
```

---

### Task Worker anzeigen

```bash
curl http://127.0.0.1:9501/cooks | jq
```

---

### Neue Bestellung erzeugen

```bash
curl "http://127.0.0.1:9501/order?meal=Pizza&duration=5" | jq
```

Parameter:

| Parameter | Bedeutung                    |
| --------- | ---------------------------- |
| meal      | Gericht                      |
| duration  | Bearbeitungszeit in Sekunden |

---

### Alle Tasks anzeigen

```bash
curl http://127.0.0.1:9501/tasks | jq
```

---

### Nur wartende Tasks

```bash
curl http://127.0.0.1:9501/tasks/waiting | jq
```

---

### Nur laufende Tasks

```bash
curl http://127.0.0.1:9501/tasks/running | jq
```

---

### Nur fertige Tasks

```bash
curl http://127.0.0.1:9501/tasks/finished | jq
```

---

## Last erzeugen

Mehrere Bestellungen hintereinander erzeugen:

```bash
curl "http://127.0.0.1:9501/order?meal=Pizza&duration=10"
curl "http://127.0.0.1:9501/order?meal=Pasta&duration=10"
curl "http://127.0.0.1:9501/order?meal=Burger&duration=10"
```

Bei zwei Task Workern ergibt sich typischerweise:

```text
running
running
waiting
```

Sobald ein Koch fertig ist, übernimmt er automatisch den wartenden Task.

---

## Beobachtung

Die Route `/tasks` zeigt **keine Historie**, sondern immer den **aktuellen Zustand** jedes Tasks.

Ein Task verändert seinen Status:

```text
waiting
↓
running
↓
finished
```

Dabei wird der vorhandene Eintrag in der `TaskTable` überschrieben.

---

## Lernziele

Nach Abschluss dieses Boss Levels soll nachvollziehbar sein:

* Warum Task Worker die Parallelität begrenzen.
* Warum Tasks in einer Warteschlange landen.
* Wie sich ein Task durch verschiedene Zustände bewegt.
* Wie sich der aktuelle Zustand eines Servers über eine Diagnose-API beobachten lässt.

---

## Ausblick

Im nächsten Schritt kann das Monitoring weiter ausgebaut werden, beispielsweise um:

* Wartezeit (`wait_ms`)
* Bearbeitungszeit (`duration_ms`)
* CPU-Auslastung
* RAM-Auslastung
* Linux-Prozessinformationen
* Queue-Länge
* offene Dateien
* weitere Diagnoseinformationen für Produktivsysteme
