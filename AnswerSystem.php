<?php
require(MonoclBackend\MySQLConnectionFile.php);
$ConnectionFunction=ConnectionReturn();
//functiontosee if answer correct

echo $AnswerGiven;

function IsAnswerCorrect($AnswerGiven,$QuestionID){
//checks if it is equal to the sacred string
//checks if the signifanct value reached
if(IsAnswerPreferedText($TextToCheck,$QuestionID)){
    PointsAward(100);
    return true;
}
else{
    if(IsAnswerAboveSignifValue($AnswerGiven)){
        PointsAward(50);
        return true;
    }
    else{
        return false;
    }

}
}
function IsAnswerPreferedText($AnswerGiven,$QuestionID){
    $PreferredTextQuery = "SELECT PreferredAnswer FROM questiontable WHERE QuestionID = $QuestionID";
    $PreferredTextExecution = mysqli_query(ConnectionReturn(),$PreferredTextQuery);
    if($PreferredTextExecution){
        foreach($PreferredTextExecution as $row){
            $itemtocheck = $row['PreferredAnswer'];
        }
    }
    if($itemtocheck == $AnswerGiven){
        return true;
    }
    else{
            return false;
    }
}
function addAnswer($CorrectOrNot){
    
}
function IsAnswerAboveSignifValue($TextToCheck,$QuestionID){
    $ConnectionFunctionPrimary = ConnectionReturn();
    $RetrievalOfSignifValue = "SELECT SignificantValue FROM questiontable WHERE QuestionID = $QuestionID";
    $SignificantValue =0;
    $CountSoFar=0;
    $SignificantValueExecution = mysqli_query($ConnectionFunctionPrimary,$RetrievalOfSignifValue);
    foreach($SignificantValueExecution as $row){
        $SignificantValue = $row["SignifcantValue"];
    }
    $ConnectionFunctionSecondary = ConnectionReturnSecondaryTable();
    $TextStringArray = preg_split("/[^A-Za-z0-9]/", $TextToCheck);
    $SecondaryTableQuery = "SELECT * FROM `$QuestionID`";
    $SecondaryTableExecution = mysqli_query($ConnectionFunctionSecondary,$SecondaryTableQuery);
    if($SecondaryTableExecution){
        foreach($SecondaryTableExecution as $row){
            $NumberAssinged = $row["Percentage"];
            $itemselected = $row["MainWord"];
            if($NumberAssigned >= $SignificantValue){
                if(in_array($itemselected,$TextStringArray)){
                    if($SecondaryTableExecution["PreviousWord"]=="NULL"){
                        if($SecondaryTableExecution["FollowingWord"]=="NULL"){
                            //one word answer 
                            return true;
                        }
                        else{
                            //do nothing
                        }
                    }
                    else{
                        // do ntihing
                    }
                    //good
                }
                else{
                    //item required not present retrun false not good;
                    return false;
                }
            }
            else{
                if(in_array($itemselected.$TextStringArray)){
                    $CountSoFar = $CountSoFar +$NumberAssigned;
                    //do nothing
                }
                else{
                    //do nothing
                }
            }
        }
        $CountSoFar = $CountSoFar/count($TextStringArray);
        if($CountSoFar > $SignificantValue){
            return true;
        }
        else{
            return false;
        }
    
}
//points award method as common function in mysqlfile

}
?>