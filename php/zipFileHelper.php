<?php

namespace App\Models\Helper;

use ZipArchive;

class ZipFileHelper 
{

    /**
     * @param string $zipFileLocation
     * @return array
     */
    static function getZippedFilesListArray(string $zipFileLocation){
        $output = [];
        $zip = new ZipArchive;
        $zip->open($zipFileLocation);
        for( $i = 0; $i < $zip->numFiles; $i++ ){
            $stat = $zip->statIndex( $i );
            array_push($output, $stat['name']);
        }
        return $output;
    }

    /**
     * @param string $zipFileLocation
     * @param string $extension
     * @return array
     */
    static function getZippedFilesWithExtensionListArray(string $zipFileLocation, string $extension){
        $zippedFiles = ZipFileHelper::getZippedFilesListArray($zipFileLocation);
        $filesWithExtensionArray = [];
        for($i=0; $i<count($zippedFiles); $i++){
            if(FilePathHelper::getExtensionFromFilepath($zippedFiles[$i]) == '.'.$extension){
                array_push($filesWithExtensionArray, $zippedFiles[$i]);
            }
        }
        return $filesWithExtensionArray;
    }

    /**
     * @param string $zipFileLocation
     * @param string $destination
     * @param array $filesToExtract
     * @return bool
     */
    static function openZipFile(string $zipFileLocation, string $destination, array $filesToExtract = []){
        $zip = new ZipArchive;
        if(!count($filesToExtract)){
            $filesToExtract = null;
        }
        $res = $zip->open($zipFileLocation);
        if ($res === TRUE) {
            $zip->extractTo($destination, $filesToExtract);
            $zip->close();
            return true;
        } else {
            return false;
        }
    }

}
