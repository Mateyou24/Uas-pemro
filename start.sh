#!/bin/bash

MARIADB_BIN=/nix/store/a4jsa8kjdn3wlccj2wkvhxqza38rpxzf-mariadb-server-10.11.13/bin/mariadbd
MARIADB_SHARE=/nix/store/a4jsa8kjdn3wlccj2wkvhxqza38rpxzf-mariadb-server-10.11.13/share/mysql
DATADIR=/home/runner/workspace/.mysql_data
SOCKET=/tmp/mysql.sock

# Remove stale socket
rm -f "$SOCKET"

# Start MariaDB in background
"$MARIADB_BIN" --no-defaults \
    "--basedir=/nix/store/a4jsa8kjdn3wlccj2wkvhxqza38rpxzf-mariadb-server-10.11.13" \
    "--datadir=$DATADIR" \
    "--socket=$SOCKET" \
    --port=3306 \
    --bind-address=127.0.0.1 \
    --skip-grant-tables \
    --skip-networking=0 \
    --pid-file=/tmp/mariadb.pid \
    >/tmp/mariadb.log 2>&1 &
MARIADB_PID=$!

echo "MariaDB starting (PID: $MARIADB_PID)..."

# Wait for MariaDB to be ready
for i in $(seq 1 30); do
    if mysql -u root -S "$SOCKET" -e "SELECT 1;" >/dev/null 2>&1; then
        echo "MariaDB ready after ${i}s"
        break
    fi
    sleep 1
done

# Initialize system tables if not already done
if ! mysql -u root -S "$SOCKET" -e "USE mysql; SELECT COUNT(*) FROM user;" >/dev/null 2>&1; then
    echo "Initializing MariaDB system tables..."
    mysql -u root -S "$SOCKET" < "$MARIADB_SHARE/mysql_system_tables.sql" 2>/dev/null
    mysql -u root -S "$SOCKET" mysql < "$MARIADB_SHARE/mysql_performance_tables.sql" 2>/dev/null
    mysql -u root -S "$SOCKET" mysql < "$MARIADB_SHARE/mysql_system_tables_data.sql" 2>/dev/null
    mysql -u root -S "$SOCKET" mysql < "$MARIADB_SHARE/mysql_sys_schema.sql" 2>/dev/null
    echo "System tables initialized."
fi

# Create the application database if not exists
if ! mysql -u root -S "$SOCKET" -e "USE boardgame_hub;" >/dev/null 2>&1; then
    echo "Importing boardgame_hub database..."
    mysql -u root -S "$SOCKET" -e "CREATE DATABASE IF NOT EXISTS boardgame_hub CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
    mysql -u root -S "$SOCKET" boardgame_hub < /home/runner/workspace/boardgame_hub.sql
    echo "Database imported."
fi

echo "Starting PHP server on port 5000..."
php -S 0.0.0.0:5000 -t /home/runner/workspace &
PHP_PID=$!
echo "PHP server started (PID: $PHP_PID)"

# Keep script alive - wait for MariaDB
wait $MARIADB_PID
