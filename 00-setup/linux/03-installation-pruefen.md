# 03 Installation prüfen

Nach der Installation sollte überprüft werden, ob Swoole korrekt geladen wurde.

## Geladene PHP-Module anzeigen

```bash
php -m | grep -i swoole
```

Erwartete Ausgabe:

```text
swoole
```

---

## Detailinformationen anzeigen

```bash
php --ri swoole
```

Hier werden unter anderem die installierte Version sowie aktivierte Funktionen angezeigt.

---

## PHP-Konfiguration prüfen

```bash
php --ini
```

Kontrollieren Sie, ob die Erweiterung `swoole` geladen wird.

---

## Erster Funktionstest

Wechseln Sie in das Verzeichnis des ersten Beispiels:

```bash
cd steps/01-http-server/01-hello-world
```

Starten Sie den Server:

```bash
php server.php
```

Erwartete Ausgabe:

```text
Swoole HTTP Server läuft auf http://127.0.0.1:9501
```

---

## Server testen

Im Browser:

```text
http://127.0.0.1:9501
```

Oder mit `curl`:

```bash
curl http://127.0.0.1:9501
```

Erwartete Antwort:

```text
Hello World
```

---

## Fertig

Die Swoole-Installation ist erfolgreich abgeschlossen.

Sie können nun mit den Übungen der Schulung beginnen.