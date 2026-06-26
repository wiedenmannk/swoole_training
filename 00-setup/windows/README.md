# Windows

## Empfehlung

Für diese Schulung empfehlen wir die Verwendung von Docker.

Dadurch entfällt die Installation von PHP und Swoole unter Windows.

Die gesamte Laufzeitumgebung befindet sich im Docker-Container.

## Voraussetzungen

Folgende Software sollte installiert sein:

- Docker Desktop
- Visual Studio Code

Eine lokale PHP-Installation ist nicht erforderlich.

## Installation

Bitte folgen Sie der Docker-Anleitung:

→ `../docker/README.md`

## Warum Docker?

Docker bietet für diese Schulung einige Vorteile:

- identische Entwicklungsumgebung für alle Teilnehmer
- keine Installation von Swoole unter Windows erforderlich
- keine Anpassung der PHP-Konfiguration notwendig
- alle Beispiele funktionieren unverändert

## Hinweis

Die Entwicklung erfolgt wie gewohnt in Visual Studio Code.

Die Projektdateien bleiben auf dem Windows-Dateisystem und werden vom Docker-Container verwendet.