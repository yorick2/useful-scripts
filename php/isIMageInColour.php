<?php

namespace App\Models\Helper;

use App\Models\Hebrew\HebrewCodex;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use League\ColorExtractor\Color;
use League\ColorExtractor\ColorExtractor;
use League\ColorExtractor\Palette;
use phpDocumentor\Reflection\Types\Object_;


class ImageHelper extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $imageLocation;

    /**
     * @var array
     */
    protected $imageSize;

    /**
     * GDImage instance
     * @var Object
     *
     */
    protected $gbImg;

    /**
     * @param string $imageLocation
     * @return bool
     */
    public function setImage(string $imageLocation)
    {
        $this->imageLocation = $imageLocation;
        $this->imageSize = getimagesize($imageLocation);
        if(!$this->imageSize) {
            return FALSE;
        }
        switch($this->imageSize['mime']) {
            case 'image/jpeg':
                $this->gbImg = imagecreatefromjpeg($imageLocation);
                break;
            case 'image/png':
                $this->gbImg = imagecreatefrompng($imageLocation);
                break;
            case 'image/gif':
                $this->gbImg = imagecreatefromgif($imageLocation);
                break;
            case 'image/webp':
                $this->gbImg = imagecreatefromwebp($imageLocation);
                break;
            default:
                return FALSE;
        }
    }

    /**
     * @param int $maxNumber
     * @param int $level
     * @return 0|array|bool
     */
    public function getColourPalette($maxNumber = 6, $level = 1) {
        $palette = array();
        for($i = 0; $i < $this->imageSize[0]; $i += $level) {
            for($j = 0; $j < $this->imageSize[1]; $j += $level) {
                $thisColor = imagecolorat($this->gbImg, $i, $j);
                $rgb = imagecolorsforindex($this->gbImg, $thisColor);
                $color = sprintf('%02X%02X%02X', (round(round(($rgb['red'] / 0x33)) * 0x33)), round(round(($rgb['green'] / 0x33)) * 0x33), round(round(($rgb['blue'] / 0x33)) * 0x33));
                $palette[$color] = isset($palette[$color]) ? ++$palette[$color] : 1;
            }
        }
        arsort($palette);
        return array_slice(array_keys($palette), 0, $maxNumber);
    }

    public function isColourImage(){
        $pallete = $this->getColourPalette();
        if(count($pallete)>1){
            return true;
        }
        return false;
    }

}
