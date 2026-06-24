# Mini CRUD Memory API

## Lernziel

- Daten im Speicher des Servers halten
- Benutzer anlegen
- Benutzer abrufen
- GET und POST kombinieren
- Den Unterschied zwischen klassischem PHP und Swoole verstehen

## Warum diese Übung?

Bisher haben wir Requests verarbeitet und Antworten zurückgegeben.

Nun speichern wir Daten zusätzlich im Speicher des laufenden Servers.

```text
POST
↓
Benutzer anlegen

GET
↓
Benutzer abrufen
```

## Verwendete Variable

```php
$users = [];
```

Diese Variable wird beim Start des Servers angelegt.

Neue Benutzer werden dort gespeichert.

## Route: Benutzer anlegen

```text
POST /api/users
```

Beispiel:

```bash
curl -X POST http://127.0.0.1:9501/api/users \
  -H "Content-Type: application/json" \
  -d '{"name":"Klaus"}' | jq
```

Antwort:

```json
{
  "status": "created"
}
```

## Benutzer speichern

```php
$users[] = [
    "name" => $data["name"]
];
```

Bedeutung:

```text
Benutzer an das Ende der Liste anhängen
```

Vergleich zu JavaScript:

```javascript
users.push({
    name: data.name
});
```

## Route: Benutzer abrufen

```text
GET /api/users
```

Aufruf:

```bash
curl http://127.0.0.1:9501/api/users | jq
```

Antwort:

```json
[
  {
    "name": "Klaus"
  }
]
```

## Mehrere Benutzer

Anlegen:

```bash
curl -X POST http://127.0.0.1:9501/api/users \
  -H "Content-Type: application/json" \
  -d '{"name":"Klaus"}'

curl -X POST http://127.0.0.1:9501/api/users \
  -H "Content-Type: application/json" \
  -d '{"name":"Bob"}'
```

Abrufen:

```bash
curl http://127.0.0.1:9501/api/users | jq
```

Antwort:

```json
[
  {
    "name": "Klaus"
  },
  {
    "name": "Bob"
  }
]
```

## Warum use (&$users)?

Der Request-Callback läuft in einer eigenen Funktion.

Ohne:

```php
use (&$users)
```

kann der Callback nicht auf die Benutzerliste zugreifen.

Mit:

```php
use (&$users)
```

wird dieselbe Variable verwendet.

```text
Server
↓
users
↑
↓
Request Callback
```

## CRUD

CRUD steht für:

```text
Create
Read
Update
Delete
```

In dieser Übung verwenden wir:

### Create

```text
POST /api/users
```

Benutzer anlegen.

### Read

```text
GET /api/users
```

Benutzer abrufen.

Update und Delete werden nicht implementiert.

## Der wichtigste Unterschied zu klassischem PHP

Klassisches PHP:

```text
Request
↓
Script startet
↓
Variable entsteht
↓
Antwort
↓
Variable verschwindet
```

Swoole:

```text
Server startet
↓
Variable entsteht
↓
Request 1
↓
Request 2
↓
Request 3
↓
Variable bleibt erhalten
```

Deshalb kann sich der Server Benutzer merken.

## Einschränkung

Die Benutzerliste befindet sich nur im Arbeitsspeicher.

Nach einem Neustart des Servers:

```text
CTRL + C
↓
Server starten
```

ist die Liste wieder leer.

## Was haben wir gelernt?

- Daten im Speicher halten
- Benutzer anlegen
- Benutzer abrufen
- GET und POST kombinieren
- State in Swoole nutzen
- Referenzen mit `use (&$users)` verwenden

## Ausblick

In den Worker-Übungen haben wir gelernt:

```text
Jeder Worker besitzt eigenen Speicher
```

Für gemeinsam genutzte Daten kann später verwendet werden:

```text
Swoole\Table
```

oder

```text
Datenbank
Redis
Dateien
```

## API-Zusammenfassung

```text
01-json-response
✓ JSON zurückgeben

02-query-parameter-api
✓ GET
✓ Query Parameter

03-post-json-api
✓ POST
✓ JSON empfangen

04-post-validation
✓ Validierung
✓ Fehlerbehandlung

05-mini-crud-memory
✓ Daten speichern
✓ Daten abrufen
✓ State
```