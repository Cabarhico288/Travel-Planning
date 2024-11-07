<?php
require_once "connection.php";

// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Check if booking ID is provided
if (isset($_POST['id'])) {
    $bookingId = $_POST['id'];

    // Update booking status to "Cancelled"
    $query = "UPDATE booking SET status = 'Cancelled' WHERE id = $bookingId";
    if (mysqli_query($con, $query)) {
        echo "Booking cancelled successfully.";
    } else {
        echo "Error: " . mysqli_error($con);
    }
} else {
    echo "No booking ID provided.";
}
?>
