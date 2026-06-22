# Request URI

## Lernziel

- Informationen aus einem HTTP-Request auslesen
- Die aufgerufene URL erkennen
- Das Request-Objekt kennenlernen

## Start

Aus dem aktuellen Verzeichnis:

```bash
php server.php
```

Oder vom Projektverzeichnis aus:

```bash
php ./01-http-server/02-request-uri/server.php
```

## Test

Root aufrufen:

```bash
curl http://127.0.0.1:9501
```

Aufruf einer URL:

```bash
curl http://127.0.0.1:9501/test
```

Weitere URL:

```bash
curl http://127.0.0.1:9501/klaus
```

## Erwartete Ausgabe

```text
URI: /
```

```text
URI: /test
```

```text
URI: /klaus
```

## Debug-Tipp

Um die vom Browser gesendeten Server-Informationen zu untersuchen, kann man sich das Array ausgeben lassen:

```php
print_r($request->server);
```

Beispiel:

```text
Array
(
    [request_method] => GET
    [request_uri] => /test
    [remote_addr] => 127.0.0.1
)
```

## Was passiert hier?

```php
$uri = $request->server['request_uri'];
```

Swoole stellt Informationen über den HTTP-Request im Array
`$request->server` bereit.

Wird folgende URL aufgerufen:

```text
http://127.0.0.1:9501/test
```

dann enthält:

```php
$request->server['request_uri']
```

den Wert:

```text
/test
```

Der Server kann dadurch erkennen, welche URL angefordert wurde.

## Was haben wir gelernt?

- Ein HTTP-Request enthält zusätzliche Informationen
- Swoole stellt diese Informationen im Request-Objekt bereit
- Die URI beschreibt den aufgerufenen Pfad
- Mit `print_r()` können wir unbekannte Datenstrukturen untersuchen

## Beenden

Im Terminal:

```bash
CTRL + C
```