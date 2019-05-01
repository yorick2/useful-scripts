<?php
/**
 *  look for images not found in a csv when provided with a folder of images.
 *  It works with multiple image column, including columns like 'additional_images' where the cell
 * can have multiple images split by a delimiter.
 *
 * $fields should be an array where the key is the column name and the "cell delimiter" mentioned above as the value.
 *
 * e.g. the below would have a cell delimiter of , and file delimiter of ;
 *
 * sku;website_id;additional_images;additional_image_labels
 * 123a;1;blue.jpg,red.jpg;blue,red
 * 124a;1;blue.jpg,red.jpg;blue,red
 *
 * and would have:
 * $fileDelimiter = ';';
 * $hasHeader = true;
 * $fields = [
 * 'additional_images' => ',' // field name => row delimiter (empty string for none)
 * ];
 *
 *
 */

/**
 * Class CsvImageFileExists
 */
class CsvImageFileExists
{
    private $filename;
    private $delimiter;
    private $hasHeader;
    private $fields;
    private $csvData;
    private $imageFolder;

    /**
     * CsvImageFileExists constructor.
     * @param $filename string
     * @param $delimiter string
     * @param $hasHeader boolean
     * @param $fields array
     * @param $imageFolder string
     */
    public function __construct($filename, $delimiter, $hasHeader, $fields, $imageFolder)
    {
        $this->imageFolder = $imageFolder;
        $this->filename = $filename;
        $this->delimiter = $delimiter;
        $this->hasHeader = $hasHeader;
        $this->fields = $fields;
    }

    public function execute()
    {
        $csvImporter = new csvImporter($this->filename, $this->hasHeader, $this->delimiter, 0);
        $this->csvData = $csvImporter->get();
        for ($i=0; $i<count($this->csvData); $i++) {
            $this->findMissingImages($this->csvData[$i]);
        }
    }

    /**
     * @param $row array
     */
    protected function findMissingImages($row)
    {
        foreach ($this->fields as $fieldName => $cellDelimiter) {
            $images = $this->getImagesFromCellText($row[$fieldName], $cellDelimiter);
            $this->loopThroughImages($images);
        }
    }

    /**
     * @param $images array
     */
    protected function loopThroughImages($images)
    {
        for ($i=0; $i<count($images); $i++) {
            if (!$this->findImage($images[$i])) {
                $this->missingImageAction($images[$i]);
            }
        }
    }

    /**
     * @param $cellText
     * @param $cellDelimiter
     * @return array
     */
    protected function getImagesFromCellText($cellText, $cellDelimiter)
    {
        if ($cellDelimiter===null) {
            return [$cellText];
        }
        if (!strlen($cellDelimiter)) {
            return [$cellText];
        }
        return explode($cellDelimiter, $cellText);
    }

    /**
     * @param $imageName
     * @return bool
     */
    protected function findImage($imageName)
    {
        if (file_exists($this->imageFolder.'/'.$imageName)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $imageName string
     */
    protected function missingImageAction($imageName)
    {
        echo $imageName.PHP_EOL;
    }
}

/**
 * Class CsvImporter
 */
class CsvImporter
{
    private $fp;
    private $parse_header;
    private $header;
    private $delimiter;
    private $length;

    /**
     * CsvImporter constructor.
     * @param $file_name
     * @param bool $parseHeader
     * @param string $delimiter
     * @param int $length
     */
    public function __construct($file_name, $parseHeader = false, $delimiter = ",", $length = 8000)
    {
        $this->fp = fopen($file_name, "r");
        $this->parse_header = $parseHeader;
        $this->delimiter = $delimiter;
        $this->length = $length;

        if ($this->parse_header) {
            $this->header = fgetcsv($this->fp, $this->length, $this->delimiter);
        }
    }

    public function __destruct()
    {
        if ($this->fp) {
            fclose($this->fp);
        }
    }

    /**
     * @param int $max_lines
     * @return array
     */
    public function get($max_lines = 0)
    {
        //if $max_lines is set to 0, then get all the data
        $data = array();

        if ($max_lines > 0) {
            $line_count = 0;
        } else {
            $line_count = -1; // so loop limit is ignored
        }

        while ($line_count < $max_lines
            && ($row = fgetcsv($this->fp, $this->length, $this->delimiter)) !== false
        ) {
            if ($this->parse_header) {
                $row_new = [];
                foreach ($this->header as $i => $heading_i) {
                    $row_new[$heading_i] = $row[$i];
                }
                $data[] = $row_new;
            } else {
                $data[] = $row;
            }

            if ($max_lines > 0) {
                $line_count++;
            }
        }
        return $data;
    }
}

$filename  = 'images.csv';
$fileDelimiter = ';';
$hasHeader = true;
$fields = [
    'additional_images' => ',' // field name => row delimiter (empty string for none)
];
$imageFolderLocation = 'images';
$csvImageFileExists = new CsvImageFileExists($filename, $fileDelimiter, $hasHeader, $fields, $imageFolderLocation);
$csvImageFileExists->execute();
