# Entwicklungsumgebung

## PHP prüfen

php -m | grep -i swoole

Erwartet:
swoole

## Swoole IDE Helper installieren

composer require --dev swoole/ide-helper

Nutzen:
- Autocomplete
- Methodenhinweise
- Typinformationen
- Keine roten Markierungen in VSCode

Wird nur für die IDE benötigt.
Die echte Ausführung erfolgt weiterhin über die installierte Swoole-Erweiterung.