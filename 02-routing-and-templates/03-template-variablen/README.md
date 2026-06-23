# Template Variablen

## Lernziel

- Daten an Templates übergeben
- Die Funktion `render()` erweitern
- Verstehen, wie Template-Variablen entstehen
- Query Parameter mit HTML-Ausgabe kombinieren

## Start

Aus dem aktuellen Verzeichnis:

```bash
php server.php
```

Oder vom Projektverzeichnis aus:

```bash
php ./02-routing-and-templates/03-template-variablen/server.php
```

## Projektstruktur

```text
03-template-variablen/
├── server.php
├── render.php
└── templates/
    ├── home.php
    └── hello.php
```

## Test

Home-Seite:

```text
http://127.0.0.1:9501/
```

Hello-Seite ohne Parameter:

```text
http://127.0.0.1:9501/hello
```

Hello-Seite mit Parameter:

```text
http://127.0.0.1:9501/hello?name=Klaus
```

Weiteres Beispiel:

```text
http://127.0.0.1:9501/hello?name=Bob
```

Alternativ mit curl:

```bash
curl http://127.0.0.1:9501/hello
```

```bash
curl "http://127.0.0.1:9501/hello?name=Klaus"
```

## Erwartete Ausgabe

Ohne Parameter:

```html
<h1>Hallo Gast</h1>
```

Mit Parameter:

```html
<h1>Hallo Klaus</h1>
```

Weiteres Beispiel:

```html
<h1>Hallo Bob</h1>
```

## Die erweiterte Render-Funktion

```php
function render(string $template, array $data = []): string
{
    extract($data);

    ob_start();

    include __DIR__ . '/templates/' . $template;

    return ob_get_clean();
}
```

## Was macht extract()?

Beispiel:

```php
[
    'name' => 'Klaus'
]
```

wird durch:

```php
extract($data);
```

zu:

```php
$name = 'Klaus';
```

Die Schlüssel des Arrays werden zu Variablen.

## Übergabe von Daten

Im Router:

```php
render('hello.php', [
    'name' => $name
]);
```

Das Array wird an die Render-Funktion übergeben.

## Verwendung im Template

```php
<h1>Hallo <?= $name ?></h1>
```

Die Variable `$name` wurde zuvor durch `extract()` erzeugt.

## Woher kommt der Name?

Die Route liest den Query Parameter:

```php
$name = $request->get['name'] ?? 'Gast';
```

Beispiel:

```text
/hello?name=Klaus
```

liefert:

```php
$request->get['name']
```

mit dem Wert:

```text
Klaus
```

## Datenfluss

```text
Browser
↓
/hello?name=Klaus
↓
$request->get
↓
$name = Klaus
↓
render(...)
↓
extract(...)
↓
$name im Template
↓
HTML
↓
Browser
```

## Hinweis zu VSCode

Viele IDEs markieren:

```php
$name
```

im Template als möglicherweise undefiniert.

Der Grund:

```php
extract($data);
```

erzeugt die Variable erst zur Laufzeit.

Die IDE kann diesen Zusammenhang oft nicht erkennen.

Das Template funktioniert trotzdem korrekt.

## Was haben wir gelernt?

- Daten können an Templates übergeben werden
- Arrays können mit `extract()` in Variablen umgewandelt werden
- Query Parameter können direkt in HTML ausgegeben werden
- Templates können dynamische Inhalte anzeigen
- Routing, Request und Templates arbeiten zusammen

## Express.js Vergleich

Express:

```javascript
res.render('hello', {
    name: 'Klaus'
});
```

Swoole:

```php
render('hello.php', [
    'name' => 'Klaus'
]);
```

Das Konzept ist identisch:

```text
Daten
↓
Template
↓
HTML
```

## Ausblick

Im nächsten Schritt werden wir ein gemeinsames Layout einführen.

Beispiele:

```text
layout.php
header.php
footer.php
```

Damit müssen HTML-Strukturen nicht auf jeder Seite wiederholt werden.

## Beenden

Im Terminal:

```bash
CTRL + C
```