#!/bin/sh
PATH=/root/.local/bin/:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/games:/usr/local/games
watcher=`ls $1`
src="$1"
for i in $watcher
do
  echo '>> Transfering $src$i to s3://franchising-system'
  aws s3 mv $src$i s3://franchising-system
 # sleep 40
done

