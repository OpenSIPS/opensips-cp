#!/bin/sh


HOSTNAME="localhost"
USER="opensips"
PASS="opensips_pass"
DATABASE="opensips"

psql -h $HOSTNAME -U $USER "call opensips_cdrs_1_6(); " -d $DATABASE

