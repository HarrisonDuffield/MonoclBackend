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
function DualArrayInsertionSort($UserNameArray,$UserPointsArray){
    for($i =1;$i<sizeof($UserPointsArray);$i++){
        $ItemToCheck =$UserPointsArray[$i];
        $NameToCheck = $UserNameArray[$i];
        $tempcount = $i-1;
        while($tempcount >=0 && $UserPointsArray[$tempcount] > $ItemToCheck){
            $UserPointsArray[$tempcount+1]=$UserPointsArray[$tempcount];
            $UserNameArray[$tempcount+1]=$UserNameArray[$tempcount];
            $tempcount = $tempcount-1;                    
        }
        $UserPointsArray[$tempcount+1] = $ItemToCheck;
        $UserNameArray[$tempcount+1]=$NameToCheck;
    }
    return array($UserNameArray,$UserPointsArray);
}


?>