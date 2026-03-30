#!/usr/bin/env bash
set -euo pipefail

PREFIX="${ACG_DB_PREFIX:-acg_}"
ADMIN_EMAIL="${ACG_ADMIN_EMAIL:-admin@example.com}"
ADMIN_NICKNAME="${ACG_ADMIN_NICKNAME:-admin}"
ADMIN_PASSWORD="${ACG_ADMIN_PASSWORD:-Admin@123456}"
DB_NAME="${MYSQL_DATABASE:-acg_faka}"

escape_sed() {
  printf '%s' "$1" | sed -e 's/[\/&]/\\&/g'
}

SALT="$(od -An -N16 -tx1 /dev/urandom | tr -d ' \n')"
PASS_MD5="$(printf '%s' "$ADMIN_PASSWORD" | md5sum | awk '{print $1}')"
SALT_MD5="$(printf '%s' "$SALT" | md5sum | awk '{print $1}')"
MERGED_MD5="$(printf '%s' "${PASS_MD5}${SALT_MD5}" | md5sum | awk '{print $1}')"
PASS_HASH="$(printf '%s' "$MERGED_MD5" | sha1sum | awk '{print $1}')"

TMP_SQL="/tmp/acg-init.sql"

sed \
  -e "s/__PREFIX__/$(escape_sed "$PREFIX")/g" \
  -e "s/__MANAGE_EMAIL__/$(escape_sed "$ADMIN_EMAIL")/g" \
  -e "s/__MANAGE_PASSWORD__/$(escape_sed "$PASS_HASH")/g" \
  -e "s/__MANAGE_NICKNAME__/$(escape_sed "$ADMIN_NICKNAME")/g" \
  -e "s/__MANAGE_SALT__/$(escape_sed "$SALT")/g" \
  /docker-entrypoint-initdb.d/Install.sql > "$TMP_SQL"

mysql -uroot -p"${MYSQL_ROOT_PASSWORD}" "$DB_NAME" < "$TMP_SQL"

rm -f "$TMP_SQL"
