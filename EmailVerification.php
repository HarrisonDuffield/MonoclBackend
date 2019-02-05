<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require ('PHPMailer-6.0.7\PHPMailer-6.0.7\src\PHPMailer.php');
require ('PHPMailer-6.0.7\PHPMailer-6.0.7\src\Exception.php');
require ('PHPMailer-6.0.7\PHPMailer-6.0.7\src\SMTP.php');


function SendEmail($Destination,$EmailVerificationHash){
$MailFunction = new PHPMailer(true);
$MailFunction ->Username ="monoclnoreply@gmail.com";
$MailFunction ->Password ="MonoclPassword";
$MailFunction ->IsSMTP(true);
$MailFunction ->Host ="smtp.gmail.com";
$MailFunction ->Port = 465;
$MailFunction ->FromName = "Moncol No Reply";
$MailFunction ->SMTPAuth =true;
$MailFunction->SMTPSecure="ssl";
$MailFunction ->From = $MailFunction ->Username;
$MailFunction ->AddAddress($Destination);
$MailFunction ->Subject =" Monocl Account Verification";
$MailFunction ->Body = "You have successfully activated your account please click the link to go"
        . "activate your account "
        . "http://127.0.0.1/AccountVerified.php?VerifyString=$EmailVerificationHash"; 
if($MailFunction->Send()){
    return true;       
    }
else{
    return false;     
    }
}
function TableUpdate($Hash){
    $servername = "localhost:3306";
    $account = "PHPConnection2";
    $dbname = "monoclmain";
    $password="PHPPassword12";
    $ConnectionFunction = mysqli_connect($servername, $account, $password, $dbname);
    $TableUpdateQuery = "UPDATE userdetails SET AccountVerified = 1 WHERE EmailVerificationHash = '$Hash'";
    $TableUpdateExecution = mysqli_query($ConnectionFunction,$TableUpdateQuery);
    if($TableUpdateExecution){
        return true;
    }
    else{
        return false;
    }
}
//}
?>