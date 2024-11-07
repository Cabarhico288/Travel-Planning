<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Agent Accounts</title>
<style>
/* CSS for User Data Container */
.profile-form-container {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    z-index: 1000; /* Ensure it appears above other elements */
}

.profile-data {
    text-align: center;
}

.close-btn {
    position: absolute;
    top: 5px;
    right: 5px;
    background-color: transparent;
    border: none;
    cursor: pointer;
}

.profile-data h3 {
    margin-bottom: 10px;
    color: #333;
}

.profile-data p {
    margin-bottom: 5px;
}

.profile-data strong {
    font-weight: bold;
}

/* Optional: Adjust styles as needed */
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>
<!-- Add this modal structure at the end of your HTML body -->


<?php
session_start(); // Start the session
// Include the database connection file
require_once "connection.php";

// Display the message if it exists
if (isset($_SESSION['message'])) {
    echo "<script>
        Swal.fire({
            title: 'Notification',
            text: '" . htmlspecialchars($_SESSION['message']) . "',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    </script>";
    unset($_SESSION['message']); // Clear the message after displaying it
}

// Fetch accounts based on role
$query = "SELECT * FROM usertable WHERE role='Agent'";
$result = mysqli_query($con, $query);

// Check if there are any accounts for the current role
if (mysqli_num_rows($result) > 0) {
    echo "<div class='container'>";
    echo "<h2>Agent Accounts</h2>";
    echo "<table>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Name</th>";
    echo "<th>Contact</th>";
    echo "<th>Email</th>";
    echo "<th>Status</th>"; // Add column for status
    echo "<th>Action</th>"; // Add column for actions
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    
    // Loop through each row and display account information
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>{$row['name']}</td>";
        echo "<td>{$row['contact']}</td>";
        echo "<td>{$row['email']}</td>";
        echo "<td>";
        
        // Determine status based on the 'legit' column value
        if ($row['legit'] == 1) {
            echo "Approved";
        } else {
            echo "Pending";
        }
        
        echo "</td>";
        echo "<td>";
         // View button
         echo "<button class='btn btn-view' onclick='redirectToAgentProfile({$row['id']})'>View</button>";
        // Display action buttons based on status
        if ($row['legit'] == 1) {
            // If approved, display disapprove button
            echo "<form action='update_legit_status.php' method='POST' style='display: inline;'>";
            echo "<input type='hidden' name='agent_id' value='{$row['id']}'>";
            echo "<input type='hidden' name='status' value='2'>"; // Set status to 2 for disapproval
            echo "<button type='submit' class='btn btn-disapprove'>Block</button>";
            echo "</form>";
        } else {
            // If pending, display approve button
            echo "<form action='update_legit_status.php' method='POST' style='display: inline;'>";
            echo "<input type='hidden' name='agent_id' value='{$row['id']}'>";
            echo "<input type='hidden' name='status' value='1'>"; // Set status to 1 for approval
            echo "<button type='submit' class='btn btn-approve'>Approve</button>";
            echo "</form>";
        }
        
        // Delete button
        echo "<form action='delete_agent.php' method='POST' style='display: inline;'>";
        echo "<input type='hidden' name='agent_id' value='{$row['id']}'>";
        echo "<button type='submit' class='btn btn-delete'>Delete</button>";
        echo "</form>";
        
       
    }
    
    echo "</tbody>";
    echo "</table>";
    echo "</div>";
} else {
    echo "<p>No Agent accounts found.</p>";
}
?>
<div id="userDataContainer" class="profile-form-container" style="display: none;">
  <div class="profile-data">
    <button class="close-btn" onclick="closeUserDataContainer()">Close</button>
    <h3>User Data</h3>
    <p><strong>Certificate:</strong> <span id="userCertificate"></span></p>
    <p><strong>Name:</strong> <span id="userName"></span></p>
    <p><strong>Avatar:</strong> <img id="userAvatar" src="" alt="User Avatar"></p>
    <p><strong>Contact:</strong> <span id="userContact"></span></p>
    <p><strong>Role:</strong> <span id="userRole"></span></p>
    <p><strong>Email:</strong> <span id="userEmail"></span></p>
  </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
// Function to redirect to agentprofile.php with user ID as parameter
function redirectToAgentProfile(userId) {
    window.location.href = "../Accounts//agentprofileview.php?user_id=" + userId;
}
</script>



</body>
</html>
