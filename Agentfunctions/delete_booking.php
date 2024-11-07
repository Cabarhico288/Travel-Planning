<?php
// Include the database connection file
require_once "../connection.php";

// Check if the bookingId parameter is set
if(isset($_POST['bookingId'])) {
    // Sanitize the input to prevent SQL injection
    $bookingId = mysqli_real_escape_string($con, $_POST['bookingId']);

    // Query to delete the booking from the database
    $deleteQuery = "DELETE FROM booking WHERE id = '$bookingId'";

    // Execute the delete query
    if(mysqli_query($con, $deleteQuery)) {
        // Return a success response if the deletion was successful
        echo "success";
    } else {
        // Return an error response if the deletion failed
        echo "error: " . mysqli_error($con);
    }
} else {
    // Return an error response if the bookingId parameter is not set
    echo "error: bookingId parameter is not set";
}

// Close database connection
mysqli_close($con);
?>
