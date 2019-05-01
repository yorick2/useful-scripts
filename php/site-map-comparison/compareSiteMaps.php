<?php
/***
 * compares two site maps, to help identify missing/renamed pages for seo
 * 
 * --- instructions --
 * make a copy of the two sitemaps and name them 'newSitemap.xml' & 'oldSitemap.xml', in the same folder as the file
* run php compareSiteMaps.php
* it will output to the screen  
 * 
 */
 
function getFilePath($fileName){
    $importFolder = __DIR__ ;
    return $importFolder.'/'.$fileName;
}

function getFileContents($filePath){
    if( file_exists($filePath) ){
        if( is_readable($filePath) ){
            return file_get_contents($filePath);
        }else{
            error_log('cannot read file: '.$filePath);
            return false;
        }
    }else{
        error_log("file doesn't exist: ".$filePath);
        return false;
    }
}

function getContents($sitemaps){
    foreach (array_keys($sitemaps) as $key) {
        $fileName = $sitemaps[$key]['location'];
        if(!$fileName){
            continue;
        }
        $filePath = getFilePath($fileName);
        $content = getFileContents($filePath);
        if($content) {
           $sitemaps[$key]['content'] = $content;
        }else{
            die('File read Error: See error log for more details');
        }
    }
    return $sitemaps;
}


/*
* $whereAttr array of required attribute value pairs  for desired node e.g. [ 'attr'=>'value', 'attr2'=>'value2' ]
*/
function extractFromXmlFile( $xml, $node, $nodeAttribute='', $return_data = [], $whereAttr=[] ){
    $empty = true;
    $reader = new XMLReader();
    $reader->xml($xml);
    while ($reader->read()) {
        if ($reader->nodeType == XMLReader::ELEMENT) {
            $exp = $reader->expand();
            if ($exp->nodeName == $node){
                if(count($whereAttr)>0){
                    $continue = false;
                    foreach ( $whereAttr as $attr => $val ){
                        if( $exp->getAttribute($attr) == $val ){
                            $continue = true;
                        }
                    }
                }else{
                    $continue = true;
                }
                if( $continue ){
                    if( $nodeAttribute === ''){
                        array_push( $return_data, $exp->nodeValue );
                    }else{
                        array_push( $return_data, $exp->getAttribute($nodeAttribute) );
                    }
                    $empty = false;
                }
            }
        }
    }
    return [ $return_data, $empty];
}

function removeBaseUrl($url){
    $url = str_replace(['http://','https://'],'',$url);
    list($removed,$url) = explode('/',$url,2);
    return $url;
}

function extractUrlsFromXml($sitemaps){
    foreach (array_keys($sitemaps) as $key) {
        $xml = $sitemaps[$key]['content'];
        $node = 'loc';
        list($sitemaps[$key]['full_urls'], $empty) = extractFromXmlFile($xml, $node);
        $urlsQuantity = sizeof( $sitemaps[$key]['full_urls'] );
        for( $i=0; $i<$urlsQuantity ; $i++ ){
            $sitemaps[$key]['urls'][$i] = removeBaseUrl( $sitemaps[$key]['full_urls'][$i] );
        }
    }
    return $sitemaps;
}

function applyRewrites($sitemaps){
    $urlsQuantity = sizeof( $sitemaps['old']['urls'] );
    for( $i=0; $i<$urlsQuantity ; $i++ ){
        $url = $sitemaps['old']['urls'][$i];

        $ltrim = preg_quote($sitemaps['rewriteRule']['ltrim']);
        $url = preg_filter('^'.$ltrim, '', $url);

        $rtrim = preg_quote($sitemaps['rewriteRule']['rtrim']);
        $url = preg_filter('$'.$ltrim, '', $url);

        $url = $sitemaps['rewriteRule']['prepend'] . $url . $sitemaps['rewriteRule']['append'];
    }
    return $sitemaps;
}

function compareSiteMaps($sitemaps){
    $sitemaps = getContents($sitemaps);
    $sitemaps = extractUrlsFromXml($sitemaps);
    $sitemaps = applyRewrites($sitemaps);
    $urlsMissingFromNew = array_diff($sitemaps['old']['urls'], $sitemaps['new']['urls'] );
    return $urlsMissingFromNew;
}





$sitemaps = [
    'new' =>[
        'location' => 'newSitemap.xml'
    ],
    'old' =>[
        'location' => 'oldSitemap.xml'
    ],
    'rewriteRule' =>[
        'append' => '',
        'prepend' => '',
        'ltrim' => '',
        'rtrim' => ''
    ]
];
$comparison = compareSiteMaps($sitemaps);

echo 'These urls dont match'.PHP_EOL;
$urlsQuantity = sizeof( $comparison );
for( $i=0; $i<$urlsQuantity ; $i++ ) {
    echo $comparison[$i] . PHP_EOL;
}
