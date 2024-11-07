<?php
// Include the database connection file
require_once "../connection.php";

// Check if the bookingId parameter is set
if(isset($_POST['bookingId'])) {
    // Sanitize the input to prevent SQL injection
    $bookingId = mysqli_real_escape_string($con, $_POST['bookingId']);

    // Update query to set the status to "Approved"
    $updateQuery = "UPDATE booking SET status = 'Approved' WHERE id = '$bookingId'";

    // Execute the update query
    if(mysqli_query($con, $updateQuery)) {
        // Return a success response if the update was successful
        echo "success";
    } else {
        // Return an error response if the update failed
        echo "error: " . mysqli_error($con);
    }
} else {
    // Return an error response if the bookingId parameter is not set
    echo "error: bookingId parameter is not set";
}

// Close database connection
mysqli_close($con);
?>
