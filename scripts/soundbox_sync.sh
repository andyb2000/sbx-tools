#!/bin/bash

# Sync up soundbox folders, using rsync cos its clever

CHECK_MOUNTS=`cat /etc/mtab |grep soundbox`
        if [ "x$CHECK_MOUNTS" = "x" ]; then
		mount /mnt/soundbox
                sleep 3
        fi
/usr/bin/rsync -axz --delete /mnt/soundbox/Data/ /var/www/sbschedule/local_soundbox/
touch /var/www/sbschedule/local_soundbox/touchfile.txt
# umount /mnt/soundbox
