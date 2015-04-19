#!/bin/sh

if test -z "$1"
then
DB="ossim"
else
DB="$1"
fi

HOST=`grep db_ip /etc/ossim/ossim_setup.conf | cut -f 2 -d "="`
if test -z "$HOST"
then
HOST=localhost
fi

mysql -u`grep user /etc/ossim/ossim_setup.conf | cut -f 2 -d "="` -h$HOST  -p`grep pass /etc/ossim/ossim_setup.conf | cut -f 2 -d "="` $DB
