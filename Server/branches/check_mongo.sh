#!/bin/bash
echo "*************************"
DATE=`date -d today +"%Y-%m-%d %H:%M:%S"`  
echo $DATE 
for((k=81;k<=84;k++));do
    NU=`ping -c 2 172.31.201.$k | grep icmp_seq | wc -l`
    if [ $NU -lt 1 ];then
        echo "172.31.201.$k false"
        for i in `seq 11 22`;do ssh -t 172.31.201.$i "service php-fpm reload";done
        service php-fpm reload
        ssh -t 172.31.201.123 "service php-fpm reload"
    fi
done
echo "finished"
