<?php
   function  EmailMessageSend($EmailAddress,$EmailVerificationHash){
    $EmailSubject = " Monocl Account Verification";
    $Message = '
            You have successfully created an account at Monocl
            
            please click here to verify the account
            
            http://localhost/EmailVerification.php?Hash='.$EmailVerificationHash.';
            
       ';
    $SentFrom = "From:monocltest@gmail.com";
    mail($EmailAddress,$EmailSubject,$Message,$SentFrom);
}
function EmailMessageRecieved(){
    
}
?>