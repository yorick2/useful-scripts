<?php
/*
* get array of all filter combinations for multiple options of a filter
*
*/

// array of filter options
$x = range(0,10,1);

$y1 = [];
$y2 = [];
$y3 = [];
for( $i = 0 ; $i<=sizeof($x); $i++){
    array_push($y1 , $x[$i] );
    if( $i <= sizeof($x)-2 ){
        for( $j = $i+1 ; $j<= sizeof($x); $j++){
            array_push($y2, $x[$i] . $x[$j] );
            if( $j <= sizeof($x)-1 ) {
                for ($z = $j + 1; $z <= sizeof($x); $z++){
                    array_push($y3, $x[$i] . $x[$j] . $x[$z] );
                }
            }
        }
    }
}
$y = array_merge($y1,$y2,$y3);
var_dump($y);
