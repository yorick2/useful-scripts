#!/usr/bin/env bash

#######################################################################
####### warning this empties the database folder before it runs #######
#######################################################################

success=''; dot_sql_failure=''; dot_tar_failure=''; 

user='root'
password='root'
host='localhost'
databaseFolderLocation='databases'

clearDatabaseFolder=true

databases=$(mysql -h${host} -u${user} -p${password} -e"show databases;" | grep -v Database);

if [[ $str != /* ]]; then
   scriptDir="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
   databaseFolderLocation="${scriptDir}/${databaseFolderLocation}";
fi

echo "current directory: $(pwd)"
echo "database folder location: ${databaseFolderLocation}"


if ls ${databaseFolderLocation}/*.sql.tar.gz 1> /dev/null 2>&1 &&
[ "${clearDatabaseFolder}" = true ] ; then
  rm ${databaseFolderLocation}/*.sql.tar.gz
fi

for db in ${databases}; do \
  if [ "${db}" != "information_schema" ] && 
  [ "${db}" != "mysql" ] && 
     [ "${db}" != "performance_schema" ] && 
     [ "${db}" != "phpmyadmin" ] && 
  [ "${db}" != _* ] ; then 

    echo "---- exporting ${db} -----" ; 
    filename="${db}-$(date +%Y-%m-%d).sql"
    if [ -e  "${db}.sql" ] ; then 
      echo "ERROR: ${filename} already exists. Skipping export of database ${db}" ; 
      dot_sql_failure="${dot_sql_failure} ${db}" ; 
    else 
      echo "dumping database ${db} into ${filename}";
      mysqldump  -h${host} -u${user} -p${password} --force ${db} > ${databaseFolderLocation}/${filename} ; 
      if [ -e  "${db}.tar.gz" ] ; then 
        echo "ERROR: ${filename}.tar.gz already exists. Skipping compressing ${filename} for database ${db}" ; 
        dot_tar_failure="${dot_tar_failure} ${db}" ; 
      else 
        echo "compressing db into ${filename}.tar.gz" ; 
        tar --remove-files -czf ${databaseFolderLocation}/${filename}.tar.gz --directory ${databaseFolderLocation} ${filename} ; 
        success="${success} ${db}" ; 
      fi 
    fi 
    echo
   # break # for testing
  fi
done

# clear the variables for security
password='';
user='';
host='';
databaseFolderLocation='';

echo ; 
echo '-----------------------' ;
echo '       finished' ; 
echo '-----------------------' ; 
echo 'please read the report below'; 
if [ "${dot_sql_failure}" != '' ]; then 
  echo ; 
  echo '--- export failed ---' ; 
  echo ' unable to export to an sql file with its name, it already exists'
  echo ${dot_sql_failure}; 
fi ; 
if [ "${dot_tar_failure}" != '' ]; then 
  echo ; 
  echo '--- exported but not compressed ---' ; 
  echo 'Unable to compress into tar.gz files as these exists with the database name. Please find the .sql files availible for you to compress manually';
  echo ${dot_tar_failure}; 
fi; 
if [ "${success}" != '' ]; then 
  echo ; 
  echo '--- sucessfully exported and compressed ---' ; 
  echo 'Please find these in here'; 
  echo ${success}; 
fi; 
echo ; 
echo 'Please look back any errors.' ; 
echo 'Have a nice day (•‿•)' ; 
echo ;

