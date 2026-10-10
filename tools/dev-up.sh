#!/bin/sh
# Start the local MariaDB + PHP server for the Sea4Us WordPress build (development only).
pgrep mariadbd >/dev/null || (mysqld_safe >/dev/null 2>&1 &)
sleep 4
pgrep -f "php -S 0.0.0.0:8080" >/dev/null || (cd /tmp/claude-0/wpt/wp && php -S 0.0.0.0:8080 -t /tmp/claude-0/wpt/wp /mnt/project-files/sea4us/tools/router.php >/tmp/claude-0/php.log 2>&1 &)
sleep 1
