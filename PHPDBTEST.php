<html>
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
   
$UserName = "";
$FirstName ="";
$LastName="";
$EmailAddress="";
$ClassCode="";
$PasswordOriginal="";
$PasswordConfirmation="";
$UserIDToInsert="";
while(isset($_POST['SignUpButtonGreen'])&& (count($validation)<1)){
$UserName = mysqli_real_escape_string($ConnectionFunction,$_POST['UserName']);
$FirstName = mysqli_real_escape_string($ConnectionFunction,$_POST['FirstName']);
$LastName = mysqli_real_escape_string($ConnectionFunction,$_POST['LastName']);
$EmailAddress= mysqli_real_escape_string($ConnectionFunction,$_POST['Email']);
$ClassCode= mysqli_real_escape_string($ConnectionFunction,$_POST['ClassCode']);
$PasswordOriginal = mysqli_real_escape_string($ConnectionFunction,$_POST['PwdInput']);
$PasswordConfirmation = mysqli_real_escape_string($ConnectionFunction,$_POST['PwdConfirmation']);

if(empty($UserName)){
    array_push($validation,"Empty username");
    unset($_POST['SignUpButtonGreen']);
}
if(empty($LastName)){
    array_push($validation,"Empty LastName");
    unset($_POST['SignUpButtonGreen']);
}
if(empty($FirstName)){
    array_push($validation,"Empty FirstName");
    unset($_POST['SignUpButtonGreen']);
}
if(empty($EmailAddress)){
    array_push($validation,"Empty Email");
    unset($_POST['SignUpButtonGreen']);
}
if(empty($PasswordOriginal)){
    array_push($validation,"Password Field is empty");
    unset($_POST['SignUpButtonGreen']);
}
if($PasswordOriginal != $PasswordConfirmation){
    array_push($validation,"Passwords don't match");
    unset($_POST['SignUpButtonGreen']);
}
$UserNameCheck = "SELECT * FROM userdetails WHERE (UserName) = '$UserName' LIMIT 1";
$EmailCheck = "SELECT * FROM userprivatedetails WHERE EmailAddress = '$EmailAddress' LIMIT 1";
$UserNameCheckResult = mysqli_query($ConnectionFunction, $UserNameCheck);
$EmailCheckResult = mysqli_query($ConnectionFunction, $EmailCheck);
if($UserNameCheckResult==False){
    echo "Error Technical : Failure to access DB";
    
}
else{
    foreach($UserNameCheckResult as $row){
        echo $row['UserName'];
        if ($row['UserName'] == $UserName){
            echo "UserName Matches";
            array_push($validation,"User Name already taken");
        }
    }
}

if($EmailCheckResult==False){
    echo "Error Technical : Failure to access DB";
    
}
else{
    foreach($EmailCheckResult as $row){
        echo $row['EmailAddress'];
        if ($row['EmailAddress'] == $EmailAddress){
            
            array_push($validation,"Account Already Exists with this email");
        }
    }
}

if(empty($ClassCode)){
    $ClassCode=404;
}
if(count($validation)==0){
    $PasswordHashed = password_hash($PasswordOriginal,PASSWORD_DEFAULT);
    echo $PasswordHashed;
    
    
    $CreationPrivateQuery ="INSERT INTO userprivatedetails (FirstName,LastName,ClassID,PasswordHash,EmailAddress) VALUES ('$FirstName','$LastName','$ClassCode','$PasswordHashed','$EmailAddress')";
    $PrivateQueryExecution = mysqli_query($ConnectionFunction,$CreationPrivateQuery);
    
    if($PrivateQueryExecution){
        echo "  Private Query Success   ";
    }
    if(!$PrivateQueryExecution){
        echo "  Private Query Failure   ";
    }
    
    
    
    $UserIDEmailCheck = "SELECT * FROM userprivatedetails WHERE (EmailAddress) = '$EmailAddress' LIMIT 1";
    $UserIDEmailCheckResult= mysqli_query($ConnectionFunction,$UserIDEmailCheck);
    if($UserIDEmailCheckResult==False){
    echo "Error Technical : Failure to access DB";
    
    }
    else{
    foreach($UserIDEmailCheckResult as $row){
        $UserIDToInsert=$row['UserID'];
    }
    }
    
    $CreationPublicQuery = "INSERT INTO userdetails (UserID,UserName,ClassID) VALUES ('$UserIDToInsert','$UserName','$ClassCode')";
    $PublicQueryExecution= mysqli_query($ConnectionFunction,$CreationPublicQuery);
    echo $CreationPublicQuery;
    
    if($PublicQueryExecution){
        echo "  Public Query Success  ";
    }
    if(!$PublicQueryExecution){
        echo "  Public Query Failure    ";
    }
    break;
}
}


?>

</body>
</html>
