#!/bin/bash
/usr/bin/php /var/www/html/cron/abc.php > /var/www/html/cron/output1.log 2>&1 && /usr/bin/php /var/www/html/cron/def.php > /var/www/html/cron/output2.log 2>&1 
