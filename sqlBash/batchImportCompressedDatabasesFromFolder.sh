echo;echo; \
echo mysql user; \
read user ; \
echo mysql password ; \
read password ; \
echo 'please select a file extension (sql/tar.bz2/tar.gz/zip)'; \
read filetype ; \
if [ "$filetype" == "tar.gz" ]; then \
  cat *.tar.gz | tar -xzvf - -i ; \
elif [ "$filetype" == "tar.bz2" ]; then \
   cat *.tar.bz2 | tar -xjvf - -i ; \
elif [ "$filetype" == "zip" ]; then \
  unzip *.zip; \
elif [ "$filetype" != "sql" ]; then \
  echo filetype not recognised; \
  exit; \
fi ; \
\
for file in *.sql ; do \
  if [ "$file" != "*.sql" ]; then \
    echo '----- '$file' -------'; \
   echo creating database ${file%.*} ; \
   mysql -u$user -p$password -e"create database ${file%.*}" && \
   echo importing $file into database ${file%.*} && \
   mysql -u$user -p$password ${file%.*} < $file ; \
  else \
    echo  "----> ERROR: no .sql database files found <----"; \
  fi; \
  echo;
done ; \
password='';user=''; \
echo '------ 'finished' ------' ; \
echo 'Please look back to see if there were any for any errors' ; \
echo ;