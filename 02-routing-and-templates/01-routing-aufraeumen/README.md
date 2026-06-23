# Routing aufräumen

## Lernziel

- HTML an den Browser zurückgeben
- Den Unterschied zwischen Text- und HTML-Ausgabe verstehen
- Erkennen, warum HTML im Router schnell unübersichtlich wird
- Die Notwendigkeit von Templates verstehen

## Start

Aus dem aktuellen Verzeichnis:

```bash
php server.php
```

Oder vom Projektverzeichnis aus:

```bash
php ./02-routing-and-templates/01-routing-aufraeumen/server.php
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

Nicht vorhandene Seite:

```text
http://127.0.0.1:9501/unbekannt
```

Alternativ mit curl:

```bash
curl http://127.0.0.1:9501/
```

```bash
curl http://127.0.0.1:9501/hello
```

## Erwartete Ausgabe

Startseite:

```html
<h1>Home</h1>
```

Hello-Seite:

```html
<h1>Hallo Swoole</h1>
```

Fehlerseite:

```html
<h1>404 - Seite nicht gefunden</h1>
```

Im Browser werden die HTML-Tags interpretiert und als Webseite dargestellt.

## Was hat sich geändert?

In den bisherigen Übungen wurden einfache Texte zurückgegeben:

```php
$response->end("Hallo Swoole\n");
```

Jetzt wird HTML zurückgegeben:

```php
$response->end("
<html>
    <body>
        <h1>Hallo Swoole</h1>
    </body>
</html>
");
```

Der Browser interpretiert das HTML und stellt daraus eine Webseite dar.

## Routing und HTML

Aktuell befinden sich Routing und HTML an derselben Stelle:

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

## Das Problem

Stell dir vor, jede Seite enthält:

```text
100 Zeilen HTML
```

und die Anwendung besitzt:

```text
20 Seiten
```

Dann würde der gesamte HTML-Code direkt im Router stehen.

Der Router müsste gleichzeitig:

- Routing durchführen
- HTML erzeugen
- Inhalte verwalten

Das widerspricht dem Prinzip der Aufgabentrennung.

## Warum Templates?

Statt HTML direkt im Router zu speichern:

```php
case '/hello':
    ...
```

soll später eine separate Datei verwendet werden:

```php
render('hello.php');
```

Dadurch werden Routing und Darstellung voneinander getrennt.

## Was haben wir gelernt?

- Ein Browser erwartet HTML
- Swoole kann HTML direkt zurückgeben
- HTML im Router funktioniert, wird aber schnell unübersichtlich
- Routing und Darstellung sollten getrennt werden
- Templates lösen dieses Problem

## Ausblick

Im nächsten Schritt erstellen wir eine erste Render-Funktion:

```php
render('home.php');
```

Damit können HTML-Dateien außerhalb des Routers gespeichert werden.

## Beenden

Im Terminal:

```bash
CTRL + C
```