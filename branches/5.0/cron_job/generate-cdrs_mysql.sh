#!/bin/sh


HOSTNAME="localhost"
USER="opensips"
PASS="opensips_pass"
DATABASE="opensips"

mysql -h $HOSTNAME -u $USER -p$PASS -e "call opensips_cdrs_1_6(); " $DATABASE

