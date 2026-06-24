# API Grundlagen mit Swoole

## Ziel

In diesem Kapitel lernen wir die Grundlagen moderner HTTP APIs kennen.

Wir verwenden:

- GET Requests
- POST Requests
- JSON
- Query Parameter
- Request Bodies
- Validierung
- Status Codes

Alle Beispiele basieren auf einem Swoole HTTP Server.

---

# Was ist eine API?

API steht für:

```text
Application Programming Interface
```

Eine API ermöglicht die Kommunikation zwischen Anwendungen.

Beispiele:

```text
Angular Frontend
↓
HTTP Request
↓
Swoole API
↓
JSON Response
```

oder:

```text
Mobile App
↓
HTTP Request
↓
API
↓
JSON
```

---

# GET und POST

## GET

GET wird verwendet, um Daten abzurufen.

Beispiel:

```http
GET /api/users
```

oder:

```http
GET /api/hello?name=Klaus
```

---

## POST

POST wird verwendet, um Daten an den Server zu senden.

Beispiel:

```http
POST /api/users
```

JSON Body:

```json
{
  "name": "Klaus"
}
```

---

# JSON

JSON ist das Standardformat moderner APIs.

Beispiel:

```json
{
  "status": "ok",
  "message": "Hallo Klaus"
}
```

---

# Status Codes

## 200 OK

Anfrage erfolgreich.

```text
Request erfolgreich verarbeitet
```

---

## 201 Created

Neuer Datensatz erstellt.

```text
Benutzer angelegt
```

---

## 400 Bad Request

Ungültige Anfrage.

```text
Pflichtfeld fehlt
Ungültiges JSON
```

---

## 404 Not Found

Route existiert nicht.

```text
/api/test
```

---

# Übersicht der Übungen

---

## 01-json-response

Lernziel:

```text
JSON Antworten erzeugen
```

Route:

```text
GET /api/status
```

Beispiel:

```json
{
  "status": "ok",
  "message": "Swoole API läuft"
}
```

---

## 02-query-parameter-api

Lernziel:

```text
Query Parameter lesen
```

Route:

```text
GET /api/hello?name=Klaus
```

Auslesen:

```php
$request->get["name"]
```

Antwort:

```json
{
  "message": "Hallo Klaus"
}
```

---

## 03-post-json-api

Lernziel:

```text
JSON Daten empfangen
```

Route:

```text
POST /api/echo
```

Auslesen:

```php
$request->rawContent()
```

JSON:

```php
json_decode(...)
```

Antwort:

```json
{
  "received": {
    "name": "Klaus"
  }
}
```

---

## 04-post-validation

Lernziel:

```text
Daten prüfen
Fehler erkennen
```

Validierung:

```php
isset(...)
trim(...)
is_array(...)
```

Mögliche Antworten:

```json
{
  "error": "Name is required"
}
```

oder:

```json
{
  "error": "Invalid JSON"
}
```

---

## 05-mini-crud-memory

Lernziel:

```text
State im Server speichern
```

Routen:

```text
POST /api/users
GET  /api/users
```

Benutzer anlegen:

```json
{
  "name": "Klaus"
}
```

Benutzer abrufen:

```json
[
  {
    "name": "Klaus"
  }
]
```

---

# Zusammenhang mit den Worker Übungen

Im Worker-Kapitel haben wir gelernt:

```text
Variablen bleiben erhalten
```

Deshalb funktioniert:

```php
$users = [];
```

über mehrere Requests hinweg.

Beispiel:

```text
POST Klaus
POST Bob
GET Users
```

liefert:

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

---

# Wichtige Funktionen

## JSON erzeugen

```php
json_encode(...)
```

---

## JSON lesen

```php
json_decode(..., true)
```

---

## Query Parameter

```php
$request->get
```

---

## Request Body

```php
$request->rawContent()
```

---

## HTTP Methode

```php
$request->server["request_method"]
```

---

## URI

```php
$request->server["request_uri"]
```

---

# Was haben wir gelernt?

- GET Requests
- POST Requests
- JSON
- Query Parameter
- Request Bodies
- Routing
- Validierung
- Status Codes
- State im Speicher
- Grundlagen einer REST API

---

# Ausblick

Als Nächstes beschäftigen wir uns mit:

```text
Timer
WebSocket
Realtime Kommunikation
```

und bauen auf den bisherigen HTTP- und API-Grundlagen auf.