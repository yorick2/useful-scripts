#!/bin/bash

# transfer a database from one magento install (remote) to another installation (local)
# can be used on a cron to keep to db's in sync, but only if the local db's changes are not required to be kept

#### settings ####
remoteUrl=""
remoteUser=""
remoteMagentoLocation="/var/www/example/htdocs/" #full path required
remoteN98Location=""

localDatabaseUser=''
localDatabasePass=''
localDatabaseName=''
localUrl=''
localMagentoLocation='/home/example/public_html/'
remoteN98Location=""

#### file location ####
fileRef="${remoteUrl}-magento"
date=`date +%Y-%m-%d`;
fileName="${fileRef}--${date}.sql"
fileLocation="/tmp/${fileName}"

#### get database ####
sshReply=$( ssh ${remoteUser}@${remoteUrl} "cd ${remoteMagentoLocation};
  ${remoteN98Location}n98-magerun.phar db:dump --strip='@stripped' ${fileLocation};" )

rsync -ahz ${remoteUser}@${remoteUrl}:${fileLocation} ${fileLocation}

ssh ${remoteUser}@${remoteUrl} "rm ${fileLocation}"

###### drop all tables #####
# Detect paths
MYSQL=$(which mysql)
AWK=$(which awk)
GREP=$(which grep)
loop="true"
while [ ${loop} == "true" ] ; do
	TABLES=$($MYSQL -u${localDatabaseUser} -p${localDatabasePass} ${localDatabaseName} -e 'show tables' | $AWK '{ print $1}' | $GREP -v '^Tables' )
	if [ -z ${TABLES} ] ; then
		loop="false"
	fi
	for t in $TABLES ; do
		echo "Deleting $t table from ${localDatabaseName} database..."
		${MYSQL} -u${localDatabaseUser} -p${localDatabasePass} ${localDatabaseName} -e "drop table $t"
	done
done


##### import and update db #####
${MYSQL} -u${localDatabaseUser} -p${localDatabasePass} ${localDatabaseName} < ${fileLocation}
table='core_config_data' 
cmd="update ${localDatabaseName}.${table} set value='http://${localUrl}/' where path='web/unsecure/base_url';"
${MYSQL} -u${localDatabaseUser} -p${localDatabasePass} -e"${cmd}"
cmd="update ${localDatabaseName}.${table} set value='http://${localUrl}/' where path='web/secure/base_url';"
${MYSQL} -u${localDatabaseUser} -p${localDatabasePass} -e"${cmd}"

# ##### check for n98 #####
n98Reply=$(${remoteN98Location}n98-magerun.phar)
n98Location=""
if [ -z "${n98Reply}" ] ; then
    remoteN98Location="/tmp/"
    if [ ! -x ${n98Location}n98-magerun.phar ] ; then
        echo "attempting to install n98"
        wget http://files.magerun.net/n98-magerun-latest.phar -O ${n98Location}n98-magerun.phar &&
        chmod +x ${n98Location}n98-magerun.phar &&
        echo "installed n98 successfully"
    fi
fi

# ##### flush cache #####
cd ${localMagentoLocation}
${remoteN98Location}n98-magerun.phar cache:flush
#${remoteN98Location}n98-magerun.phar index:reindex:all
