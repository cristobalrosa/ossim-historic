#!/bin/bash

cd /var/ossim/logs/

ps -ef|egrep -v "$$|grep|vi|scp|cp|tar|"|grep $0
if [ $? -eq 0 ];then
	echo "There is another instance already running">/dev/stderr
	exit 10
fi

reverse_acum ( )
{
	echo received list $1
IFS=' '
	for a in $1;do
		echo "************ finding in $a"
		# now generate .csv_total_events and .total_events for the directories and subdirectories
		rm -f $a/.csv_total_events 2>/dev/null
		echo "0,0" > $a/.csv_total_events
IFS='
'		
		for i in `find $a -maxdepth 1 -mindepth 1 -type d `;do
	
			echo "Acumulating $i for $a/.csv_total_events"
			name=`echo $i|tr '/' ' ' |egrep -io " [^ ]+ *$"|tr -d ' ' `
			echo $i
	
			tot=0
			if [ -f "$i/.total_events" ];then
				tot=`cat $i/.total_events`
			else
				echo "0" > $i/.total_events
			fi

			echo $name,$tot >> $a/.csv_total_events
		done
		grep -v "0,0" $a/.csv_total_events > /tmp/$$
		mv /tmp/$$ $a/.csv_total_events
		awk -v FS="," '{a=a+$2}END{a=a+0;print a}' $a/.csv_total_events  > $a/.total_events
	done
}

update_last_hours ( )
{ 
        hour=$( perl /usr/share/ossim/scripts/sem/get_last_hour.pl "`date +"%F %T"`"|tr "-" "/"|tr -d "\n" )
      	hour2=` date +"%F %T"|tr "-" "/"|tr " " "/"|egrep -io "(/*[0-9]+)+"|head -1`

	for b in ./$hour ./$hour2;do
		if [ -d "$b" ];then
		echo "Doing last hours: $b"
		find $b -name "*.log" -type f -exec egrep '^<e' {} \; |wc -l >$b/.total_events
		else
		echo " Doing last hours: $b doesn't exist"
		fi
	done 
}


first_acum ( )
{
	# Generate first total_events (for .log files)
	echo "Generating total_events for $1/.total_events"
	test -f $1/.total_events
	if [ $? -ne 0 ];then
		find $1 -name "*.log" -type f -exec egrep '^<e' {} \; |wc -l >$1/.total_events
	fi
}

IFS='
'

for a in `find . -mindepth 4 -maxdepth 4 -type d`
do
	echo "444444444444"
	first_acum $a
done

update_last_hours

for b in `find . -mindepth 3 -maxdepth 3 -type d|tr "\n" " "`
do
	echo "33333333333"
	reverse_acum $b
done

for c in `find . -mindepth 2 -maxdepth 2 -type d|tr "\n" " "`
do
	echo "222222222222"
	reverse_acum $c
done

for d in `find . -mindepth 1 -maxdepth 1 -type d|tr "\n" " "`
do
	echo "111111111111"
	reverse_acum $d
done

for e in `find . -mindepth 0 -maxdepth 0 -type d|tr "\n" " "`
do
	echo "000000000000"
	reverse_acum $e
done


