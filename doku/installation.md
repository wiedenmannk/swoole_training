# Vorbedingungen für Swoole unter Linux

## Ziel

In dieser Anleitung richten wir eine Entwicklungsumgebung für Swoole unter Linux ein.

Nach Abschluss sollten folgende Werkzeuge verfügbar sein:

* PHP
* Composer
* PECL
* Build-Werkzeuge
* Swoole
* IDE-Unterstützung

---

# 1. PHP installieren

```bash
sudo apt update

sudo apt install -y \
    php-cli \
    php-dev \
    php-xml \
    php-mbstring \
    php-curl \
    php-zip \
    php-pgsql \
    php-sqlite3
```

Installation prüfen:

```bash
php -v
```

---

# 2. Composer installieren

```bash
sudo apt install -y composer
```

Installation prüfen:

```bash
composer --version
```

---

# 3. PECL installieren

PECL dient zur Installation von PHP-Erweiterungen.

Vorhandene Installation prüfen:

```bash
pecl version
```

Falls PECL fehlt:

```bash
sudo apt install php-pear php-dev build-essential
```

---

# 4. Build-Werkzeuge prüfen

```bash
gcc --version

make --version

phpize --version
```

---

# 5. Prüfen, ob Swoole bereits installiert ist

```bash
php -m | grep -i swoole
```

---

# 6. Benötigte Bibliotheken installieren

```bash
sudo apt install -y \
    libcurl4-openssl-dev \
    zlib1g-dev
```

---

# 7. Swoole installieren

```bash
sudo pecl install swoole
```

Empfohlene Optionen:

| Option         | Wert             |
| -------------- | ---------------- |
| sockets        | yes              |
| curl           | yes              |
| openssl        | Standard (Enter) |
| mysqlnd        | no               |
| cares          | no               |
| brotli         | no               |
| zstd           | no               |
| PostgreSQL     | no               |
| ODBC           | no               |
| Oracle         | no               |
| Sqlite         | no               |
| Firebird       | no               |
| Thread Support | no               |
| io_uring       | no               |
| SSH2           | no               |
| FTP            | no               |

Nach erfolgreicher Installation sollte eine Meldung wie diese erscheinen:

```text
Build process completed successfully
...
install ok: channel://pecl.php.net/swoole-6.2.1
```

---

# 8. Swoole aktivieren

PHP-Konfiguration anzeigen:

```bash
php --ini
```

Beispiel:

```text
/etc/php/8.3/cli/conf.d/
```

Swoole aktivieren:

```bash
echo "extension=swoole.so" | sudo tee /etc/php/8.3/cli/conf.d/20-swoole.ini
```

---

# 9. Installation prüfen

```bash
php -m | grep swoole
```

Weitere Informationen:

```bash
php --ri swoole
```

Hier sollte unter anderem erscheinen:

```text
Swoole => enabled
Version => 6.2.1
...
```

---

# 10. IDE-Unterstützung installieren

```bash
composer require --dev swoole/ide-helper
```

---

# Zusammenfassung

Nach Abschluss dieser Schritte stehen folgende Komponenten zur Verfügung:

* PHP
* Composer
* PECL
* Build-Werkzeuge
* Swoole
* IDE Helper

Die Entwicklungsumgebung ist nun bereit für die Übungen.
