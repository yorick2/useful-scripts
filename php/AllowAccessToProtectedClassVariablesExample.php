<?php


namespace paulmillband\cachedImageResizer;

use function PHPUnit\Framework\throwException;

class Image
{
    protected string $filePath;
    protected string $url;
    protected array $accessibleProtectedVariables = [
            'filePath',
            'url'
    ];

    public function __construct($filePath)
    {
            $this->setFilePath($filePath);
    }

    /**
     * @param $name
     * @param $value
     * @throws \Exception
     */
    public function __set($name, $value)
    {
        if (!in_array($name,$this->accessibleProtectedVariables)){
            throw new \Exception("access to variable '${name}' denied");
        }
        $functionName = 'set'.ucfirst($name);
        if(is_callable(array($this, $functionName))){
            $this->$functionName($value);
            return;
        }
        $this->$name = $value;
    }

    /**
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        if (!in_array($name,$this->accessibleProtectedVariables)){
            throw new \Exception("access to variable '${name}' denied");
        }
        $functionName = 'get'.ucfirst($name);
        if(is_callable(array($this, $functionName))){
            return $this->$functionName();
        }
        return $this->$name;
    }

    /**
     * @param string $location
     */
    public function setFilePath($imageFilePath)
    {
        if(!file_exists($imageFilePath)){
            throw new \Exception('file not found');
        }
        switch (exif_imagetype($imageFilePath)){
            case IMAGETYPE_JPEG:
                break;
            case IMAGETYPE_PNG:
            case IMAGETYPE_GIF:
            case IMAGETYPE_WEBP:
                throw new \Exception('file not an allowed file type');
                break;
            default:
                throw new \Exception('file not an allowed file type');
        }
        $this->filePath = $imageFilePath;
    }

}
