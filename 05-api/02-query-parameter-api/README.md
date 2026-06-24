# Query Parameter API

## Lernziel

- Query Parameter auslesen
- JSON-Antworten erzeugen
- Routing und Parameter kombinieren
- Eine einfache API bereitstellen

## Start

Aus dem aktuellen Verzeichnis:

```bash
php server.php
```

Oder vom Projektverzeichnis aus:

```bash
php ./05-api/02-query-parameter-api/server.php
```

## Verfügbare Routen

### Status

```text
/api/status
```

### Begrüßung

```text
/api/hello
```

Optional mit Query Parameter:

```text
/api/hello?name=Klaus
```

## Beispielcode

```php
if ($uri === "/api/hello") {

    $name = $request->get["name"] ?? "Gast";

    $response->end(
        json_encode([
            "message" => "Hallo {$name}"
        ])
    );

    return;
}
```

## Query Parameter

URL:

```text
/api/hello?name=Klaus
```

Parameter:

```text
name = Klaus
```

Auslesen:

```php
$name = $request->get["name"] ?? "Gast";
```

Falls kein Parameter vorhanden ist:

```text
Gast
```

wird als Standardwert verwendet.

## Test mit curl

### Status abrufen

```bash
curl http://127.0.0.1:9501/api/status
```

Mit jq:

```bash
curl http://127.0.0.1:9501/api/status | jq
```

Antwort:

```json
{
  "status": "ok",
  "message": "Swoole API läuft"
}
```

## Begrüßung ohne Parameter

```bash
curl http://127.0.0.1:9501/api/hello | jq
```

Antwort:

```json
{
  "message": "Hallo Gast"
}
```

## Begrüßung mit Parameter

```bash
curl "http://127.0.0.1:9501/api/hello?name=Klaus" | jq
```

Antwort:

```json
{
  "message": "Hallo Klaus"
}
```

## Nicht vorhandene Route

```bash
curl http://127.0.0.1:9501/api/test | jq
```

Antwort:

```json
{
  "error": "Route not found"
}
```

## Warum ist das wichtig?

APIs verwenden häufig Query Parameter:

```text
?page=1
?search=angular
?name=Klaus
?sort=asc
```

Beispiele:

```text
/api/users?page=2
/api/products?search=laptop
/api/orders?status=open
```

## Was haben wir gelernt?

- Query Parameter lesen
- Standardwerte verwenden
- JSON-Antworten erzeugen
- Fehlerantworten zurückgeben
- APIs mit curl testen

## Ausblick

Im nächsten Schritt werden wir Daten per POST an den Server senden.

```text
GET
↓
Daten aus URL lesen

POST
↓
Daten im Request Body senden
```

## Beenden

```bash
CTRL + C
```