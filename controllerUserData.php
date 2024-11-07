<?php 

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once "session_start.php";

require "connection.php";
$email = "";
$name = "";
$errors = array();
$role = ""; 
$contact = "";

//if user signup button
if(isset($_POST['signup'])){
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $contact = mysqli_real_escape_string($con, $_POST['contactnum']);
    $role = mysqli_real_escape_string($con, $_POST['role']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);

    if($password !== $cpassword){
        $errors['password'] = "Confirm password not matched!";
    }
    $email_check = "SELECT * FROM usertable WHERE email = '$email'";
    $res = mysqli_query($con, $email_check);
    if(mysqli_num_rows($res) > 0){
        $errors['email'] = "Email that you have entered is already exist!";
    }
    if(count($errors) === 0){
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        $code = rand(999999, 111111);
        $status = "notverified";
        $insert_data = "INSERT INTO usertable (name, email, contact, role, password, code, status)
                        VALUES ('$name', '$email', '$contact', '$role', '$encpass', '$code', '$status')";
        $data_check = mysqli_query($con, $insert_data);
        if($data_check){
            // Initialize PHPMailer
            $mail = new PHPMailer(true);

            try {
                // SMTP configuration
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com'; // SMTP server address
                $mail->SMTPAuth   = true;
                $mail->Username   = 'akoa36253@gmail.com'; // SMTP username
                $mail->Password   = 'lqmk ierh qjzy cnmj';    // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
                $mail->Port       = 587; // TCP port to connect to

                // Sender and recipient
                $mail->setFrom('akoa36253@gmail.com', 'Your Name'); // Update with your name
                $mail->addAddress($email);

                // Email content
                $mail->isHTML(false); // Set email format to HTML
                $mail->Subject = 'Verification Code';
                $mail->Body    = "Your verification code is: $code";

                // Send email
                $mail->send();

                // Store verification code in the database
                $update_code = "UPDATE usertable SET code = '$code' WHERE email = '$email'";
                mysqli_query($con, $update_code);

                $info = "We've sent a verification code to your email - $email";
                $_SESSION['info'] = $info;
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
                header('location: user-otp.php');
                exit();
            } catch (Exception $e) {
                $errors['otp-error'] = "Failed while sending code! Error: {$mail->ErrorInfo}";
            }
        } else {
            $errors['db-error'] = "Failed while inserting data into database!";
        }
    }
}

    //if user click verification code submit button
    if(isset($_POST['check'])){
        $_SESSION['info'] = "";
        $otp_code = mysqli_real_escape_string($con, $_POST['otp']);
        $check_code = "SELECT * FROM usertable WHERE code = $otp_code";
        $code_res = mysqli_query($con, $check_code);
        if(mysqli_num_rows($code_res) > 0){
            $fetch_data = mysqli_fetch_assoc($code_res);
            $fetch_code = $fetch_data['code'];
            $email = $fetch_data['email'];
            $code = 0;
            $status = 'verified';
            $update_otp = "UPDATE usertable SET code = $code, status = '$status' WHERE code = $fetch_code";
            $update_res = mysqli_query($con, $update_otp);
            if($update_res){
                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;
                header('location: home.php');
                exit();
            }else{
                $errors['otp-error'] = "Failed while updating code!";
            }
        }else{
            $errors['otp-error'] = "You've entered incorrect code!";
        }
    }

// Check if user has exceeded maximum login attempts
if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 2) {
    // Redirect to forgot password page and reset login attempts
    unset($_SESSION['login_attempts']); // Reset login attempts
    header('location: forgot-password.php');
    exit();
}

