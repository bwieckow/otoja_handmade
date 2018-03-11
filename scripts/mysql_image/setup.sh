#!/bin/bash
set -e
service mysql start
mysql < /mysql/uzytkownicy.sql
service mysql stop
