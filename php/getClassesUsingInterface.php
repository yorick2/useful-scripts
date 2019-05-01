<?php

namespace example;

class classFinder
{
    function run()
     {
        $interfaceName = 'example\matchRules\matchRule';
        $searchLocation = __DIR__.'/matchRules';
        $namespaceForFolder= 'example\matchRules';
        $rules = $this->whoImplements($interfaceName, $searchLocation, $namespaceForFolder);
     }

    /**
     * get an array of classes from a given folder, 
     * who a implementing a given interface
     * This assumes psr4 is followed .ie the names are the same as their class
     * @param string $interfaceName
     * @param string $searchLocation
     * @param string $namespace
     * @return array
     */
    function whoImplements($interfaceName, $searchLocation, $namespace = '')
    {
        $classes = $this->getClassesFromFolder(
            $searchLocation,
            $namespace
        );
        $implementsIModule = array();
        for ($i=0; $i<sizeof($classes); $i++) {
                $reflect = new \ReflectionClass("$classes[$i]");
            if($reflect->implementsInterface($interfaceName)){
                $implementsIModule[] = $classes[$i];
            }
        }
        return $implementsIModule;
    }

    /**
     * get the classes of all files in a folder.
     * This assumes psr4 is followed .ie the names are the same as their class
     * @param string $searchLocation
     * @param string $namespace
     * @return array
     */
    function getClassesFromFolder($searchLocation, $namespace)
    {
        $classes = [];
        $files = array_diff(scandir($searchLocation), array('..', '.'));
        $files = array_values($files);
        for ($i=0; $i<sizeof($files) ; $i++){
            $classes[] = $namespace.'\\'.basename($files[$i], '.php');
        }
        return $classes;
     }
}
