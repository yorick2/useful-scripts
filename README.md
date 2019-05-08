# useful-scripts
## Sql Bash

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

This drops the tables in the current local database. So can be used on a cron to keep to db's in sync, but only if the 
local db's changes are not required to be kept.

## Bash
### remove xml comments
[bash/remove_xml_comments.sh](../master/bash/remove_xml_comments.sh)

remove comments from xml files in a folder


### question loop
[bash/questionLoop.sh](../master/bash/questionLoop.sh)

an example of a question which will ask again unless an acceptable response given


### run through a list of file to copy and a list of files to symlink
[bash/moveFilesForRocketeer.sh](../master/bash/moveFilesForRocketeer.sh)

run though a ist of files to copy and files to symlink


### convert xml comments into valid ones 
[bash/make_xml_valid.sh](../master/bash/make_xml_valid.sh)

stop varien (or similar frameworks) throwing xml validator warnings from comments, by converting <!--< into <!-- 
and >--> into --!> in all xml files from the folder it is in.
e.g.
turns all <!-- <blah> text </blah> -->
into <!--blah> text </bah-->



## Php
### check images in a csv exists 
[php/checkCsvImagesExist.php](../master/php/checkCsvImagesExist.php)

run from command line

echo's out a table of images not found

advised column: sku

accepted image columns: 'image','small_image','thumbnail','media_gallery'


### copy files from all subfolders of a folder into a new folder
[php/copyFilesToOneLocation.php](../master/php/copyFilesToOneLocation.php)

A script to get all files from a folder recursively and copy those files into another folder and will rename files
adding a no. to the end if required. It is limited to 1000 files with the same original name.

It gives a comma separated output with source file location and destination filename. This can be output into a file
running the below from shell

```php -f copyFilesToOneLocation.php > fileMapping.csv```


### Look for images not found in a csv when provided with a folder of images.
[php/csvImageFileExists.php](../master/php/csvImageFileExists.php)

It works with multiple image column, including columns like 'additional_images' where the cell can have multiple images 
split by a delimiter.
 
$fields should be an array where the key is the column name and the "cell delimiter" mentioned above as the value.

e.g. the below would have a cell delimiter of , and file delimiter of ;
```
sku;website_id;additional_images;additional_image_labels
123a;1;blue.jpg,red.jpg;blue,red
124a;1;blue.jpg,red.jpg;blue,red
```

and would have:
```
$fileDelimiter = ';';
$hasHeader = true;
$fields = [
'additional_images' => ',' // field name => row delimiter (empty string for none)
];
 ```


### compare two site maps, to help identify missing/renamed pages for seo
[php/compareSiteMaps.php](../master/php/compareSiteMaps.php)

Make a copy of the two sitemaps and name them 'newSitemap.xml' & 'oldSitemap.xml', in the same folder as the file

run php compareSiteMaps.php

it will output to the screen


### get an array of classes from a given folder that implement an interface
[php/getClassesUsingInterface.php](../master/php/getClassesUsingInterface.php)
This assumes psr4 is followed .ie the names are the same as their class


### Link list of files from folder
[php/linkListFilesFromFolder.php](../master/php/linkListFilesFromFolder.php)

Creates a list of links to files from a folder of html/php/... files.


### get array of all filter combinations for multiple options of a filter
[php/multiOptionFilter.php](../master/php/multiOptionFilter.php)


### Rename video files to the date/time recorded based on meta data in the modd files.
[php/renameVideoFilesFromXmlData.php](../master/php/renameVideoFilesFromXmlData.php)

Rename video files to the date/time recorded based on meta data in the modd files. Adjust the protected variables
for your requirements. Please use on copies of your files, for safety and not the originals. As misconfiguration will
cause the data files to be not named the same as the video files and reverting will be difficult.

$filenamePrefix can be changed to add a prefix to the names of the renamed files

$includeOriginalName can be set to true to include the original name at the end of the filename

This can move the files into a destination folder and/or keep the current folder structure if the relevant class
variables are set.

If the $destinationFolder is not set then the files are not moved but renamed.

If the $destinationFolder is set but $keepFolderStructure is false then all files are moved into the
$destinationFolder. 

If $keepFolderStructure is true then the relative path to the original file from the $fileFolder path is used and the
moved to that location relative to the destination path. e.g. if $fileFolder='video', $keepFolderStructure=true,
$destinationFolder='collection' and the original file is video/folder/folderTwo/test.mpg then the destination
will be collection/folder/folderTwo/test.mpg
