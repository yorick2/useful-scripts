/**
 * @param string $filePath
 * @param string $fileType
 */
static function downloadFileToLaptop(string $filePath, string $fileType=''){
    $fileName= FilePathHelper::getFileNameFromPath($filePath);
    if(empty($fileName) || !file_exists($filePath)) {
        die("Error: File not found");
    }
    if(strlen($fileType)){
        header("Content-Type: application/$fileType");
    }
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=$fileName");
    header("Content-Transfer-Encoding: binary");
    readfile($filePath);
}
