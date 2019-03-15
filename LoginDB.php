<html>
<?php
session_start();
$servername = "localhost:3306";
$account = "PHPConnection2";
$dbname = "monoclmain";
$password="PHPPassword12";
$ConnectionFunction = mysqli_connect($servername, $account, $password, $dbname);
$validation = array();
$UserName = "";
$Password = "";
Require("..\MonoclBackend\EmailVerification.php");
$_SESSION["Language"] = "FR";
if(isset($_POST['LoginButtonGreen'])){
$UserName = mysqli_real_escape_string($ConnectionFunction,$_POST['UserName']);
$Password = mysqli_real_escape_string($ConnectionFunction,$_POST["Password"]);
$UserIDRetreivalQuery ="SELECT UserID FROM userdetails WHERE(UserName)='$UserName' LIMIT 1";
$UserIDRetreival=mysqli_query($ConnectionFunction,$UserIDRetreivalQuery);

if($UserIDRetreival==False){
    array_push($validation, "Error Technical : Failure to access DB");
}
else{
    foreach($UserIDRetreival as $test){
        $UserID=$test['UserID'];
    }
}
$PasswordHashRetreivalQuery="SELECT PasswordHash FROM userprivatedetails WHERE(UserID)= '$UserID' LIMIT 1";
$PasswordHashRetreival=mysqli_query($ConnectionFunction,$PasswordHashRetreivalQuery);

$EmailVerificationRetreivalQuery ="SELECT AccountVerified FROM userdetails WHERE(UserID) = '$UserID' LIMIT 1";
$EmailVerificationRetreival=mysqli_query($ConnectionFunction,$EmailVerificationRetreivalQuery) or die(mysqli_error($ConnectionFunction));
array_push($validation,"Tehcni");
foreach($PasswordHashRetreival as $row){            
        if (password_verify($Password,$row['PasswordHash'])){
            array_push($validation,"Passwords match");
            foreach($EmailVerificationRetreival as $EmailRow){
                echo $EmailRow['AccountVerified'];
                if($EmailRow['AccountVerified'] == "1"){
                    //Email is Verfifed
                    echo "Email Verified";
                    echo "Log In Approved";
                    $_SESSION["UserLoggedIn"] = $UserID;
                    $UserType="Student";
                    $IsTeacherQuery = "SELECT Teacher FROM userdetails WHERE UserID = '$UserID'";
                    foreach($IsTeacherQuery as $row){
                        if($row["Teacher"]=="1"){
                            $UserType="Teacher";
                        }
                        else{
                            $UserType="Student";
                        }
                    }
                    if($UserType=="Teacher"){
                        header("Location: TeacherPage.html");
                    }
                    else{
                        header("Location: MainPage.html");
                    }
                    
                }
                else{
                    //Email is not verified;
                    array_push($validation,"Please Verify your email address");
                    
                }
                sleep(25);
            }
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
?>
</html>