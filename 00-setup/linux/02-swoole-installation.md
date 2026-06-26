# 02 Swoole installieren

Die Installation erfolgt über PECL.

## Installation starten

```bash
sudo pecl install swoole
```

Während der Installation werden verschiedene Optionen abgefragt.

Für diese Schulung verwenden wir folgende Konfiguration:

| Option | Wert |
|---------|------|
| sockets | yes |
| curl | yes |
| openssl | Standard (Enter) |
| mysqlnd | no |
| cares | no |
| brotli | no |
| zstd | no |
| PostgreSQL | no |
| ODBC | no |
| Oracle | no |
| Sqlite | no |
| Firebird | no |
| Thread Support | no |
| io_uring | no |
| SSH2 | no |
| FTP | no |

Nach erfolgreicher Installation zeigt PECL den Speicherort der Erweiterung an.

Beispiel:

```text
Installing '/usr/lib/php/20230831/swoole.so'
install ok: channel://pecl.php.net/swoole-6.x.x
```

---

## Erweiterung aktivieren

Die Erweiterung muss anschließend in der PHP-Konfiguration aktiviert werden.

```ini
extension=swoole
```

Je nach Linux-Distribution befindet sich die Konfiguration an einer anderen Stelle.

Prüfen:

```bash
php --ini
```

Dort wird die geladene `php.ini` sowie zusätzliche Konfigurationsdateien angezeigt.

Nach dem Eintrag `extension=swoole` ist die Installation abgeschlossen.