#!/bin/bash
CRON_FILE="/tmp/mycron"
CRON_JOB="*/5 * * * * /usr/bin/php $(pwd)/cron.php"
crontab -l > $CRON_FILE 2>/dev/null
grep -qxF "$CRON_JOB" $CRON_FILE || echo "$CRON_JOB" >> $CRON_FILE
crontab $CRON_FILE
echo "CRON job installed to run every 5 minutes."
rm $CRON_FILE
