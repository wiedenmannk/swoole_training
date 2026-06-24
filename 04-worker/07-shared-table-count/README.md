# Shared Table Count

## Lernziel

- Gemeinsamen Speicher zwischen mehreren Workern verstehen
- Die Klasse `Swoole\Table` kennenlernen
- Den Unterschied zwischen Worker-Speicher und Shared Memory verstehen
- Einen globalen Besucherzähler implementieren

## Start

Aus dem aktuellen Verzeichnis:

```bash
php server.php
```

Oder vom Projektverzeichnis aus:

```bash
php ./04-workers/07-shared-table-count/server.php
```

## Das Problem

In den vorherigen Übungen hatte jeder Worker seinen eigenen Speicher.

Beispiel:

```text
Worker 0 Count: 3
Worker 1 Count: 2
Worker 2 Count: 5
Worker 3 Count: 1
```

Jeder Worker führte seinen eigenen Zähler.

Ein globaler Besucherzähler war damit nicht möglich.

## Die Lösung

Swoole stellt mit `Swoole\Table` einen gemeinsamen Speicher bereit.

Alle Worker können auf dieselben Daten zugreifen.

## Beispiel

```php
$table = new Swoole\Table(1024);

$table->column(
    "count",
    Swoole\Table::TYPE_INT
);

$table->create();

$table->set("visits", [
    "count" => 0
]);
```

## Was bedeutet 1024?

```php
$table = new Swoole\Table(1024);
```

Die Zahl gibt die maximale Anzahl von Datensätzen an.

Beispiel:

```text
1024 mögliche Keys
```

Nicht:

```text
1024 Spalten
```

Nicht:

```text
1024 Byte
```

Sondern:

```text
1024 Datensätze
```

## Was ist ein Datensatz?

Beispiel:

```php
$table->set("visits", [
    "count" => 42
]);
```

Hier gilt:

```text
Key     = visits
Spalte  = count
Wert    = 42
```

Visualisiert:

```text
+--------+-------+
| Key    | Count |
+--------+-------+
| visits | 42    |
+--------+-------+
```

## Spalten definieren

Vor dem Erzeugen der Tabelle müssen die Spalten definiert werden.

```php
$table->column(
    "count",
    Swoole\Table::TYPE_INT
);
```

Weitere Beispiele:

```php
$table->column(
    "price",
    Swoole\Table::TYPE_FLOAT
);

$table->column(
    "name",
    Swoole\Table::TYPE_STRING,
    64
);
```

Unterstützte Datentypen:

```text
TYPE_INT
TYPE_FLOAT
TYPE_STRING
```

## Tabelle erzeugen

```php
$table->create();
```

Erst danach ist die Tabelle einsatzbereit.

## Datensatz anlegen

```php
$table->set("visits", [
    "count" => 0
]);
```

## Datensatz lesen

```php
$table->get("visits", "count");
```

Beispiel:

```text
42
```

## Datensatz ändern

```php
$table->set("visits", [
    "count" => 43
]);
```

## Datensatz löschen

```php
$table->del("visits");
```

## Existenz prüfen

```php
$table->exists("visits");
```

## Anzahl Datensätze

```php
$table->count();
```

Beispiel:

```text
1
```

## Durch alle Datensätze iterieren

```php
foreach ($table as $key => $row) {

    print_r($row);

}
```

## Globaler Besucherzähler

```php
$currentCount = $table->get(
    "visits",
    "count"
);

$newCount = $currentCount + 1;

$table->set("visits", [
    "count" => $newCount
]);
```

## Beispielausgabe

```text
Worker: 0
Global Visits: 1
```

Nächster Request:

```text
Worker: 2
Global Visits: 2
```

Nächster Request:

```text
Worker: 1
Global Visits: 3
```

Der Zähler wird von allen Workern gemeinsam genutzt.

## Warum funktioniert das?

Vorher:

```text
Worker 0
└── eigener Speicher

Worker 1
└── eigener Speicher

Worker 2
└── eigener Speicher
```

Jetzt:

```text
Worker 0
     │
Worker 1
     │
Worker 2
     │
Worker 3
     │
     ▼
Swoole\Table
```

Alle Worker greifen auf dieselbe Tabelle zu.

## SQL-Vergleich

Eine Swoole Table erinnert an eine kleine SQL-Tabelle.

| SQL | Swoole\Table |
|------|------|
| INSERT | set() |
| SELECT | get() |
| UPDATE | set() |
| DELETE | del() |
| COUNT(*) | count() |

## Wichtiger Unterschied zu SQL

SQL:

```text
Datenbank
Festplatte
sehr viele Funktionen
```

Swoole\Table:

```text
RAM
Shared Memory
extrem schnell
```

Die Daten existieren nur solange der Server läuft.

Nach einem Neustart sind die Daten verloren.

## Was haben wir gelernt?

- Worker besitzen normalerweise eigenen Speicher
- Swoole\Table stellt gemeinsamen Speicher bereit
- Mehrere Worker können dieselben Daten lesen und schreiben
- Shared Memory ermöglicht globale Zähler und Statusinformationen
- Eine Swoole Table ähnelt einer kleinen SQL-Tabelle im RAM

## Ausblick

Auf Basis von Swoole\Table können später aufgebaut werden:

- Besucherzähler
- Statusanzeigen
- In-Memory-Stores
- Worker-Statistiken
- Task-Boards
- Shared State

## Beenden

```bash
CTRL + C
```