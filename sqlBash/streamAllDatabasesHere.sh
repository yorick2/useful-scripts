

success=''; dot_sql_failure=''; dot_tar_failure=''; \
echo; \
echo 'Please note files are created in this folder and it is advised to be empty.'
echo; \
echo ssh username;
read sshuser;
echo ssh host;
read host;
echo mysql user; \
read user ; \
echo; \
echo mysql password ; \
read -s password ; \
echo; \
echo; \
databases=$(ssh ${sshuser}@${host} "mysql -u$user -p${password} -e'show databases;' | grep -v Database"); \
for db in $databases; do \
echo $db
  if [ "$db" != "information_schema" ] && \
  [ "$db" != "mysql" ] && \
     [ "$db" != "performance_schema" ] && \
     [ "$db" != "phpmyadmin" ] && \
  [ "$db" != _* ] ; then \

    echo "---- exporting $db -----" ; \
    if [ -e  "$db.sql" ] ; then \
      echo "ERROR: ${db}.sql already exists. Skipping export of database $db" ; \
      dot_sql_failure=$dot_sql_failure" $db" ; \
      else \
      echo "dumping database $db into ${db}.sql";\
      echo "      mysqldump -u${user} -p${password}  --force $db > $db.sql";\
      ssh ${sshuser}@${host} "mysqldump -u${user} -p${password} --force --quick $db" > $db.sql ; \
      if [ -e  "$db.tar.gz" ] ; then \
        echo "ERROR: ${db}.tar.gz already exists. Skipping compressing ${db}.sql for database $db" ; \
        dot_tar_failure=$dot_tar_failure" $db" ; \
      else \
        echo "compressing db into ${db}.tar.gz" ; \
        tar --remove-files -czf ${db}.tar.gz ${db}.sql ; \
        success=$success" $db" ; \
       fi ; \
    fi ; \
    echo; \
  fi; \
done; \
password='';user=''; \
echo ; \
echo '-----------------------' ; \
echo '       finished' ; \
echo '-----------------------' ; \
echo 'please read the report below'; \
if [ "$dot_sql_failure" != '' ]; then \
  echo ; \
  echo '--- export failed ---' ; \
  echo ' unable to export to an sql file with its name, it already exists'
  echo $dot_sql_failure; \
fi ; \
if [ "$dot_tar_failure" != '' ]; then \
  echo ; \
  echo '--- exported but not compressed ---' ; \
  echo 'Unable to compress into tar.gz files as these exists with the database name. Please find the .sql files availible for you to compress manually'; \
  echo $dot_tar_failure; \
fi; \
if [ "$success" != '' ]; then \
  echo ; \
  echo '--- sucessfully exported and compressed ---' ; \
  echo 'Please find these in here'; \
  echo $success; \
fi; \
echo ; \
echo 'Please look back any errors.' ; \
echo 'Have a nice day (•‿•)' ; \
echo ;