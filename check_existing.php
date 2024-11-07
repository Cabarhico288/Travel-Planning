<?php
require_once "connection.php";

$response = array();

if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['contactnum'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $contactnum = mysqli_real_escape_string($con, $_POST['contactnum']);

    // Check if name already exists
    $name_check_query = "SELECT * FROM usertable WHERE name='$name' LIMIT 1";
    $name_result = mysqli_query($con, $name_check_query);
    $name_user = mysqli_fetch_assoc($name_result);
    $response['name'] = $name_user ? true : false;

    // Check if email already exists
    $email_check_query = "SELECT * FROM usertable WHERE email='$email' LIMIT 1";
    $email_result = mysqli_query($con, $email_check_query);
    $email_user = mysqli_fetch_assoc($email_result);
    $response['email'] = $email_user ? true : false;

    // Check if contact number already exists
    $contact_check_query = "SELECT * FROM usertable WHERE contact='$contactnum' LIMIT 1";
    $contact_result = mysqli_query($con, $contact_check_query);
    $contact_user = mysqli_fetch_assoc($contact_result);
    $response['contactnum'] = $contact_user ? true : false;
}

echo json_encode($response);
?>
