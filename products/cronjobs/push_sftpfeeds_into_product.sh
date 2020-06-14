#!/bin/bash
cd /warranty/html/products/cronjobs/ && /usr/sbin/php cron.php > /warranty/html/products/cronjobs/logs/process_sftp_feeds_`date +\%y\%m\%d`.log 2>&1
