# Task Worker Restaurant

## Lernziel

- Den Unterschied zwischen HTTP Workern und Task Workern verstehen
- Hintergrundarbeiten mit Task Workern ausführen
- Parallele Verarbeitung beobachten
- Kapazitätsgrenzen erkennen

## Restaurant-Metapher

In dieser Übung verwenden wir ein Restaurant als Modell.

```text
Kunde
↓
Kellner
↓
Küche
↓
Essen fertig
```

Übertragen auf Swoole:

```text
HTTP Worker
↓
nimmt Bestellung an

Task Worker
↓
bereitet Essen zu
```

## Rollen

### HTTP Worker

Der HTTP Worker nimmt Requests entgegen.

In unserem Beispiel:

```text
Kellner
```

Der Kellner nimmt die Bestellung entgegen und gibt sie an die Küche weiter.

Er kocht nicht selbst.

### Task Worker

Der Task Worker erledigt Hintergrundarbeiten.

In unserem Beispiel:

```text
Koch
```

Der Koch bereitet die Bestellung zu.

## Konfiguration

```php
$server->set([
    "worker_num" => 2,
    "task_worker_num" => 3
]);
```

### worker_num

Anzahl der HTTP Worker.

```text
Kellner
```

### task_worker_num

Anzahl der Task Worker.

```text
Köche
```

## Ablauf

Request:

```text
/order=Pizza
```

HTTP Worker:

```text
Bestellung angenommen
```

Task Worker:

```text
Pizza wird gekocht
```

Nach einigen Sekunden:

```text
Pizza ist fertig
```

## Beispielausgabe

```text
Kellner Worker 0:
Bestellung 'Pizza' angenommen
```

Server:

```text
Küche: Bestellung 'Pizza' wird gekocht...
```

Später:

```text
Küche: Bestellung 'Pizza' ist fertig
```

Danach:

```text
Service: Task 0 beendet. Essen 'Pizza' ist fertig.
```

## Parallele Verarbeitung

Mit:

```php
"task_worker_num" => 3
```

und den Bestellungen:

```text
Pizza
Pasta
Burger
```

kann die Ausgabe so aussehen:

```text
Küche: Bestellung 'Pizza' wird gekocht...
Küche: Bestellung 'Pasta' wird gekocht...
Küche: Bestellung 'Burger' wird gekocht...
```

Alle Bestellungen werden gleichzeitig bearbeitet.

## Ein Koch

Mit:

```php
"task_worker_num" => 1
```

gibt es nur einen Koch.

Dann erfolgt die Bearbeitung nacheinander:

```text
Pizza
↓
Pasta
↓
Burger
```

## Warning

Bei nur einem Task Worker kann folgende Meldung erscheinen:

```text
No idle task worker is available
```

Bedeutung:

```text
Aktuell ist kein freier Task Worker verfügbar.
```

Wichtig:

```text
Das bedeutet nicht automatisch,
dass ein Task verloren geht.
```

In unserem Beispiel wurden die Bestellungen trotzdem nacheinander abgearbeitet.

Die Meldung zeigt lediglich einen Engpass an.

## Warum Task Worker?

Task Worker eignen sich für langsame Arbeiten:

- PDF-Erzeugung
- E-Mail Versand
- Dateiimporte
- Exporte
- Bildverarbeitung
- Hintergrundjobs

## Vergleich

Ohne Task Worker:

```text
Request
↓
lange Verarbeitung
↓
Antwort
```

Mit Task Worker:

```text
Request
↓
Task starten
↓
Antwort sofort

Task Worker
↓
arbeitet im Hintergrund
```

## Was haben wir gelernt?

- HTTP Worker und Task Worker haben unterschiedliche Aufgaben
- Task Worker führen Hintergrundarbeiten aus
- Mehr Task Worker ermöglichen mehr Parallelität
- Kapazitätsengpässe werden sichtbar
- Task Worker eignen sich für zeitaufwändige Arbeiten

## Ausblick

Mögliche Erweiterungen:

- Warteschlangen
- Statusanzeigen
- Shared Tables
- Task Monitoring
- "Server is busy"-Mechanismen
- Task Board