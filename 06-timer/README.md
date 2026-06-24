# Timer

## Lernziel

In diesem Kapitel lernen wir die Timer-Funktionen von Swoole kennen.

Timer ermöglichen Aktionen, die zeitgesteuert ausgeführt werden.

Im Gegensatz zu HTTP Requests benötigen Timer keinen Benutzer und keinen Browser.

```text
Server läuft
↓
Zeit vergeht
↓
Aktion wird automatisch ausgeführt
```

---

# Übersicht

```text
01-after
↓
Einmaliger Timer

02-tick
↓
Wiederholender Timer

03-tick-stop
↓
Timer beenden
```

---

# Warum Timer?

Bisher haben wir nur auf Requests reagiert.

```text
Request
↓
Code läuft
↓
Antwort
```

Mit Timern kann der Server selbstständig arbeiten.

Beispiele:

```text
Heartbeat senden
Status prüfen
Monitoring
Cache bereinigen
Logdateien prüfen
Regelmäßige Hintergrundaufgaben
```

---

# 01-after

## Ziel

Code einmalig nach einer bestimmten Zeit ausführen.

---

Beispiel

```php
Swoole\Timer::after(
    5000,
    function (): void {
        echo "5 Sekunden sind vorbei!" . PHP_EOL;
    }
);
```

---

Ablauf

```text
Server startet
↓
5 Sekunden warten
↓
Callback wird einmal ausgeführt
```

---

Ausgabe

```text
5 Sekunden sind vorbei!
```

---

Merksatz

```text
after()
=
einmaliger Timer
```

---

# 02-tick

## Ziel

Code regelmäßig ausführen.

---

Beispiel

```php
Swoole\Timer::tick(
    1000,
    function (): void {
        echo date("H:i:s") . PHP_EOL;
    }
);
```

---

Ablauf

```text
Server startet
↓
jede Sekunde
↓
Callback wird ausgeführt
```

---

Ausgabe

```text
17:31:43
17:31:44
17:31:45
17:31:46
```

---

Merksatz

```text
tick()
=
wiederholender Timer
```

---

# 03-tick-stop

## Ziel

Timer wieder beenden.

---

Beispiel

```php
Swoole\Timer::clear($timerId);
```

---

Ablauf

```text
Timer startet
↓
5 Ausführungen
↓
Timer wird beendet
```

---

Ausgabe

```text
Tick 1
Tick 2
Tick 3
Tick 4
Tick 5
Timer wird gestoppt
```

---

Danach erfolgt keine weitere Ausgabe.

---

# Timer ID

Beim Erstellen eines Timers liefert Swoole eine Timer-ID zurück.

Beispiel:

```php
$timerId = Swoole\Timer::tick(...);
```

Diese ID wird benötigt, um den Timer später wieder zu stoppen.

---

# Event Loop

Timer basieren auf dem Event Loop von Swoole.

```text
Server läuft
↓
Event Loop läuft
↓
Timer werden überwacht
↓
Callbacks werden ausgeführt
```

---

# Wichtig

Timer sollten bei einem HTTP Server innerhalb eines Server-Events registriert werden.

Beispiel:

```php
$server->on("Start", function (): void {

});
```

oder

```php
$server->on("WorkerStart", function (): void {

});
```

---

Nicht vor:

```php
$server->start();
```

Andernfalls kann folgende Meldung auftreten:

```text
The event-loop has already been created
```

---

# Cron Job Vergleich

Cron:

```text
Betriebssystem startet Script
```

---

Swoole Timer:

```text
Server läuft bereits
↓
Timer löst Aktion aus
```

---

Restaurant-Metapher

Cron:

```text
Chef ruft jeden Morgen an
und erinnert an die Inventur
```

---

Timer:

```text
Restaurant besitzt
einen eigenen Wecker
```

---

# Was haben wir gelernt?

- Einmalige Timer
- Wiederholende Timer
- Timer stoppen
- Timer IDs
- Event Loop Grundlagen
- Unterschied zu Cron Jobs

---

# Zusammenfassung

```text
after()
↓
einmal

tick()
↓
wiederholt

clear()
↓
stoppen
```

---

# Ausblick

Als Nächstes beschäftigen wir uns mit:

```text
WebSocket
Realtime Kommunikation
Dauerhafte Verbindungen
```

und verlassen damit das klassische Request-Response-Modell.