<?php
session_start();

$servername = "localhost:3306";
$account = "PHPConnection2";
$dbname = "monoclmain";
$password="PHPPassword12";

$validation=array();

        
$ConnectionFunction = mysqli_connect($servername, $account, $password, $dbname);

if(!$ConnectionFunction){
    die("Connection Failed");
    
    }
   
    $QuestionText = "";
    $Language ="";
    $Language="";
$Topic="";
$AnswerText ="";

while(isset($_POST['SignUpButtonGreen'])&& (count($validation)<1)){
$QuestionText = mysqli_real_escape_string($ConnectionFunction,$_POST['QuestionText']);
$Language = mysqli_real_escape_string($ConnectionFunction,$_POST['Language']);
$Topic = mysqli_real_escape_string($ConnectionFunction,$_POST['Topic']);
$AnswerText = mysqli_real_escape_string($ConnectionFunction,$_POST["AnswerGiven"]);
$TeacherStatus = 0;
$SignificantValue =100;//deafult
$GeneralAvailibility = 0;
$UserID = $_SESSION["UserLoggedIn"];
$IsUserTeacherQuery = "Select UserID from teachertable";
$IsUserTeacherExecution = mysqli_query($ConnectionFunction,$IsUserTeacherQuery);
if($IsUserTeacherExecution){
foreach($IsUserTeacherExecution as $row){
    if($row["UserID"]==$UserID){
        $TeacherStatus = 1;
        $GeneralAvailibility = 1;
    }
    else{
        // do mothing
    }
}
}
else{
    array_push($validation,mysqli_error($ConnectionFunction));
}

if(empty($QuestionText)){
    array_push($validation,"Empty QuestionText");
    unset($_POST['SignUpButtonGreen']);
}
if(empty($Language)){
    array_push($validation,"Empty Language");
    unset($_POST['SignUpButtonGreen']);
}
$LanguageCorrect = true;
if($Language != "DE"){
    if($Language!="FR"){//dould have done this by querying db and jsut getting umber of rows
        if($Language != "ES"){
            $LanguageCorrect ==false;
        }
    }
}
if(!$LanguageCorrect){
    array_push($validation,$Language);
    unset($_POST['SignUpButtonGreen']);
}
if(empty($Topic)){
    array_push($validation,"Empty Topic");
    unset($_POST['SignUpButtonGreen']);
}
$QuestionTextCheck = "SELECT * FROM questiontable WHERE (QuestionText) = '$QuestionText' LIMIT 1";
$QuestionTextCheckResult = mysqli_query($ConnectionFunction, $QuestionTextCheck);
if($QuestionTextCheckResult==False){
    echo "Error Technical : Failure to access DB";
    
}
else{
    foreach($QuestionTextCheckResult as $row){
        echo $row['QuestionText'];
        if ($row['QuestionText'] == $QuestionText){
            echo "QuestionText Matches";
            array_push($validation,"Question Already Exists - please stop");
        }
    }
}
if(count($validation)==0){     
    echo $TeacherStatus;
    $CreationQuestionQuery ="INSERT INTO questiontable (QuestionText,Language,Topic,UserID,Teacher,GeneralAvailibility,SignificantValue) VALUES ('$QuestionText','$Language','$Topic','$UserID',$TeacherStatus,$GeneralAvailibility,'$SignificantValue')";
    echo $CreationQuestionQuery;
    $PrivateQueryExecution = mysqli_query($ConnectionFunction,$CreationQuestionQuery) or die(mysqli_error($ConnectionFunction));
    
    if($PrivateQueryExecution){
        echo "  Question Query Success   ";
        $ReturnedID=mysqli_insert_id($ConnectionFunction);
        echo addAnswer($ReturnedID,$AnswerText,$UserID,$GeneralAvailibility);
        PointsAward(50,$UserID);
        echo "Question Added - ALL is good Thank You";
    }
    if(!$PrivateQueryExecution){
        echo "  Question Query Failure   ";
    }
    
      
   
}
}
function addAnswer($QuestionID,$AnswerText,$UserID,$CorrectOrNot){
    global $ConnectionFunction;
    $AnswerToAddQuery = "INSERT INTO answertable (QuestionID,AnswerText,UserID,GeneralAvailibility,PointsStatus) VALUES ('$QuestionID','$AnswerText','$UserID',$CorrectOrNot,1)";
    $AnswerAddExecution =mysqli_query($ConnectionFunction,$AnswerToAddQuery);
    if($AnswerAddExecution){
        return true;
    }
    else{
        
        echo mysqli_error($ConnectionFunction);
    }

}
function PointsAward($AmountToAward,$UserID){
    global $ConnectionFunction;
    $CurrentPoints =0;
    $PointRetrievalQuery = "SELECT UserPoints FROM userdetails WHERE UserID = $UserID";
    $PointRetrievalExecution = mysqli_query($ConnectionFunction,$PointRetrievalQuery);
    if($PointRetrievalExecution){
        foreach($PointRetrievalExecution as $row){
            $CurrentPoints = $row["UserPoints"];
        }
        $PointsToSet = $CurrentPoints +$AmountToAward;
        $PointAmmedmentQuery="UPDATE userdetails SET UserPoints = $PointsToSet WHERE UserID = $UserID";
        $PointAmmendmentExecution = mysqli_query($ConnectionFunction,$PointAmmedmentQuery);
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
