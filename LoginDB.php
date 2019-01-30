<html>
<?php
session_start(); 
$servername = "localhost:3306";
$account = "PHPConnection2";
$dbname = "monoclmain";
$password="PHPPassword12";
$ConnectionFunction = mysqli_connect($servername, $account, $password, $dbname);
$validation = array();
echo "Test 1";

$UserName = "";
$Password = "";
echo "Test 2";
if(isset($_POST['LoginButtonGreen'])){
$UserName = mysqli_real_escape_string($ConnectionFunction,$_POST['UserName']);
$Password = mysqli_real_escape_string($ConnectionFunction,$_POST["Password"]);
$UserIDRetreivalQuery ="SELECT UserID FROM userdetails WHERE(UserName)='$UserName' LIMIT 1";
$UserIDRetreival=mysqli_query($ConnectionFunction,$UserIDRetreivalQuery);

if($UserIDRetreival==False){
    array_push($validation, "Error Technical : Failure to access DB");
    echo "test 3";
}
else{
    foreach($UserIDRetreival as $test){
        $UserID=$test['UserID'];
        echo "test 4";
}
}
$PasswordHashRetreivalQuery="SELECT PasswordHash FROM userprivatedetails WHERE(UserID)= '$UserID' LIMIT 1";
$PasswordHashRetreival=mysqli_query($ConnectionFunction,$PasswordHashRetreivalQuery);

$EmailVerificationRetreivalQuery ="SELECT AccountVerified FROM userdetails WHERE(UserName) = '$UserName' LIMIT1";
$EmailVerificationRetreival=mysqli_query($ConnectionFunction,$EmailVerificationRetreivalQuery);
$HashedEntryPassword=password_hash($Password,PASSWORD_DEFAULT);
array_push($validation,"Tehcni");
if($PasswordHashRetreival==False){
   array_push($validation,"Error Technical : Failure to access DB");
    
}
else{
    foreach($PasswordHashRetreival as $row){
        echo $row['PasswordHash'];
        if ($row['PasswordHash'] == $HashedEntryPassword){
            array_push($validation,"Passwords match");
            //Password  verification complete;
            break;
        }
        else{
            array_push($validation,"false");
            unset($_POST['SignUpButtonGreen']);
            array_push($validation,"Password's don't match");
            break;
        }
    }
}
}
?>
</html>