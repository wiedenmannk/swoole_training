# Render Funktion

## Lernziel

- HTML aus separaten Dateien laden
- Routing und Darstellung voneinander trennen
- Die Funktion `render()` kennenlernen
- Verstehen, warum `ob_start()` benötigt wird

## Start

Aus dem aktuellen Verzeichnis:

```bash
php server.php
```

Oder vom Projektverzeichnis aus:

```bash
php ./02-routing-and-templates/02-render-funktion/server.php
```

## Projektstruktur

```text
02-render-funktion/
├── server.php
├── render.php
└── templates/
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
http://127.0.0.1:9501/hello
```

Alternativ:

```bash
curl http://127.0.0.1:9501/
```

```bash
curl http://127.0.0.1:9501/hello
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
        <h1>Hallo Swoole</h1>
    </body>
</html>
```

Im Browser werden die HTML-Tags interpretiert und als Webseite dargestellt.

## Warum eine Render-Funktion?

In der vorherigen Übung befand sich das HTML direkt im Router:

```php
case '/hello':

    $response->end("
        <html>
            <body>
                <h1>Hallo Swoole</h1>
            </body>
        </html>
    ");

    break;
```

Das funktioniert, wird aber bei größeren Anwendungen schnell unübersichtlich.

Mit einer Render-Funktion kann das HTML in separate Dateien ausgelagert werden.

## Die Render-Funktion

```php
function render(string $template): string
{
    ob_start();

    include __DIR__ . '/templates/' . $template;

    return ob_get_clean();
}
```

## Was passiert hier?

### Schritt 1

```php
ob_start();
```

Startet einen Output Buffer.

Ausgaben werden nicht sofort angezeigt, sondern zunächst gesammelt.

### Schritt 2

```php
include __DIR__ . '/templates/' . $template;
```

Lädt die Template-Datei.

Alle HTML-Ausgaben landen im Buffer.

### Schritt 3

```php
return ob_get_clean();
```

Liest den Inhalt des Buffers aus und gibt ihn als String zurück.

## Warum wird include nicht direkt verwendet?

Viele Entwickler erwarten:

```php
$html = include 'home.php';
```

Das funktioniert jedoch nicht.

`include` liefert nicht den HTML-Inhalt zurück.

Beispiel:

```php
$result = include 'home.php';

var_dump($result);
```

Ergebnis:

```text
<h1>Home</h1>
int(1)
```

Die Ausgabe erfolgt direkt, während `include` selbst nur den Erfolg des Ladevorgangs zurückliefert.

## Routing mit render()

Vorher:

```php
$response->end("
<html>
    <body>
        <h1>Home</h1>
    </body>
</html>
");
```

Jetzt:

```php
$response->end(
    render('home.php')
);
```

Das Routing wird dadurch deutlich übersichtlicher.

## Was haben wir gelernt?

- HTML kann in separate Dateien ausgelagert werden
- Routing und Darstellung sollten getrennt werden
- `include` führt eine Datei direkt aus
- `ob_start()` sammelt Ausgaben in einem Buffer
- `ob_get_clean()` liefert den Inhalt des Buffers zurück
- Die Render-Funktion bildet die Grundlage für spätere Templates mit Variablen

## Ausblick

Im nächsten Schritt werden Daten an Templates übergeben.

Beispiel:

```php
render('hello.php', [
    'name' => 'Klaus'
]);
```

Das Template kann anschließend auf die Variable zugreifen:

```php
<h1>Hallo <?= $name ?></h1>
```

## Beenden

Im Terminal:

```bash
CTRL + C
```