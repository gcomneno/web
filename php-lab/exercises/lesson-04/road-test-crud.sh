#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "$0")" && pwd)"
DB="php4_crud_rt_$RANDOM"
DBUSER="php4crud_$RANDOM"
DBPASS="CrudRoad_${RANDOM}_$(date +%s)"
PORT=18085
LOG="$(mktemp)"
SERVER_PID=""

cleanup() {
  set +e
  if [ -n "${SERVER_PID:-}" ]; then
    kill "$SERVER_PID" 2>/dev/null
    wait "$SERVER_PID" 2>/dev/null
  fi
  sudo mysql -e "DROP DATABASE IF EXISTS \`$DB\`; DROP USER IF EXISTS '$DBUSER'@'localhost';" >/dev/null 2>&1
  rm -f "$LOG"
}
trap cleanup EXIT INT TERM

sudo mysql <<SQL
CREATE DATABASE \`$DB\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER '$DBUSER'@'localhost' IDENTIFIED BY '$DBPASS';
GRANT SELECT, INSERT, UPDATE, DELETE ON \`$DB\`.* TO '$DBUSER'@'localhost';
SQL

sudo mysql "$DB" < "$ROOT/database/schema.sql"
sudo mysql "$DB" < "$ROOT/database/seed.sql"

PHP4_DB_HOST=localhost \
PHP4_DB_NAME="$DB" \
PHP4_DB_USER="$DBUSER" \
PHP4_DB_PASSWORD="$DBPASS" \
php -S "127.0.0.1:$PORT" -t "$ROOT" >"$LOG" 2>&1 &
SERVER_PID=$!

for _ in $(seq 1 30); do
  if curl -fsS "http://127.0.0.1:$PORT/prodotti.php" >/dev/null 2>&1; then
    break
  fi
  sleep 0.2
done

echo "===== CREATE ====="
CREATE_HEADERS="$(mktemp)"
curl -fsS -D "$CREATE_HEADERS" -o /dev/null \
  -X POST \
  --data-urlencode 'titolo=CRUD Road Test' \
  --data-urlencode 'autore=1' \
  --data-urlencode 'genere=1' \
  --data-urlencode 'durata=4.25' \
  --data-urlencode 'anno=2026' \
  --data-urlencode 'prezzo=9.99' \
  "http://127.0.0.1:$PORT/salva.php"
ID="$(sudo mysql -NBe "SELECT id FROM \`$DB\`.brani WHERE titolo='CRUD Road Test' ORDER BY id DESC LIMIT 1")"
test -n "$ID"
rm -f "$CREATE_HEADERS"
echo "CREATE=PASS id=$ID"

echo "===== READ ====="
curl -fsS "http://127.0.0.1:$PORT/dettaglioprodotto.php?id=$ID" | grep -q 'CRUD Road Test'
echo "READ_DETAIL=PASS"

echo "===== UPDATE ====="
curl -fsS -o /dev/null \
  -X POST \
  --data-urlencode "id=$ID" \
  --data-urlencode 'titolo=CRUD Road Test Updated' \
  --data-urlencode 'autore=2' \
  --data-urlencode 'genere=2' \
  --data-urlencode 'durata=5.50' \
  --data-urlencode 'anno=2025' \
  --data-urlencode 'prezzo=12.34' \
  "http://127.0.0.1:$PORT/aggiorna.php"
UPDATED="$(sudo mysql -NBe "SELECT CONCAT(titolo,'|',autore_id,'|',genere_id,'|',prezzo) FROM \`$DB\`.brani WHERE id=$ID")"
test "$UPDATED" = 'CRUD Road Test Updated|2|2|12.34'
echo "UPDATE=PASS"

echo "===== DELETE ====="
curl -fsS -o /dev/null \
  -X POST \
  --data-urlencode "id=$ID" \
  "http://127.0.0.1:$PORT/eliminaprodotto.php"
LEFT="$(sudo mysql -NBe "SELECT COUNT(*) FROM \`$DB\`.brani WHERE id=$ID")"
test "$LEFT" = '0'
echo "DELETE=PASS"

echo "===== STATIC SAFETY ====="
find "$ROOT" -maxdepth 2 -name '*.php' -print0 | xargs -0 -n1 php -l >/dev/null
if grep -Ei 'Fatal error|Uncaught|Parse error' "$LOG"; then
  echo "PHP_SERVER_ERROR_GATE=FAIL"
  exit 1
fi
echo "PHP_SYNTAX=PASS"
echo "PHP_SERVER_ERROR_GATE=PASS"

echo "===== FINAL ====="
echo "PHP_4_CREATE=PASS"
echo "PHP_4_READ=PASS"
echo "PHP_4_UPDATE=PASS"
echo "PHP_4_DELETE=PASS"
echo "PHP_4_CRUD=COMPLETE"
echo "PHP_4_END_TO_END_RUNTIME=PASS"
