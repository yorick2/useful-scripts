#!/bin/bash
oldFolder=/old/folder/location

cpfilelist=(
	'Pictures/p4pb8818375.jpg'
	'Pictures/Desktop-Background-dark-wallpaper.png'
)
for file in ${cpfilelist[@]}; do
	echo "copy file ${file} (y/n)"
	read play
	if [ "${play}" == "y" ]; then
		mkdir -p ${file%/*}
		cp ${oldFolder}/${file} ${file}
	fi
done


lnfilelist=(
	'Pictures/p4pbedfsfds8818375.jpg'
	'Pictures/wallpaper.png'
)
for file in ${lnfilelist[@]}; do
	echo "link file ${file} (y/n)"
	read play
	if [ "${play}" == "y" ]; then
		mkdir -p ${file%/*}
		ln -s ${oldFolder}/${file} ${file}
	fi
done
