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
$EmailAddress = filter_var($EmailAddress,FILTER_SANITIZE_EMAIL);

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
if(!empty($EmailAddress)){
    
    $EmailAddressVerify = filter_var($EmailAddress,FILTER_VALIDATE_EMAIL);
    
    if($EmailAddressVerify){
        
        $EmailVerificationHash = md5($EmailAddress);
        
    }
    else{
        array_push($validation,"Email is Not valid");
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
    
    
    
    $CreationPrivateQuery ="INSERT INTO userprivatedetails (FirstName,LastName,ClassID,PasswordHash,EmailAddress) VALUES ('$FirstName','$LastName','$ClassCode','$PasswordHashed','$EmailAddress')";
    $PrivateQueryExecution = mysqli_query($ConnectionFunction,$CreationPrivateQuery) or die(mysqli_error($ConnectionFunction));
    echo $PrivateQueryExecution;
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
        echo $UserIDToInsert;
    }
    }
    
    $CreationPublicQuery = "INSERT INTO userdetails (UserID,UserName,EmailVerificationHash,ClassID) VALUES ('$UserIDToInsert','$UserName','$EmailVerificationHash','$ClassCode')";
    $PublicQueryExecution= mysqli_query($ConnectionFunction,$CreationPublicQuery) or die(mysqli_error($ConnectionFunction));
    if($PublicQueryExecution){
        //echo "  Public Query Success  ";
        require ("C:\Users\hpd12\Desktop\MonoclGitHubRepo\MonoclBackend\EmailVerification.php");
        if(SendEmail($EmailAddress,$EmailVerificationHash)){
            echo "Verification EMail has been sent";
        }      
        else{
            echo "Email failed";
        }
    }
    else{
        //echo "  Public Query Failure    ";
    }
    break;
}
}
}
?>
