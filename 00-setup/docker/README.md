# Docker

Diese Anleitung beschreibt die Nutzung von Swoole mit Docker.

Docker wird vor allem für Windows-Systeme oder als Notfalllösung verwendet.

## Voraussetzungen

Docker prüfen:

```bash
docker --version
```

Docker Compose prüfen:

```bash
docker compose version
```

Unter Windows muss Docker Desktop gestartet sein.

---

## Grundidee

Die Dateien bleiben auf dem normalen Dateisystem.

Beispiel unter Windows:

```text
C:\swoole-kurs
```

Dieser Ordner wird in den Container eingebunden:

```text
/app
```

Das bedeutet:

- Dateien werden in VS Code bearbeitet
- PHP und Swoole laufen im Container
- Änderungen sind sofort im Container sichtbar

---

## Container starten

Im Ordner `00-setup/docker`:

```bash
docker compose run --rm -p 9501:9501 swoole bash
```

Danach befindet man sich in einer Bash im Container.

---

## Beispiel starten

Im Container:

```bash
cd /app
php steps/01-http-server/01-hello-world/server.php
```

Im Browser öffnen:

```text
http://localhost:9501
```

Oder testen mit:

```bash
curl http://localhost:9501
```

---

## Änderungen am Code

Wenn `server.php` oder ein Template geändert wird:

1. Datei in VS Code speichern
2. Server mit `Ctrl + C` stoppen
3. Server erneut starten:

```bash
php steps/01-http-server/01-hello-world/server.php
```

Ein Docker-Build ist dafür nicht notwendig.

---

## Container verlassen

```bash
exit
```

---

## Wichtig

Docker ersetzt nur die lokale Installation von PHP und Swoole.

Der Schulungsinhalt bleibt gleich:

- Request / Response
- Routing
- Templates
- Logging
- Worker