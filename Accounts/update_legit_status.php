<?php
// Include the database connection file
require_once "connection.php";
session_start(); // Start the session

// Check if agent ID and status are provided
if (isset($_POST['agent_id']) && isset($_POST['status'])) {
    $agentId = $_POST['agent_id'];
    $status = $_POST['status'];

    // Update the legit column in the database
    $updateQuery = "UPDATE usertable SET legit = ? WHERE id = ?";
    $statement = mysqli_prepare($con, $updateQuery);

    // Bind parameters and execute the update query
    mysqli_stmt_bind_param($statement, 'ii', $status, $agentId);
    $result = mysqli_stmt_execute($statement);

    // Check if update was successful
    if ($result) {
        if ($status == 1) {
            $_SESSION['message'] = "Hooray! The agent has been successfully approved!";
        } else {
            $_SESSION['message'] = "The agent has been disapproved. Let's hope for a better match next time!";
        }
    } else {
        $_SESSION['message'] = "Oops! There was an error updating the status: " . mysqli_error($con);
    }
} else {
    $_SESSION['message'] = "Invalid request.";
}

// Redirect back to the previous page (optional)
header("Location: {$_SERVER['HTTP_REFERER']}");
exit();
?>