// Check if login button is clicked
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $check_email = "SELECT * FROM usertable WHERE email = '$email'";
    $res = mysqli_query($con, $check_email);

    if (mysqli_num_rows($res) > 0) {
        $fetch = mysqli_fetch_assoc($res);
        $fetch_pass = $fetch['password'];

        if (password_verify($password, $fetch_pass)) {
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $fetch['name']; // Store user's name in session
            $status = $fetch['status'];
            $role = $fetch['role'];

            if ($status == 'verified') {
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;

                // Redirect based on role
                if ($role == 'Agent') {
                    header('location: Agent.php');
                } elseif ($role == 'Traveler') {
                    header('location: Traveler.php');
                } elseif ($role == 'Admin') { // Add this condition for Admin role
                    header('location: Accounts/Admin.php');
                } else {
                    // Default redirection if role not specified
                    header('location: index.php');
                }
                exit();
            } else {
                // Generate random 6-digit number
                $verificationCode = mt_rand(100000, 999999);

                // Initialize PHPMailer
                $mail = new PHPMailer(true);

                try {
                    // SMTP configuration
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com'; // SMTP server address
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'akoa36253@gmail.com'; // SMTP username
                    $mail->Password   = 'lqmk ierh qjzy cnmj';    // SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
                    $mail->Port       = 587; // TCP port to connect to

                    // Sender and recipient
                    $mail->setFrom('akoa36253@gmail.com', 'Document Editor');
                    $mail->addAddress($email);

                    // Email content
                    $mail->isHTML(false); // Set email format to HTML
                    $mail->Subject = 'Verification Code';
                    $mail->Body    = "Your verification code is: $verificationCode";

                    // Send email
                    $mail->send();

                    // Store verification code in the database
                    $update_code = "UPDATE usertable SET code = '$verificationCode' WHERE email = '$email'";
                    mysqli_query($con, $update_code);

                    // Redirect to OTP verification page
                    header('location: user-otp.php');
                    exit();
                } catch (Exception $e) {
                    $errors['email'] = "Failed to send verification code. Error: {$mail->ErrorInfo}";
                }
            }
        } else {
            // Increment login attempts
            if (!isset($_SESSION['login_attempts'])) {
                $_SESSION['login_attempts'] = 1;
            } else {
                $_SESSION['login_attempts']++;
            }
            
            $errors['email'] = "Incorrect email or password!";
        }
    } else {
        $errors['email'] = "It looks like you're not yet a member! Click on the bottom link to signup.";
    }
}


//if user click continue button in forgot password form
if(isset($_POST['check-email'])){
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $check_email = "SELECT * FROM usertable WHERE email='$email'";
    $run_sql = mysqli_query($con, $check_email);
    if(mysqli_num_rows($run_sql) > 0){
        $code = mt_rand(100000, 999999); // Generate 6-digit random code
        $insert_code = "UPDATE usertable SET code = '$code' WHERE email = '$email'";
        $run_query =  mysqli_query($con, $insert_code);
        if($run_query){
            // Initialize PHPMailer
            $mail = new PHPMailer(true);

            try {
                // SMTP configuration
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com'; // SMTP server address
                $mail->SMTPAuth   = true;
                $mail->Username   = 'akoa36253@gmail.com'; // SMTP username
                $mail->Password   = 'lqmk ierh qjzy cnmj';    // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
                $mail->Port       = 587; // TCP port to connect to

                // Sender and recipient
                $mail->setFrom('akoa36253@gmail.com', 'Document Editor');
                $mail->addAddress($email);

                // Email content
                $mail->isHTML(false); // Set email format to HTML
                $mail->Subject = 'Password Reset Code';
                $mail->Body    = "Your password reset code is $code";

                // Send email
                $mail->send();
                $info = "We've sent a password reset OTP to your email - $email";
                $_SESSION['info'] = $info;
                $_SESSION['email'] = $email;
                header('location: reset-code.php');
                exit();
            } catch (Exception $e) {
                $errors['otp-error'] = "Failed while sending code! Error: {$mail->ErrorInfo}";
            }
        } else {
            $errors['db-error'] = "Something went wrong!";
        }
    } else {
        $errors['email'] = "This email address does not exist!";
    }
}


     //if user click change password button
     if(isset($_POST['change-password'])){
        $_SESSION['info'] = "";
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
        if($password !== $cpassword){
            $errors['password'] = "Confirm password not matched!";
        }else{
            $code = 0;
            $email = $_SESSION['email']; //getting this email using session
            $encpass = password_hash($password, PASSWORD_BCRYPT);
            $update_pass = "UPDATE usertable SET code = $code, password = '$encpass' WHERE email = '$email'";
            $run_query = mysqli_query($con, $update_pass);
            if($run_query){
                $info = "Your password changed. Now you can login with your new password.";
                $_SESSION['info'] = $info;
                header('Location: password-changed.php');
            }else{
                $errors['db-error'] = "Failed to change your password!";
            }
        }
    }
    
    //if user click check reset otp button
    if(isset($_POST['check-reset-otp'])){
        $_SESSION['info'] = "";
        $otp_code = mysqli_real_escape_string($con, $_POST['otp']);
        $check_code = "SELECT * FROM usertable WHERE code = $otp_code";
        $code_res = mysqli_query($con, $check_code);
        if(mysqli_num_rows($code_res) > 0){
            $fetch_data = mysqli_fetch_assoc($code_res);
            $email = $fetch_data['email'];
            $_SESSION['email'] = $email;
            $info = "Please create a new password that you don't use on any other site.";
            $_SESSION['info'] = $info;
            header('location: new-password.php');
            exit();
        }else{
            $errors['otp-error'] = "You've entered incorrect code!";
        }
    }
   //if login now button click
    if(isset($_POST['login-now'])){
        header('Location: login-user.php');
    }

    
?>