<?php
function ConnectionReturn(){
$servername = "localhost:3306";
$account = "PHPConnection2";
$dbname = "monoclmain";
$password="PHPPassword12";

    $ConnectionFunction = mysqli_connect($servername, $account, $password, $dbname);
    return $ConnectionFunction;
}
function ConnectionReturnSecondaryTable(){
    $servername = "localhost:3306";
    $account = "PHPConnection2";
$dbname = "monoclquestionansers";
$password="PHPPassword12";

    $ConnectionFunction = mysqli_connect($servername, $account, $password, $dbname);
    return $ConnectionFunction;
}
function PointsAward($AmountToAward,$UserID){
    $CurrentPoints =0;
    $PointRetrievalQuery = "SELECT UserPoints FROM userdetails WHERE UserID = $UserID";
    $PointRetrievalExecution = mysqli_query(ConnectionReturn(),$PointsRetrievalQuery);
    if($PointRetrievalExecution){
        foreach($PointRetrievalExecution as $row){
            $CurrentPoints = $row["UserPoints"];
        }
        $PointsToSet = $CurrentPoints +$AmountToAward;
        $PointAmmedmentQuery="UPDATE userdetails SET UserPoints = $PointsToSet WHERE UserID = $UserID";
        $PointAmmendmentExecution = mysqli_query(ConnectionReturn(),$PointAmmedmentQuery);
        if($PointAmmendmentExecution){
            return true;

        }
        else{
            return false;
        }

    }


    else{
        return false;
    }
    

}
?>