#!/bin/sh

SCRIPTS_DIR=/home/djp/prog/yoyocase/sql
DB_OWNER=yoyocase_owner
DB_PASS=bob
SQL_COMMAND="mysql -u $DB_OWNER -p$DB_PASS yoyocase"

if [ $PWD != $SCRIPTS_DIR ]; then
  echo "Please cd to $SCRIPTS_DIR"
  exit 1
fi

for f in teardown setup delta testdata; do
  $SQL_COMMAND < $f.sql
done

