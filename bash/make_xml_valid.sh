#!/bin/bash

# -- stop varien throwing xml validator warnings from comments --
#turns all <!-- <blah> text </blah> -->
# into
# <!--blah> text </bah-->

for file in $(find . -type f -name '*.xml')
do
	echo "editing xml comments from $file";
	sed -i 's/<!-- *</<!--/g' $file
	sed -i 's/> *-->/-->/g' $file
done
