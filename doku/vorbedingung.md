## Vorbedingungen für Swoole unter Linux
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

php Version checken
php -v


sudo apt install -y composer

composer testen
composer --version

## PECL installieren
PECL ist vereinfacht gesagt ein Installationssystem für PHP-Erweiterungen.

check ob pecl vorhanden ist
pecl version

Ausgabe Beispiel:
PEAR Version: 1.10.13
PHP Version: 8.3.6
Zend Engine Version: 4.3.6

falls nicht dann noch mal installieren:
sudo apt install php-pear php-dev build-essential


## Build Werkzeuge prüfen
gcc --version
make --version
phpize --version


## checken ob Swoole vorhanden ist
php -m | grep -i swoole

## Package Libcurl installieren
sudo apt install -y libcurl4-openssl-dev


## Swoole installieren
sudo pecl install swoole
bei enable socket support: yes
specify openssl installation directory (requires openssl 1.1.0 or later)? [no] : => enter drücken
enable mysqlnd support? [no] : => enter oder no
enable curl support? [no] : yes
enable cares support? [no] : no oder enter
enable brotli support? [yes] : no
specify brotli installation directory? [no] : no oder enter
enable zstd support (requires zstd 1.4.0 or later)? [no] : no oder enter
enable PostgreSQL database support? [no] : no oder enter
enable ODBC database support? [no] : no oder enter
enable Oracle database support? [no] : no oder enter
enable Sqlite database support? [no] : no oder enter
enable Firebird database support? [no] : no oder enter
enable swoole thread support (need php zts support)? [no] : no oder enter
enable iouring for file async support? [no] : no oder enter
specify liburing installation directory (requires liburing 2.8 or later)? [no] :no oder enter
enable async ssh2 client support? [no] : no oder enter
enable async ftp client support? [no] : no oder enter

nach der Installation sollte irgendwo erscheinen:
Build process completed successfully
Installing '/usr/lib/php/20230831/swoole.so'
Installing '/usr/include/php/20230831/ext/swoole/php_swoole.h'
Installing '/usr/include/php/20230831/ext/swoole/config.h'
install ok: channel://pecl.php.net/swoole-6.2.1
configuration option "php_ini" is not set to php.ini location
You should add "extension=swoole.so" to php.ini


## Swoole in php einbinden
checken ob swoole vorhanden ist
php -m | grep swoole
falls leer dann swoole einbinden

für Übersicht aus den conf Dateien
php --ini

Beispiel für config Datei: /etc/php/8.3/cli/conf.d
echo "extension=swoole.so" | sudo tee /etc/php/8.3/cli/conf.d/20-swoole.ini

## Swoole check ##
such nach php module swoole
php -m | grep swoole

php --ri swoole
sollte eine Ausgabe wie diese bringen:
swoole

Swoole => enabled
Author => Swoole Team <team@swoole.com>
Version => 6.2.1
Built => Jun 22 2026 12:30:15
host byte order => little endian
coroutine => enabled with boost asm context
epoll => enabled
eventfd => enabled
signalfd => enabled
cpu_affinity => enabled
spinlock => enabled
rwlock => enabled
sockets => enabled
openssl => OpenSSL 3.0.13 30 Jan 2024
dtls => enabled
http2 => enabled
json => enabled
curl-native => enabled
curl-version => 8.5.0
mutex_timedlock => enabled
pthread_barrier => enabled
futex => enabled
execinfo => enabled

Directive => Local Value => Master Value
swoole.enable_library => On => On
swoole.enable_fiber_mock => Off => Off
swoole.enable_preemptive_scheduler => Off => Off
swoole.display_errors => On => On
swoole.use_shortname => On => On
swoole.socket_buffer_size => 8388608 => 8388608
swoole.blocking_detection => Off => Off
swoole.blocking_threshold => 100000 => 100000
swoole.profile => Off => Off
swoole.leak_detection => Off => Off

## Entwicklungsumgebung einrichten
composer require --dev swoole/ide-helper