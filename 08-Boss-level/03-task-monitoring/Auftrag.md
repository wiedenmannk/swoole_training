# Arbeitsauftrag – Boss Level 03: Task Monitoring

## Ausgangslage

Im vorherigen Boss Level wurde der aktuelle Zustand des Servers sichtbar gemacht.

Wir konnten bereits beobachten:

* welche Worker laufen,
* welche Task Worker gestartet wurden,
* wie viel Speicher der Server verbraucht.

Nun soll das Monitoring erweitert werden.

Nicht mehr nur der Server, sondern auch die **laufenden Tasks** sollen beobachtet werden können.

---

# Ziel

Entwickeln Sie eine kleine Diagnose-API für ein laufendes Swoole-System.

Die API soll jederzeit beantworten können:

* Welche Bestellungen warten?
* Welche Bestellungen werden aktuell bearbeitet?
* Welche Bestellungen sind bereits fertig?
* Welcher Koch bearbeitet welche Bestellung?
* Welche Worker haben die Bestellung angenommen?

---

# Restaurantmodell

Verwenden Sie weiterhin das Restaurantmodell.

```text
Gast

↓

Worker (Kellner)

↓

Task (Bestellung)

↓

Task Worker (Koch)
```

---

# Architektur

Die bestehende Architektur aus Boss Level 02 soll erweitert werden.

```text
server.php

↓

RouteHandler

↓

TaskHandler

↓

TaskTable

↓

TaskMember
```

Die vorhandenen Klassen aus Boss Level 02 bleiben bestehen.

Neue Funktionalität wird ausschließlich ergänzt.

---

# Neue Klassen

Erstellen Sie folgende Klassen.

```text
TaskMember
TaskHandler
TaskTable
```

---

# TaskMember

Beschreibt eine Bestellung.

Folgende Informationen sollen gespeichert werden.

| Feld          | Beschreibung                              |
| ------------- | ----------------------------------------- |
| traceId       | eindeutige Task-ID                        |
| meal          | Gericht                                   |
| duration      | Bearbeitungszeit                          |
| status        | waiting, running oder finished            |
| requestWorker | Worker, der die Bestellung angenommen hat |
| taskWorker    | Koch, der die Bestellung bearbeitet       |
| createdAt     | Zeitpunkt der Annahme                     |
| startedAt     | Beginn der Bearbeitung                    |
| finishedAt    | Ende der Bearbeitung                      |
| waitMs        | Wartezeit                                 |
| durationMs    | Bearbeitungszeit                          |

Die Klasse soll Daten sowohl für die `Swoole\Table` als auch für die JSON-Ausgabe bereitstellen.

---

# TaskTable

Verwaltet alle Bestellungen.

Folgende Methoden werden benötigt.

```php
add()

update()

find()

findAll()

findByStatus()
```

Die Daten werden in einer `Swoole\Table` gespeichert.

---

# TaskHandler

Der Handler erzeugt und verändert Tasks.

Folgende Zustände sollen unterstützt werden.

```text
waiting

↓

running

↓

finished
```

Bei jedem Statuswechsel wird ein neuer `TaskMember` erzeugt und der bestehende Eintrag in der `TaskTable` aktualisiert.

---

# RouteHandler erweitern

Folgende neue Routen sollen entstehen.

| Route             | Beschreibung             |
| ----------------- | ------------------------ |
| `/order`          | neue Bestellung erzeugen |
| `/tasks`          | alle Tasks anzeigen      |
| `/tasks/waiting`  | wartende Tasks           |
| `/tasks/running`  | laufende Tasks           |
| `/tasks/finished` | fertige Tasks            |

Die Antworten sollen als formatiertes JSON zurückgegeben werden.

---

# server.php erweitern

Der Server soll nun zusätzlich:

* eine `TaskTable` erzeugen,
* einen `TaskHandler` verwenden,
* `onTask` registrieren,
* `onFinish` registrieren.

---

# Konfiguration

Verwenden Sie mindestens

```php
worker_num = 3

task_worker_num = 2
```

Dadurch entstehen mehrere parallel arbeitende Köche.

---

# Tests

## Server starten

```bash
php server.php
```

---

## Bestellung erzeugen

```bash
curl "http://127.0.0.1:9501/order?meal=Pizza&duration=10"
```

---

## Mehrere Bestellungen erzeugen

```bash
curl "http://127.0.0.1:9501/order?meal=Pizza&duration=10"

curl "http://127.0.0.1:9501/order?meal=Pasta&duration=10"

curl "http://127.0.0.1:9501/order?meal=Burger&duration=10"
```

---

## Status beobachten

```bash
curl http://127.0.0.1:9501/tasks | jq
```

Während der Bearbeitung sollten mehrere Zustände sichtbar werden.

Beispiel:

```text
running

running

waiting
```

Nach Abschluss:

```text
finished

finished

finished
```

---

# Erwartetes Ergebnis

Die Anwendung soll den aktuellen Zustand aller Tasks anzeigen können.

Ein Task verändert während seiner Lebensdauer seinen Status.

```text
waiting

↓

running

↓

finished
```

Der Status wird **nicht** als Historie gespeichert.

Stattdessen wird der bestehende Eintrag in der `TaskTable` überschrieben.

Die API zeigt dadurch jederzeit den **aktuellen Zustand des Systems**.

---

# Lernziele

Nach Abschluss dieser Übung sollen Sie erklären können:

* Warum Task Worker die Parallelität begrenzen.
* Warum wartende Tasks entstehen.
* Wie Worker und Task Worker zusammenarbeiten.
* Wie sich Zustände eines Tasks verändern.
* Wie eine Diagnose-API beim Debuggen produktiver Systeme helfen kann.

---

# Bonusaufgaben

Erweitern Sie das Monitoring um zusätzliche Informationen.

Beispiele:

* tatsächliche Wartezeit (`wait_ms`)
* tatsächliche Bearbeitungszeit (`duration_ms`)
* Anzahl aktiver Tasks
* Anzahl wartender Tasks
* Anzahl fertiger Tasks
* aktueller Status eines Kochs (`idle` oder `busy`)
* aktuell bearbeitetes Gericht eines Kochs

Überlegen Sie außerdem, welche Linux- oder Swoole-Informationen sich zusätzlich eignen würden, um ein laufendes Produktivsystem zu analysieren.
