<html>
    <body>
        <a> test</a>
<?php
$servername = "localhost";
$username = "PHPConnection2";
$dbname = "monoclmain";

$ConnectionFunction = mysqli_connect($servername, $username, $password);
if(!$ConnectionFunction){
    die("Connection Failed");
}
echo "Console Message : Connection Succeeded";
$UserName = mysqli_real_escape_string($ConnectionFunction,$_POST['UserName']);        
$FirstName = mysqli_real_escape_string($ConnectionFunction,$_POST['FirstName']);
$LastName = mysqli_real_escape_string($ConnectionFunction,$_POST['LastName']);
$EmailAddress= mysqli_real_escape_string($ConnectionFunction,$_POST['Email']);
$ClassCode= mysqli_real_escape_string($ConnectionFunction,$_POST['ClassCode']);
$PasswordOriginal = $_POST['PwdInput'];
$PasswordConfirmation = $_POST['PwdConfirmation'];
echo $UserName;
$querypublicdetails = "INSERT INTO userdetails (UserName,ClassID) VALUES ($UserName,$ClassCode)";
$queryprivatedetails ="INSERT INTO userprivatedetails (FirstName,LastName,ClassID,PasswordHash,EmailAdress) VALUES ($FirstName,$LastName,$EmailAddress,$ClassCode,$PasswordOriginal)";
        
$test = mysqli_query($ConnectionFunction,$querypublicdetails);
if($test){
    echo "success";
}
      
?>
</body>
</html>
