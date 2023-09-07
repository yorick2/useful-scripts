<?php

namespace App\Models\Helper;

use http\Encoding\Stream;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilePathHelper extends Model
{
    use HasFactory;

    /**
     * @param string $filepath
     * @return mixed
     */
    static function getExtensionFromFilepath(string$filepath){
        preg_match("/\.[a-zA-Z]*$/", $filepath, $matches);
        return $matches[0];
    }

    /**
     * @param string $filepath
     * @return string
     */
    static function getFilepathWithoutExtension(string$filepath){
        return preg_replace("/\.[a-zA-Z]*$/", '', $filepath);
    }

    /**
     * @param string $filepath
     * @return string|string[]|null
     */
    static function getFileNameFromPath(string $filepath){
        return preg_replace("/^.*\//", '', $filepath);
    }

    static function sanitiseFilename(string $filename){
        return str_replace('/','_',self::sanitiseFilePath($filename));
    }

    static function sanitiseFilePath(string $filename){
        return preg_replace('/[^\/0-9a-zA-Z_\-.]/','_',$filename);
    }
}
