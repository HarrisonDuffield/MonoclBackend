<html>
<?php
session_start(); 
$servername = "localhost:3306";
$account = "PHPConnection2";
$dbname = "monoclmain";
$password="PHPPassword12";

$validation=array();

        
$ConnectionFunction = mysqli_connect($servername,$account, $password);
if(!$ConnectionFunction){
    die("Connection Failed");
    }
    
echo "Console Message : Connection Succeeded";
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
    echo "Email empty";
    array_push($validation,"Empty Email");
}
if($PasswordOriginal != $PasswordConfirmation){
    array_push($validation,"Passwords dont match");
}
echo $UserName;
echo $ClassCode;
$querypublicdetails = "INSERT INTO userdetails (UserName,ClassID) VALUES ($UserName,$ClassCode)";
$queryprivatedetails ="INSERT INTO userprivatedetails (FirstName,LastName,ClassID,PasswordHash,EmailAdress) VALUES ($FirstName,$LastName,$ClassCode,$PasswordOriginal,$EmailAddress)";
        
$result = mysqli_query($ConnectionFunction,$queryprivatedetails);
if($result){
    echo "Query Success";
}
if(!$result){
    echo "Query Failure";
}
}
echo "\n"
. "Console Message : Sign up not clicked";
?>
        <a> Test</a>  
</body>
</html>
