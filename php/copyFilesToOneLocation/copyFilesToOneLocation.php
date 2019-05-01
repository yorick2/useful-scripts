<?php
/**
 * Created by PhpStorm.
 * User: Paul
 * Date: 09/05/16
 * Time: 16:06
 *
 *
 * a script to get all files from a folder recursively and copy those files into another folder and will rename files adding a no. to the end if required. It is limited to 1000 files with the same original name
 * It gives a comma separated output with source file location and destination filename. This can be output into a file running the below from shell
 * php -f copyFilesToOneLocation.php > fileMapping.csv
 *
 */
class copyFiles {
    /**
     * @param string $folderName
     * @param array $includedExtensions
     * @param array $excludedExtensions
     * @return array
     */
    function findFiles($folderName, $includedExtensions = [],  $excludedExtensions = [], $excludedFileNames = [] )
    {
        $fileNamesArray = [];
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folderName)) as $file) {
            $filename = $file->getFileName();

            // filter out "." and ".."
            if ($file->isDir()) {
                continue;
            }elseif( in_array($file->getExtension(),$excludedExtensions) || in_array($file->getFileName(),$excludedFileNames) ){
                continue;
            }elseif( sizeof($includedExtensions) == 0 || in_array($file->getExtension(),$includedExtensions) ){
                array_push($fileNamesArray, $file);
            }
        }
        return $fileNamesArray;
    }

    /**
     * @param string $fileName
     * @param string $destinationFolder
     * @return string
     */
    function findFreeFileName($fileName, $destinationFolder)
    {
        $i = 0;
        $file = pathinfo($fileName);

        if( strlen($file['filename']) === 0 ){
            $file['filename'] = '.' . $file['extension'];
            unset($file['extension']);
        }

        while ($i < 1000) {
            if ($i === 0) {
                $newName = $fileName;
            } else {
                if ( isset($file['extension']) && strlen($file['extension']) > 0 ){
                    $newName = $file['filename'] . $i . '.' . $file['extension'] ;
                }else{
                    $newName = $file['filename'] . $i;
                }
            }
            if ( !file_exists($destinationFolder.'/'.$newName) ) {
                return $newName;
            }
            $i++;
        }
    }

    /**
     * @param string $sourceFile
     * @param string $destinationFolder
     * @return mixed|string
     */
    function getFileDestinationName($sourceFile, $destinationFolder)
    {
        $fileName = str_replace(' ', '_', basename($sourceFile));
        $fileName = $this->findFreeFileName($fileName, $destinationFolder);
        return $fileName;
    }

    /**
     * @param string $sourceFile
     * @param string $destinationFolder
     * @return string
     */
    function copyFile($sourceFile, $destinationFolder)
    {
        $fileName = $this->getFileDestinationName($sourceFile, $destinationFolder);
        copy($sourceFile, $destinationFolder . '/' . $fileName);
        return $fileName;
    }

    /***
     * @param string $sourceFolder
     * @param string $destinationFolder
     * @param array $excludedExtensions
     * @param array $includedExtensions
     * @param array $excludedFileNames
     */
    function run($sourceFolder, $destinationFolder, $includedExtensions = [], $excludedExtensions = [], $excludedFileNames = [] )
    {
        $files = $this->findFiles($sourceFolder,$includedExtensions,$excludedExtensions,$excludedFileNames);
        echo "source file, destination file\n";
        for ($i = 0; $i < sizeof($files); $i++) {
            $destinationFileName = $this->copyFile($files[$i]->getPathName(), $destinationFolder);
            echo $files[$i]->getPathName() . ',' . $destinationFileName . "\n";
        }
    }
}

$sourceFolder = '/Users/Paul/Google Drive/projects/Project - Graftons/Graftons - Shared Documents/CMS Images and Text/B2B';
$destinationFolder = '/Users/Paul/Documents/Repositories/graftons-new/htdocs/media/banners';
$copyFiles = new copyFiles;
$copyFiles->run($sourceFolder,$destinationFolder, $includedExtensions = [], $excludedExtensions = [], $excludedFileNames = ['.gitignore','.DS_Store','Thumbs.db',"Icon\r"] );
