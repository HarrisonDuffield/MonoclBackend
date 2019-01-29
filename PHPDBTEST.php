<html>
<?php
session_start(); 
$servername = "localhost:3306";
$account = "PHPConnection2";
$dbname = "monoclmain";
$password="PHPPassword12";

$validation=array();

        
$ConnectionFunction = mysqli_connect($servername,$account, $password);

$ConnectionTesting = mysqli_get_server_info($ConncetionFunction);

echo $ConnectionTesting;
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
if(isset($_POST['SignUpButtonGreen'])){

$UserName = mysqli_real_escape_string($ConnectionFunction,$_POST['UserName']);

$FirstName = mysqli_real_escape_string($ConnectionFunction,$_POST['FirstName']);
$LastName = mysqli_real_escape_string($ConnectionFunction,$_POST['LastName']);
$EmailAddress= mysqli_real_escape_string($ConnectionFunction,$_POST['Email']);
$ClassCode= mysqli_real_escape_string($ConnectionFunction,$_POST['ClassCode']);
$PasswordOriginal = mysqli_real_escape_string($ConnectionFunction,$_POST['PwdInput']);
$PasswordConfirmation = mysqli_real_escape_string($ConnectionFunction,$_POST['PwdConfirmation']);
if(empty($UserName)){
    array_push($validation,"Empty username");
}
if(empty($LastName)){
    array_push($validation,"Empty LastName");
}
if(empty($FirstName)){
    array_push($validation,"Empty FirstName");
}
if(empty($EmailAddress)){
    array_push($validation,"Empty Email");
}
if(empty($PasswordOriginal)){
    array_push($validation,"Password Field is empty");
}
if($PasswordOriginal != $PasswordConfirmation){
    array_push($validation,"Passwords don't match");
}
$UserNameCheck = "SELECT * FROM userdetails WHERE (UserName) = 'Admin' LIMIT 1";
$EmailCheck = "SELECT * FROM userprivatedetails WHERE EmailAddress = '$EmailAddress' LIMIT 1";
$UserNameCheckResult = mysqli_query($ConnectionFunction, $UserNameCheck);
if($UserNameCheckResult==False){
    array_push($validation,"Failure to see if username already there");
    
}
else{
    foreach($UserNameCheckResult as $row){
        echo $row['UserName'];
    }
}

if(empty($ClassCode)){
    $ClassCode=404;
}

if(count($validation)==0){
    echo "count is 0";
    $CreationPublicQuery = "INSERT INTO userdetails (UserName,ClassID) VALUES ($UserName,$ClassCode)";
    $CreationPrivateQuery ="INSERT INTO userprivatedetails (FirstName,LastName,ClassID,PasswordHash,EmailAddress) VALUES ($FirstName,$LastName,$ClassCode,$PasswordOriginal,$EmailAddress)";
    $result = mysqli_query($ConnectionFunction,$CreationPublicQuery);
    $result2 = mysqli_query($ConnectionFunction,$CreationPrivateQuery);
    if($result){
        echo "Query Success";
    }
    if(!$result){
        echo "Query Failure";
    }
    if($result2){
        echo "Query Success";
    }
    if(!$result2){
        echo "Query Failure";
    }
}
}

?>

</body>
</html>
