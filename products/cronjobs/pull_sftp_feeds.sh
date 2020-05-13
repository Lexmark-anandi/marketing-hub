#!/bin/sh
sftp souravg@sftp.lexmark.com <<EOF
lcd /warranty/html/products/lxpd-feeds/files/
get -Pr /lxpd/200Grad/*.zip
quit
EOF
find /warranty/html/products/lxpd-feeds/files/ -type f -mtime +7 -exec rm -rf {} \;
