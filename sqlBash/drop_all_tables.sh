#!/bin/bash
MUSER="$1"
MDB="$2"

if [ $# -lt 2 ]
then
	echo "Usage: $0 {MySQL-User-Name} {MySQL-Database-Name}"
	echo "Usage: $0 {MySQL-User-Name} {MySQL-Database-Name} {MySQL-Database-Host}"
	echo "Drops all tables from a MySQL"
	exit 1
fi

if [  -z $3  ]
then
	MHostCmd=" "
else
	MHostCmd="-h $3"
fi 

echo 'mysql password'
read -s MPASS
 

# Detect paths
MYSQL=$(which mysql)
AWK=$(which awk)
GREP=$(which grep)

loop="true"

while [ ${loop} == "true" ] ; do
	 
	TABLES=$($MYSQL $MHostCmd -u $MUSER -p$MPASS $MDB -e 'show tables' | $AWK '{ print $1}' | $GREP -v '^Tables' )
	
	# if mysql fails or no tables
	if [ -z ${TABLES} ] ; then
		loop="false"
	fi

	for t in $TABLES
	do
		echo "Deleting $t table from $MDB database..."
		$MYSQL $MHostCmd -u $MUSER -p$MPASS $MDB -e "drop table $t"
	done

done
