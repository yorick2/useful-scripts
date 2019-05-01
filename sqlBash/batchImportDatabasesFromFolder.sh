echo;echo; \
echo mysql user; \
read user ; \
echo mysql password ; \
read password ; \
for file in *.sql ; do \
# *.sql is the first thing it returns so reject that.
  if [ "$file" != "*.sql" ]; then \
    echo '----- '$file' -------'; \
    dbname="${file%.*}"
    dbname=$(echo "$dbname"| sed 's/[^a-zA-Z0-9]/_/g')
    echo creating database ${file%.*} ; \
    mysql -u$user -p$password -e"create database ${file%.*}" && \
    echo importing $file into database ${file%.*} && \
    mysql -u$user -p$password ${file%.*} < $file ; \
  else \
    echo  "----> ERROR: no .sql database files found <----"; \
  fi; \
  echo;
done ; \
password='';user='';
echo '------ 'finished' ------'
echo 'Please look back to see if there were any for any errors' ;
echo;
