dbArray=(dbname1 dbname2 dbname3 );
user=root
host=example.com
for db in ${dbArray[@]}; do
	echo ------ $db ------ ;
	ssh ${user}@${host} "nice -n -15 mysqldump   -uroot  -ppassword ${db} --quick"  > ./${db}.sql
done;