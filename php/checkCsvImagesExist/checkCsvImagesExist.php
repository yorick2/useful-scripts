<?php
/**
 * Created by PhpStorm.
 * User: Paul
 * Date: 26/01/16
 * Time: 10:56
 */

namespace PaulMillband;


class checkCsvImagesExist {

    // deconstruct csv
    //
    //--------------------
    function deconstructCsv($fileName,$delimiter=",",$header=[]){
        if ( ($handle = fopen($fileName, 'r') ) !== FALSE)
        {
            $x=0;
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
            {
                //remove white space
                array_map('trim', $header);
                if(!$header){
                    $header = $row;
                }else{
                    $data[$x-1] = array_combine($header, $row);
                }
                $x++;
            }
            fclose($handle);
        }
        return [$header,$data];
    }

    // check if can read csv
    //
    //--------------------
    function canReadCsv($fileName){
        if(!file_exists($fileName)){
            echo "error:- $fileName file not found";
            return false;
        }elseif(!is_readable($fileName)){
            echo "error:- $fileName file not readable";
            return false;
        }else{
            return true;
        }
    }


    // run csv image checks
    // requires the image locations be treated as relative even if starts with /
    //
    //--------------------
    function run($fileName,$delimiter=",",$header=[],$imageHeadings=[],$forceRelativePaths=false){
        if( empty($imageHeadings) ){
            $imageHeadings=[
                'image',
                'small_image',
                'thumbnail',
                'media_gallery'
            ];
        }

        list($header,$csvData) = $this->deconstructCsv($fileName,$delimiter,$header);

        foreach($imageHeadings as $key => $heading) {
            if (!in_array($heading, $header)) {
                unset($imageHeadings[$key]);
            }
        }

        echo "Images not found \n";
        echo "sku,column,image \n";
        foreach($csvData as $csvRow) {
            foreach($imageHeadings as $heading){
                $sku = ( isset($csvRow['sku']) ) ? $csvRow['sku'] : '';
                $imageLocations = explode(',',$csvRow[$heading]);
                foreach($imageLocations as $imageFile) {
                    if( strpos($imageFile,'/') === 0 && $forceRelativePaths ){
                        $imageFile = '.'.$imageFile;
                    }
                    if( !empty($imageFile) && !file_exists($imageFile)) {
                        echo "$sku, $heading, $imageFile \n";
                    }
                }
            }
        }
    }

}

/*
// for setting variables in script
$fileName = '';
$delimiter = ",";
$header = [];
$forceRelativePaths=false;
*/


/* command line
 * --all-paths-relative sets all file paths to relative even if they start with a /
 */
$options = getopt("r", ["all-paths-relative"]);
if( isset($options['r']) || isset($options['all-paths-relative']) ){
    $forceRelativePaths = true;
    unset($argv[1]);
    $argv = array_values($argv);
}else{
    $forceRelativePaths = false;
}

if ( count($argv) <= 1 && empty($fileName)){
    echo "\n arguments missing: \n php checkCsvImagesExist.php <<<path/to/file.csv>> \n or \n php checkCsvImagesExist.php <<<path/to/file.csv>> <<<delimiter>>> \n";
    echo "\n Options List: \n  -r   --all-paths-relative  forces relative paths to be used even for file paths starting with a /";
    echo "\n hint: \n  To create an output csv run \n 'php checkCsvImagesExist.php path/to/file.csv > outputFileName.csv' \n \n";
    exit;
}

if( isset($argv[1]) ) {
    $fileName = $argv[1];
}
if( isset($argv[2]) ){
    $delimiter = $argv[2];
}

$checkCsvImagesExist = new checkCsvImagesExist();
$checkCsvImagesExist->run($fileName,$delimiter=",",$header=[],$imageHeadings=[],$forceRelativePaths);