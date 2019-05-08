<?php

/**
 * Class renameVideoFiles
 * Rename video files to the date/time recorded based on meta data in the modd files. Adjust the protected variables
 * for your requirements. Please use on copies of your files, for safety and not the originals. As misconfiguration will
 * cause the data files to be not named the same as the video files and reverting will be difficult.
 *
 * $filenamePrefix can be changed to add a prefix to the names of the renamed files
 *
 * This can move the files into a destination folder and/or keep the current folder structure if the relevant class
 * variables are set.
 *
 * If the $destinationFolder is not set then the files are not moved but renamed.
 *
 * If the $destinationFolder is set but $keepFolderStructure is false then all files are moved into the
 * $destinationFolder.
 *
 * If $keepFolderStructure is true then the relative path to the original file from the $fileFolder path is used and the
 * moved to that location relative to the destination path. e.g. if $fileFolder='video', $keepFolderStructure=true,
 * $destinationFolder='collection' and the original file is video/folder/folderTwo/test.mpg then the destination
 * will be collection/folder/folderTwo/test.mpg
 *
 */
class renameVideoFiles
{
    /**
     * @var string
     * 'video/*' would be all sub-folders in teh video folder
     */
    protected $fileFolder = './*';

    /**
     * @var string
     */
    protected $fileExtension = 'mpg';

    /**
     * @var string
     */
    protected $dataFileExtension = "modd";

    /**
     * @var string
     * This string goes before the date in the name of the new file
     * e.g. 'example-' would give a filename of example-2009-03-20-15:06:00.mpg
     */
    protected $filenamePrefix = '';

    /**
     * @var string
     * This will give a format of 2009-03-20-15:06:00
     */
    protected $dateFormat = 'Y-m-d-H:i:s';

    /**
     * @var string
     * set as false here to rename and not move to the destination folder
     */
    protected $destinationFolder = '';

    /**
     * @var bool
     * keep the folder structure inside the destination folder? e.g. if the file was in video/folder/folderTwo/test.mpg
     * then the file would be copied to $this->>destination/folder/folderTwo/test.mpg if $fileFolder is 'video'
     */
    protected $keepFolderStructure = false;

    /**
     * @var bool
     * include the original name at the end of the file
     */
    protected $includeOriginalName = true;

    public function execute(){
        foreach (glob($this->fileFolder.'/*.'.$this->fileExtension) as $filename) {
            if (!file_exists($filename)) {
                continue;
            }
            $timestamp = $this->getTimestamp($filename);
            if(!$timestamp){
                continue;
            }
            $formattedDate = $this->getDateFromXmlTimestamp($timestamp);
            rename($filename, $this->getNewName($filename, $formattedDate));
        }
    }

    /**
     * @param string $filename
     * @return string
     */
    protected function getDestinationFolder($filename){
        if(!$this->destinationFolder){
            return preg_replace("/\/[^\/]*$/", '', $filename );
        }
        if($this->keepFolderStructure){
            return $this->destinationFolder.preg_replace("/^[^\/]*|[^\/]*$/", '', $filename );
        }
        return $this->destinationFolder;
    }

    /**
     * @param string $filename
     * @param string $formattedDate
     * @param int $i
     * @return string
     * @throws Exception
     */
    protected function getNewName($filename, $formattedDate, $i = 0){
        $suffix = '';
        if($i>100){
            throw new Exception('getNewName error too many iterations');
        }
        $folder = $this->getDestinationFolder($filename);
        if(!file_exists($folder)){
            mkdir($folder);
        }
        if($this->includeOriginalName){
            $suffix = '---'.preg_replace("/.*\/|\.[^.\/]*$/", '', $filename ).'---';
        }
        if($i==0) {
            $newFileLocation = "{$folder}/{$this->filenamePrefix}{$formattedDate}{$suffix}.{$this->fileExtension}";
        }else{
            $newFileLocation = "{$folder}/{$this->filenamePrefix}{$formattedDate}{$suffix}-{$i}.{$this->fileExtension}";
        }
        if(file_exists($newFileLocation)){
            $newFileLocation = $this->getNewName($filename, $formattedDate, $i+1);
        }
        return $newFileLocation;
    }


    /**
     * @param string $filename
     * @return bool|string
     */
    protected function getTimestamp($filename){
        $info = pathinfo($filename);
        $xmlFile = $info['dirname'].'/'.$info['filename'] . '.' . $this->dataFileExtension;
        $xmlContent = file_get_contents($xmlFile);
        if($xmlContent === false){
            return false;
        }
        if(!preg_match('/<key>DateTimeOriginal<\/key>\s*<real>([\d\.]*)<\/real>/' , $xmlContent, $matches)){
            return false;
        }
        return $matches[1];
    }

    /**
     * @param string $timestamp
     * @return string
     * @throws Exception
     */
    protected function getDateFromXmlTimestamp($timestamp){
        $date2 = new DateTime('1900-01-01');
        $hours = $timestamp * 24;
        $min = fmod($hours, 1) * 60; # dont use % for modulus as that only works for integers
        $date2->modify('+'.floor($hours).' hours');
        $date2->modify('+'.floor($min).' minutes');
        return $date2->format($this->dateFormat);
    }
}

$renameVideoFiles = new renameVideoFiles();
$renameVideoFiles->execute();
