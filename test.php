<?php
$receiver = "cabarhico288@gmail.com";
$subject = "Email test";
$body = "Hi, there...This is a test email send from Localhost.";
$sender = "From:akoa36253@gmail.com";

if(mail($receiver, $subject, $body, $sender)){
    echo "Email sent successfully to $receiver";
}else{
    echo "Sorry, failed while sending mail!";
}
?>