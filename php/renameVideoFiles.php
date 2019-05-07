<?php

/**
 * Class renameVideoFiles
 * Rename video files to the date/time recorded based on meta data in the modd files. Adjust the protected variables
 * for your requirements. Please use on copies of your files, for safety and not the originals. As misconfiguration will
 * cause the data files to be not named the same as the video files and reverting will be difficult
 */
class renameVideoFiles
{
    /**
     * @var string
     * 'video/*' would be all sub-folders in teh video folder
     */
    protected $fileFolder = 'video/*';

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
     */
    protected $filenamePrefix = 'canoeing-';

    /**
     * @var string
     */
    protected $dateFormat = 'Y-m-d-H:i:s';

    /**
     * @var string
     * set as false here to rename and not move to the destination folder
     */
    protected $destinationFolder = 'video/collate';

    /**
     * @var bool
     * keep the folder structure inside the destination folder?
     */
    protected $keepFolderStructure = false;


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
        if($i>100){
            throw new Exception('getNewName error too many iterations');
        }
        $folder = $this->getDestinationFolder($filename);
        if(!file_exists($folder)){
            mkdir($folder);
        }
        if($i==0) {
            $newFileLocation = "{$folder}/{$this->filenamePrefix}{$formattedDate}.{$this->fileExtension}";
        }else{
            $newFileLocation = "{$folder}/{$this->filenamePrefix}{$formattedDate}-{$i}.{$this->fileExtension}";
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