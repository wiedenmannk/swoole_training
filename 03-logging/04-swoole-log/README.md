# Swoole Logging

## Ziel

In dieser Übung lernen wir den Unterschied zwischen **Application Logging** und **Server Logging** kennen.

Dabei verwenden wir zwei getrennte Logdateien:

```text
application.log
```

und

```text
swoole.log
```

---

# Warum zwei Logs?

Nicht alle Informationen gehören in dieselbe Logdatei.

Ein Server besteht aus mehreren Ebenen.

```text
Anwendung
↓

Swoole Server
↓

Linux Betriebssystem
```

Jede Ebene kann eigene Informationen protokollieren.

---

# Application Log

Das Application Log wird von unserer Anwendung geschrieben.

Beispiel:

```php
file_put_contents(
    $applicationLogFile,
    "...",
    FILE_APPEND
);
```

Hier entscheiden wir selbst, welche Informationen gespeichert werden.

Typische Inhalte:

```text
GET /hello

GET /api/users

POST /invoice

Benutzer Klaus angemeldet

Rechnung erstellt
```

Das Application Log beantwortet die Frage:

> **Was macht meine Anwendung?**

---

# Swoole Log

Das Swoole Log wird automatisch vom Server geschrieben.

Aktiviert wird es über:

```php
$server->set([
    "log_file" => $swooleLogFile
]);
```

Hier schreibt Swoole technische Informationen.

Typische Inhalte:

```text
Warnings

Fatal Errors

Worker Fehler

Socket Probleme

Stack Traces

Server Meldungen
```

Das Swoole Log beantwortet die Frage:

> **Was macht der Server?**

---

# Verzeichnisstruktur

```text
04-swoole-log/

├── server.php
├── README.md
└── logs
    ├── application.log
    └── swoole.log
```

---

# Beispiel

## Server starten

```bash
php server.php
```

---

## Application Log beobachten

```bash
tail -f logs/application.log
```

---

## Swoole Log beobachten

```bash
tail -f logs/swoole.log
```

---

# Test 1

Normale Requests

```bash
curl http://127.0.0.1:9501/test

curl http://127.0.0.1:9501/api

curl http://127.0.0.1:9501/debug
```

---

## application.log

```text
2026-06-25 11:55:10 GET /test

2026-06-25 11:55:14 GET /api

2026-06-25 11:55:20 GET /debug
```

---

## swoole.log

Normalerweise erfolgt keine neue Ausgabe.

Der Server arbeitet fehlerfrei.

---

# Test 2

Fehler provozieren

```bash
curl http://127.0.0.1:9501/error
```

Im Beispiel ruft die Route absichtlich eine nicht vorhandene Funktion auf.

```php
undefined_function();
```

---

## application.log

```text
2026-06-25 11:57:41 GET /error
```

Die Anfrage wurde erfolgreich protokolliert.

---

## swoole.log

Jetzt erscheinen technische Informationen.

Zum Beispiel:

```text
Fatal Error

undefined_function()

Stack Trace
```

Der Server dokumentiert den Fehler automatisch.

---

# Vergleich

## Application Log

```text
Wer?

Wann?

Welche URL?

Welche Aktion?

Welcher Benutzer?
```

Beispiele:

```text
Login

Bestellung

Rechnung

API Request
```

---

## Swoole Log

```text
Worker

Socket

Fatal Error

Warning

Exception

Server Status
```

---

# Warum nicht nur das Swoole Log?

Das Swoole Log kennt die fachliche Bedeutung einer Anwendung nicht.

Der Server weiß beispielsweise nicht:

- welcher Benutzer angemeldet wurde
- welche Rechnung erstellt wurde
- welcher Auftrag bearbeitet wurde

Diese Informationen kann nur die Anwendung selbst protokollieren.

---

# Warum nicht nur das Application Log?

Die Anwendung erkennt viele technische Probleme nicht.

Beispielsweise:

- Worker abgestürzt
- Socket Fehler
- Stack Trace
- Interne Serverfehler

Diese Informationen schreibt Swoole automatisch in das Server Log.

---

# Zusammenfassung

```text
Application Log

↓

Was macht meine Anwendung?


Swoole Log

↓

Was macht mein Server?
```

Beide Logdateien ergänzen sich und erfüllen unterschiedliche Aufgaben.