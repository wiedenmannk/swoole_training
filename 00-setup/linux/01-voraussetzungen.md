# 01 Voraussetzungen

Bevor Swoole installiert werden kann, sollten einige Werkzeuge verfügbar sein.

## PHP prüfen

```bash
php -v
```

Beispiel:

```text
PHP 8.3.x (cli)
```

---

## PECL prüfen

```bash
pecl version
```

Beispiel:

```text
PEAR Version: ...
PHP Version: ...
```

---

## Compiler prüfen

```bash
gcc --version
```

---

## Make prüfen

```bash
make --version
```

---

## PHP-Entwicklungswerkzeuge prüfen

```bash
phpize --version
```

---

## Zusammenfassung

Folgende Werkzeuge sollten verfügbar sein:

| Werkzeug | Zweck |
|----------|-------|
| PHP | Laufzeitumgebung |
| PECL | Installation von PHP-Erweiterungen |
| gcc | Compiler |
| make | Build-Werkzeug |
| phpize | Vorbereitung der PHP-Erweiterung |

Sind alle Werkzeuge vorhanden, kann die Installation von Swoole beginnen.