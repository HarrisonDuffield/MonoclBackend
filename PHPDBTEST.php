<html>
<link href="Assets/HomePageAssets.css" rel="stylesheet" type="text/css">
<link href="Assets/FontAssets.css" rel="stylesheet" type="text/css">
<link href="Assets/CommonAssets.css" rel="stylesheet" type="text/css">
<div id="MonocleHeaderBar">
    <body>
        <a> Registration Complete</a>
<?php
$servername = "localhost";
$account = "PHPConnection2";
$dbname = "monoclmain";
$password="PHPPassword12";


$UserName =$_POST['UserName'];
$FirstName = $_POST['FirstName'];
$LastName = $_POST['LastName'];
$EmailAddress = $_POST['Email'];
$ClassCode = $_POST['ClassCode'];

        
$ConnectionFunction = mysqli_connect($servername,$account, $password);
if(!$ConnectionFunction){
    die("Connection Failed");
    }
echo "Console Message : Connection Succeeded";
$UserName = mysqli_real_escape_string($ConnectionFunction,$UserName);        
$FirstName = mysqli_real_escape_string($ConnectionFunction,$FirstName);
$LastName = mysqli_real_escape_string($ConnectionFunction,$LastName);
$EmailAddress= mysqli_real_escape_string($ConnectionFunction,$Email);
$ClassCode= mysqli_real_escape_string($ConnectionFunction,$ClassCode);
$PasswordOriginal = $_POST['PwdInput'];
$PasswordConfirmation = $_POST['PwdConfirmation'];
$querypublicdetails = "INSERT INTO userdetails (UserName,ClassID) VALUES ($UserName,$ClassCode)";
$queryprivatedetails ="INSERT INTO userprivatedetails (FirstName,LastName,ClassID,PasswordHash,EmailAdress) VALUES ($FirstName,$LastName,$ClassCode,$PasswordOrignial,$EmailAddressl)";
        
$result = mysqli_query($ConnectionFunction,$queryprivatedetails);
if($result){
    echo "success";
}
mysqli_close($ConnectionFunction);
?>
        <a> Test</a>  
</body>
</html>
