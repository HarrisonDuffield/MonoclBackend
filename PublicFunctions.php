<?php
function InsertionSort(&$BaseArray){
    $SortedArray = array();
    for($i =1;$i<sizeof($BaseArray);$i++){
        $ItemToCheck =$BaseArray[$i];
        $tempcount = $i-1;
        while($tempcount >=0 && $BaseArray[$tempcount] > $ItemToCheck){
            $BaseArray[$tempcount+1]=$BaseArray[$tempcount];
            $tempcount = $tempcount-1;                    
        }
        $BaseArray[$tempcount+1] = $ItemToCheck;
    }
    return $BaseArray;
}


?>