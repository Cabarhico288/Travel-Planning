<?php
// Include the database connection file
require_once "connection.php";

// Check if user_id is set in GET request
if(isset($_GET['user_id'])) {
    // Sanitize the input to prevent SQL injection
    $userId = mysqli_real_escape_string($con, $_GET['user_id']);

    // Fetch user data based on user ID
    $query = "SELECT usertable.*, acredentials.certificate FROM usertable 
              LEFT JOIN acredentials ON usertable.id = acredentials.user_id 
              WHERE usertable.id = '$userId'";
    $result = mysqli_query($con, $query);

    // Check if the query executed successfully
    if($result) {
        // Check if user data exists
        if(mysqli_num_rows($result) > 0) {
            // Fetch user data
            $userData = mysqli_fetch_assoc($result);

            // Display user data
            echo "<h2>User Details</h2>";
            echo "<p><strong>Name:</strong> {$userData['name']}</p>";
            echo "<p><strong>Contact:</strong> {$userData['contact']}</p>";
            echo "<p><strong>Email:</strong> {$userData['email']}</p>";
            echo "<p><strong>Role:</strong> {$userData['role']}</p>";
            echo "<p><strong>Certificate:</strong> {$userData['certificate']}</p>";
            echo "<img src='{$userData['avatar']}' alt='Avatar' width='100'>";
        } else {
            echo "<p>No user found with the provided ID.</p>";
        }
    } else {
        echo "<p>Error fetching user data.</p>";
    }
} else {
    echo "<p>User ID not provided.</p>";
}
?>
