#!/bin/sh


HOSTNAME="localhost"
USER="opensips"
PASS="opensips_pass"
DATABASE="opensips_1_4"

mysql -h $HOSTNAME -u $USER -p$PASS -e "call opensips_cdrs_1_4(); " $DATABASE

