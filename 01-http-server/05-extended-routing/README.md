# Extended Routing

## Lernziel

- Routing mit `switch` umsetzen
- Mehrere Routen übersichtlich verwalten
- Den Unterschied zwischen `if` und `switch` verstehen
- Eine 404-Seite für unbekannte Routen zurückgeben

## Start

Aus dem aktuellen Verzeichnis:

```bash
php server.php
```

Oder vom Projektverzeichnis aus:

```bash
php ./01-http-server/05-extended-routing/server.php
```

## Test

Startseite:

```bash
curl http://127.0.0.1:9501/
```

Hello-Seite:

```bash
curl http://127.0.0.1:9501/hello
```

Test-Seite:

```bash
curl http://127.0.0.1:9501/test
```

Debug-Seite:

```bash
curl http://127.0.0.1:9501/debug
```

Nicht vorhandene Seite:

```bash
curl http://127.0.0.1:9501/xyz
```

## Erwartete Ausgabe

```text
Home
```

```text
Hallo Swoole
```

```text
Testseite
```

```text
Debugseite
```

```text
Seite nicht gefunden
```

## Warum Extended Routing?

In der vorherigen Übung wurde Routing mit mehreren `if`-Anweisungen umgesetzt:

```php
if ($uri === '/') {
    ...
}

if ($uri === '/hello') {
    ...
}

if ($uri === '/test') {
    ...
}
```

Das funktioniert gut für wenige Routen.

Mit zunehmender Anzahl von Seiten wird der Code jedoch länger und schwerer zu lesen.

## Routing mit switch

```php
switch ($uri) {

    case '/':
        $response->end("Home\n");
        break;

    case '/hello':
        $response->end("Hallo Swoole\n");
        break;

    default:
        $response->status(404);
        $response->end("Seite nicht gefunden\n");
}
```

Bedeutung:

```text
Prüfe die URI
↓
Suche passenden case
↓
Sende Antwort
```

## Was bedeutet break?

```php
break;
```

beendet den aktuellen `case`.

Ohne `break` würde die Ausführung in den nächsten `case` weiterlaufen.

Beispiel:

```php
case '/hello':
    echo "Hallo";
    break;
```

## Die default-Route

```php
default:
    $response->status(404);
    $response->end("Seite nicht gefunden\n");
```

`default` wird ausgeführt, wenn kein passender `case` gefunden wurde.

Beispiel:

```bash
curl http://127.0.0.1:9501/unbekannt
```

Ergebnis:

```text
Seite nicht gefunden
```

## Was haben wir gelernt?

- Routing kann mit `switch` übersichtlicher gestaltet werden
- Jeder `case` repräsentiert eine Route
- `break` beendet einen `case`
- `default` behandelt unbekannte URLs
- HTTP-Statuscode 404 signalisiert eine nicht vorhandene Seite

## Bonus-Aufgabe

Erweitere den Router um eine eigene Route:

```text
/about
```

oder

```text
/contact
```

Beispiel:

```bash
curl http://127.0.0.1:9501/about
```

Erwartung:

```text
Über diese Anwendung
```

## Ausblick

Aktuell liefert jede Route nur einen Text zurück:

```php
$response->end("Hallo Swoole\n");
```

Im nächsten Kapitel werden wir Routing und Darstellung voneinander trennen und HTML-Templates verwenden.

## Beenden

Im Terminal:

```bash
CTRL + C
```