# Mini Routing

## Lernziel

- Unterschiedliche URLs verarbeiten
- Entscheidungen anhand der URI treffen
- Erste Routing-Logik erstellen
- HTTP-Statuscode 404 kennenlernen

## Start

Aus dem aktuellen Verzeichnis:

```bash
php server.php
```

Oder vom Projektverzeichnis aus:

```bash
php ./01-http-server/04-mini-routing/server.php
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
Seite nicht gefunden
```

## Was bedeutet Routing?

Routing bedeutet:

> Der Server entscheidet anhand der aufgerufenen URL, welche Antwort zurückgegeben wird.

Beispiel:

```text
/        → Home
/hello   → Hallo Swoole
/test    → Testseite
```

Der Browser sendet die URL an den Server.

Der Server wertet die URI aus und entscheidet, welche Antwort erzeugt wird.

## Was passiert hier?

```php
$uri = $request->server['request_uri'];
```

Die URI wird aus dem Request gelesen.

Beispiele:

```text
http://127.0.0.1:9501/
```

liefert:

```text
/
```

---

```text
http://127.0.0.1:9501/hello
```

liefert:

```text
/hello
```

---

```text
http://127.0.0.1:9501/test
```

liefert:

```text
/test
```

## Routing mit if

```php
if ($uri === '/hello') {
    $response->end("Hallo Swoole\n");
    return;
}
```

Bedeutung:

```text
Wenn die URI "/hello" ist,
dann sende "Hallo Swoole" zurück.
```

## Warum return?

```php
$response->end(...);
return;
```

Nachdem eine Antwort gesendet wurde, soll die Funktion beendet werden.

Ohne `return` würden auch die nachfolgenden Prüfungen ausgeführt werden.

## Nicht gefundene Seiten

Falls keine Route passt:

```php
$response->status(404);
$response->end("Seite nicht gefunden\n");
```

Der HTTP-Statuscode:

```text
404 = Not Found
```

bedeutet:

> Die angeforderte Seite existiert nicht.

## Was haben wir gelernt?

- Die URI kann zur Steuerung des Programms verwendet werden
- Unterschiedliche URLs können unterschiedliche Antworten liefern
- Routing ist die Zuordnung von URLs zu Programmcode
- Mit `if` kann einfaches Routing umgesetzt werden
- HTTP-Statuscode 404 signalisiert eine nicht vorhandene Seite

## Bonus-Aufgabe

Erweitere den Server um eine eigene Route:

```text
/about
```

oder

```text
/klaus
```

Beispiel:

```bash
curl http://127.0.0.1:9501/about
```

Erwartung:

```text
Über diese Anwendung
```

## Beenden

Im Terminal:

```bash
CTRL + C
```