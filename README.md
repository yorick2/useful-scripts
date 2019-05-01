# useful-scripts
## Bash
### remove xml comments
file: remove_xml_comments.xml
remove comments from xml files in a folder

### question loop
file:questionLoop.sh
an example of a question which will ask again unless an acceptable response given

### run through a list of file to copy and a list of files to symlink
file: moveFilesForRocketeer.sh
run though a ist of files to copy and files to symlink

### convert xml comments into valid ones 
file: make_xml_valid.sh
stop varien (or similar frameworks) throwing xml validator warnings from comments, by converting <!--< into <!-- and >--> into --!> in all xml files from the folder it is in.
e.g.
turns all <!-- <blah> text </blah> -->
into <!--blah> text </bah-->



## Php

### Link list of files from folder
folder: linkListFilesFromFolder
Creates a list of links to files from a folder of html/php/... files.


### check images in a csv exists 
folder: checkCsvImagesExist
run from command line
echo's out a table of images not found
advised column: sku
accepted image columns: 'image','small_image','thumbnail','media_gallery'


### copy files from all subfolders of a folder into a new folder
folder: copyFilesToOneLocation
a script to get all files from a folder recursively and copy those files into another folder and will rename files adding a no. to the end if required. It is limited to 1000 files with the same original name
It gives a comma separated output with source file location and destination filename. This can be output into a file running the below from shell
php -f copyFilesToOneLocation.php > fileMapping.csv


### compare two site maps, to help identify missing/renamed pages for seo
folder: site-map-comparison
#### Instructions
make a copy of the two sitemaps and name them 'newSitemap.xml' & 'oldSitemap.xml', in the same folder as the file
run php compareSiteMaps.php
it will output to the screen