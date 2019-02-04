<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require ('PHPMailer-6.0.7\PHPMailer-6.0.7\src\PHPMailer.php');
require ('PHPMailer-6.0.7\PHPMailer-6.0.7\src\Exception.php');
require ('PHPMailer-6.0.7\PHPMailer-6.0.7\src\SMTP.php');
$MailFunction = new PHPMailer(true);
$MailFunction->SMTPDebug=2;
$MailFunction ->Username ="monoclnoreply@gmail.com";
$MailFunction ->Password ="insertpassword";
$MailFunction ->IsSMTP(true);
$MailFunction ->Host ="smtp.gmail.com";
$MailFunction ->Port = 465;
$MailFunction ->FromName = "Moncol No Reply";
$MailFunction ->SMTPAuth =true;
$MailFunction->SMTPSecure="ssl";
$MailFunction ->From = $MailFunction ->Username;
   
//function SendEmail($Destination,$EmailVerificationHash){
    $MailFunction ->AddAddress("insertadress");
    $MailFunction ->Subject =" Monocl Account Verification";
    $MailFunction ->Body = "You have successfully ";        
         
    
    if($MailFunction->Send()){
        //return true;
        echo "true";
        
    }
    else{
        //return false;
        echo "false";
        echo $MailFunction->ErrorInfo;
    }
    echo "test";
//}
?>