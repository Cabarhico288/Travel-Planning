<?php
require_once "connection.php";

// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo "<script>
            setTimeout(function() {
                Swal.fire({
                    title: 'Error!',
                    text: 'Not logged in.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }, 100);
          </script>";
    exit();
}

$email = $_SESSION['email'];
$response = ['status' => 'success', 'message' => 'Profile updated successfully.'];

// Handle file uploads
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == UPLOAD_ERR_OK) {
    $avatarData = file_get_contents($_FILES['avatar']['tmp_name']);

    // Save avatar to usertable
    $query = "UPDATE usertable SET avatar = ? WHERE email = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "bs", $avatarData, $email);
    mysqli_stmt_send_long_data($stmt, 0, $avatarData);
    if (!mysqli_stmt_execute($stmt)) {
        $response['status'] = 'error';
        $response['message'] = 'Error updating avatar: ' . mysqli_error($con);
        echo json_encode($response);
        exit();
    }
}

if (isset($_FILES['credential_photo']) && $_FILES['credential_photo']['error'] == UPLOAD_ERR_OK) {
    $credData = file_get_contents($_FILES['credential_photo']['tmp_name']);

    // Save credential photo to acredentials table
    $query = "INSERT INTO acredentials (certificate) VALUES (?)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "b", $credData);
    mysqli_stmt_send_long_data($stmt, 0, $credData);
    if (!mysqli_stmt_execute($stmt)) {
        $response['status'] = 'error';
        $response['message'] = 'Error updating credential photo: ' . mysqli_error($con);
        echo json_encode($response);
        exit();
    } else {
        // Get the ID of the inserted row
        $credId = mysqli_insert_id($con);

        // Update the cred column in the usertable
        $query = "UPDATE usertable SET cred = ? WHERE email = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "is", $credId, $email);
        if (!mysqli_stmt_execute($stmt)) {
            $response['status'] = 'error';
            $response['message'] = 'Error updating credential ID: ' . mysqli_error($con);
            echo json_encode($response);
            exit();
        }
    }
}

// Display success message with SweetAlert
echo "<script>
        setTimeout(function() {
            Swal.fire({
                title: 'Success!',
                text: 'Profile updated successfully.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.reload();
            });
        }, 100);
      </script>";
exit();
?>
