# Query Parameter

## Lernziel

- Query Parameter aus einer URL auslesen
- Benutzereingaben an den Server übergeben
- Das Array `$request->get` kennenlernen

## Start

Aus dem aktuellen Verzeichnis:

```bash
php server.php
```

Oder vom Projektverzeichnis aus:

```bash
php ./01-http-server/03-query-parameter/server.php
```

## Test

Ohne Parameter:

```bash
curl http://127.0.0.1:9501
```

Mit Parameter:

```bash
curl "http://127.0.0.1:9501/?name=Klaus"
```

Weiteres Beispiel:

```bash
curl "http://127.0.0.1:9501/?name=Bob"
```

## Erwartete Ausgabe

Ohne Parameter:

```text
Hallo Gast
```

Mit Parameter:

```text
Hallo Klaus
```

Weiteres Beispiel:

```text
Hallo Bob
```

## Debug-Tipp

Um die Query Parameter zu untersuchen, kann man sich das Array ausgeben lassen:

```php
print_r($request->get);
```

Beispiel:

```text
Array
(
    [name] => Klaus
)
```

## Was passiert hier?

```php
$name = $request->get['name'] ?? 'Gast';
```

Swoole stellt die Query Parameter im Array `$request->get` bereit.

Wird folgende URL aufgerufen:

```text
http://127.0.0.1:9501/?name=Klaus
```

dann enthält:

```php
$request->get['name']
```

den Wert:

```text
Klaus
```

Der Ausdruck:

```php
?? 'Gast'
```

bedeutet:

> Falls kein Parameter `name` vorhanden ist, verwende den Standardwert `Gast`.

## URL-Aufbau

Beispiel:

```text
http://127.0.0.1:9501/?name=Klaus
```

Bestandteile:

```text
http://127.0.0.1:9501/
                        ↑
                        Beginn der Query Parameter

?name=Klaus
 ↑
 Parametername

      Klaus
      ↑
      Parameterwert
```

## Was haben wir gelernt?

- Query Parameter werden über die URL übertragen
- Swoole stellt sie im Array `$request->get` bereit
- Benutzereingaben können vom Server verarbeitet werden
- Mit `print_r()` lassen sich die übergebenen Werte untersuchen
- Der Null-Coalescing-Operator `??` liefert einen Standardwert

## Beenden

Im Terminal:

```bash
CTRL + C
```

## Hinweis für Linux und macOS

Bei mehreren Query-Parametern muss die URL in Anführungszeichen gesetzt werden:

```bash
curl "http://127.0.0.1:9501/?name=Klaus&alter=50"
```

Das Zeichen `&` hat in der Shell eine besondere Bedeutung und würde sonst als Hintergrundprozess interpretiert werden.