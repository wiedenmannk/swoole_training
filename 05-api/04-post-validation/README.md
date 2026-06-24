# POST Validation API

## Lernziel

- JSON-Daten empfangen
- Requests validieren
- Fehler erkennen
- HTTP-Statuscodes verwenden
- Erfolgs- und Fehlerantworten unterscheiden

## Warum Validierung?

Nicht jeder Request enthält gültige Daten.

Der Server muss prüfen:

```text
Sind alle Pflichtfelder vorhanden?
Sind die Daten gültig?
Kann die Anfrage verarbeitet werden?
```

## Route

```text
POST /api/users
```

## Erwartete Daten

```json
{
  "name": "Klaus"
}
```

## Validierung

Der Server prüft:

```php
if (!is_array($data))
```

Ist gültiges JSON vorhanden?

---

```php
if (!isset($data["name"]))
```

Ist das Feld vorhanden?

---

```php
trim($data["name"]) === ""
```

Ist das Feld leer?

## Erfolgreicher Request

Aufruf:

```bash
curl -X POST http://127.0.0.1:9501/api/users \
  -H "Content-Type: application/json" \
  -d '{"name":"Klaus"}' | jq
```

Antwort:

```json
{
  "status": "created",
  "user": {
    "name": "Klaus"
  }
}
```

HTTP Status:

```text
201 Created
```

## Fehlendes Feld

Aufruf:

```bash
curl -X POST http://127.0.0.1:9501/api/users \
  -H "Content-Type: application/json" \
  -d '{}' | jq
```

Antwort:

```json
{
  "error": "Name is required"
}
```

HTTP Status:

```text
400 Bad Request
```

## Ungültiges JSON

Aufruf:

```bash
curl -X POST http://127.0.0.1:9501/api/users \
  -H "Content-Type: application/json" \
  -d '{test}' | jq
```

Antwort:

```json
{
  "error": "Invalid JSON"
}
```

HTTP Status:

```text
400 Bad Request
```

## Verwendete Statuscodes

### 201 Created

```text
Datensatz erfolgreich angelegt
```

### 400 Bad Request

```text
Anfrage enthält ungültige Daten
```

### 404 Not Found

```text
Route existiert nicht
```

## API-Ablauf

```text
Request
↓
JSON lesen
↓
Validierung
↓
Fehler?
├─ Ja  → 400
└─ Nein → 201
```

## Warum ist das wichtig?

In echten APIs werden Daten fast immer geprüft:

```text
Benutzername
E-Mail
Passwort
Bestellungen
Adressen
Formulare
```

Ohne Validierung könnten fehlerhafte Daten gespeichert werden.

## Was haben wir gelernt?

- JSON empfangen
- JSON prüfen
- Pflichtfelder validieren
- Fehlerantworten zurückgeben
- HTTP-Statuscodes verwenden

## Ausblick

Im nächsten Schritt speichern wir Daten im Speicher des Servers.

```text
POST /api/users
↓
Benutzer anlegen

GET /api/users
↓
Benutzer abrufen
```