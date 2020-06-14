#!/bin/bash
cd /warranty/html/mh/cronjobs/ && /usr/sbin/php cronkiado.php > /warranty/html/mh/cronjobs/logs/push_kiado_data_`date +\%y\%m\%d`.log 2>&1
