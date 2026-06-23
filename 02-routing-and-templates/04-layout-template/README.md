# Layout Template

## Lernziel

- Gemeinsame Layouts verwenden
- Wiederholungen im HTML vermeiden
- Inhalte in ein Layout einbetten
- Verstehen, wie `$content` entsteht

## Start

Aus dem aktuellen Verzeichnis:

```bash
php server.php
```

Oder vom Projektverzeichnis aus:

```bash
php ./02-routing-and-templates/04-layout-template/server.php
```

## Projektstruktur

```text
04-layout-template/
├── server.php
├── render.php
└── templates/
    ├── layout.php
    ├── home.php
    └── hello.php
```

## Test

Startseite:

```text
http://127.0.0.1:9501/
```

Hello-Seite:

```text
http://127.0.0.1:9501/hello?name=Klaus
```

Alternativ:

```bash
curl http://127.0.0.1:9501/
```

```bash
curl "http://127.0.0.1:9501/hello?name=Klaus"
```

## Erwartete Ausgabe

Startseite:

```html
<html>
    <body>

        <h1>Home</h1>

    </body>
</html>
```

Hello-Seite:

```html
<html>
    <body>

        <h1>Hallo Klaus</h1>

    </body>
</html>
```

## Das Problem

In der vorherigen Übung enthielt jede Template-Datei eine vollständige HTML-Seite:

```php
<html>
    <body>
        <h1>Home</h1>
    </body>
</html>
```

und:

```php
<html>
    <body>
        <h1>Hallo <?= $name ?></h1>
    </body>
</html>
```

Mit zunehmender Anzahl von Seiten würde derselbe HTML-Rahmen immer wieder kopiert werden.

## Die Lösung

Die Template-Dateien enthalten nur noch ihren eigentlichen Inhalt.

### home.php

```php
<h1>Home</h1>
```

### hello.php

```php
<h1>Hallo <?= $name ?></h1>
```

Der gemeinsame HTML-Rahmen wird in eine eigene Datei ausgelagert.

### layout.php

```php
<html>
    <body>

        <?= $content ?>

    </body>
</html>
```

## Wie funktioniert das?

### Schritt 1

Das eigentliche Template wird gerendert:

```php
include 'home.php';
```

Ergebnis:

```html
<h1>Home</h1>
```

### Schritt 2

Der erzeugte HTML-Inhalt wird gespeichert:

```php
$content = ob_get_clean();
```

Nun enthält `$content`:

```html
<h1>Home</h1>
```

### Schritt 3

Das Layout wird geladen:

```php
include 'layout.php';
```

Im Layout steht:

```php
<?= $content ?>
```

### Schritt 4

Der Inhalt wird in das Layout eingefügt.

Ergebnis:

```html
<html>
    <body>

        <h1>Home</h1>

    </body>
</html>
```

## Zwei Output Buffer

In dieser Übung werden zwei Buffer verwendet.

### Erster Buffer

Erzeugt den eigentlichen Seiteninhalt:

```php
ob_start();

include 'home.php';

$content = ob_get_clean();
```

### Zweiter Buffer

Erzeugt die fertige Seite:

```php
ob_start();

include 'layout.php';

return ob_get_clean();
```

## Datenfluss

```text
home.php
↓
HTML erzeugen
↓
$content
↓
layout.php
↓
<?= $content ?>
↓
fertige HTML-Seite
```

## Was haben wir gelernt?

- Layouts vermeiden HTML-Duplikate
- Templates enthalten nur noch ihren eigentlichen Inhalt
- `$content` verbindet Template und Layout
- Mehrere Output Buffer können kombiniert werden
- Die Render-Funktion entwickelt sich zu einer kleinen View-Engine

## Vergleich zu Frameworks

Viele Frameworks arbeiten nach einem ähnlichen Prinzip:

```text
Template
↓
Layout
↓
Fertige HTML-Seite
```

Beispiele:

- Express.js (Layouts / View Engines)
- Laravel (Blade Layouts)
- Symfony (Twig Layouts)
- Angular (Layout-Komponenten)

## Ausblick

Im nächsten Schritt werden gemeinsame Bereiche ausgelagert:

```text
header.php
footer.php
navigation.php
```

Dadurch entstehen wiederverwendbare Teil-Templates (Partials).

## Beenden

Im Terminal:

```bash
CTRL + C
```