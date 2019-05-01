# useful-scripts
## sql bash

### import all sql files in a folder
[sqlBash/batchImportDatabasesFromFolder.sh](../master/sqlBash/batchImportDatabasesFromFolder.sh)


### uncompress and import all compressed sql database files in a folder with a given extension
[sqlBash/batchImportCompressedDatabasesFromFolder.sh](../master/sqlBash/batchImportCompressedDatabasesFromFolder.sh)


### dump all databases from a mysql connection
[sqlBash/downloadAllDatabases.sh](../master/sqlBash/downloadAllDatabases.sh)


### dump all databases using a cron
[sqlBash/downloadAllDatabasesViaCron.sh](../master/sqlBash/downloadAllDatabasesViaCron.sh)


### drop all database tables for a given atabase
[sqlBash/drop_all_tables.sh](../master/sqlBash/drop_all_tables.sh)


### dump a magento 1 database without using n98-magerun
[sqlBash/magentoDbDumpWithoutN98.sh](../master/sqlBash/magentoDbDumpWithoutN98.sh)


### export databases in a list to current machine using an ssh tunnel 
[sqlBash/streamAllDatabasesFromListToHere.sh](../master/sqlBash/streamAllDatabasesFromListToHere.sh)


### export all databases from a server to the current machine using an ssh tunnel
[sqlBash/streamAllDatabasesHere.sh](../master/sqlBash/streamAllDatabasesHere.sh)


### transfer a database from one magento install (remote) to another installation (local)
[sqlBash/updateMagentoDatabase.sh](../master/sqlBash/updateMagentoDatabase.sh)

Transfer a database from one magento install (remote) to another installation (local)

This drops the tables in the current local database. So can be used on a cron to keep to db's in sync, but only if the local db's changes are not required to be kept.

## Bash
### remove xml comments
[bash/remove_xml_comments.xml](../master/bash/ remove_xml_comments.xml)

remove comments from xml files in a folder


### question loop
[bash/questionLoop.sh](../master/bash/questionLoop.sh)

an example of a question which will ask again unless an acceptable response given


### run through a list of file to copy and a list of files to symlink
[bash/moveFilesForRocketeer.sh](../master/bash/moveFilesForRocketeer.sh)

run though a ist of files to copy and files to symlink


### convert xml comments into valid ones 
[bash/make_xml_valid.sh](../master/bash/make_xml_valid.sh)

stop varien (or similar frameworks) throwing xml validator warnings from comments, by converting <!--< into <!-- and >--> into --!> in all xml files from the folder it is in.
e.g.
turns all <!-- <blah> text </blah> -->
into <!--blah> text </bah-->



## Php

### Link list of files from folder
[php/linkListFilesFromFolder.php](../master/php/linkListFilesFromFolder.php)

Creates a list of links to files from a folder of html/php/... files.


### check images in a csv exists 
[php/checkCsvImagesExist.php](../master/php/checkCsvImagesExist.php)

run from command line

echo's out a table of images not found

advised column: sku

accepted image columns: 'image','small_image','thumbnail','media_gallery'


### copy files from all subfolders of a folder into a new folder
[php/copyFilesToOneLocation.php](../master/php/copyFilesToOneLocation.php)

A script to get all files from a folder recursively and copy those files into another folder and will rename files adding a no. to the end if required. It is limited to 1000 files with the same original name.

It gives a comma separated output with source file location and destination filename. This can be output into a file running the below from shell

php -f copyFilesToOneLocation.php > fileMapping.csv


### compare two site maps, to help identify missing/renamed pages for seo
[php/site-map-comparison.php](../master/php/site-map-comparison.php)

#### Instructions
make a copy of the two sitemaps and name them 'newSitemap.xml' & 'oldSitemap.xml', in the same folder as the file

run php compareSiteMaps.php

it will output to the screen
