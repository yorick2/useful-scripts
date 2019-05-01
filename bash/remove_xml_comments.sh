#!/bin/bash

for file in $(find . -type f -name '*.xml')
do
	echo "removing xml comments from $file";
	gawk 'BEGIN { RS = "<!--"; FS = "-->"; ORS=""; OFS="" } { if ( NR > 1 ) $1=""; print }' $file > $file.no_comments;
	#cp $file $file.orig
	diff=$(diff -q  $file $file.no_comments);
	diff_result=$? # Store previous command's result i.e. 0 = no diff 1 = difference
	if [ $diff_result  -ne 0 ];
	then
		echo "updating $file" ;
		mv $file.no_comments $file ;
	else
		rm $file.no_comments;
	fi;
done
