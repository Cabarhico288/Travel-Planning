<?php
// Include the database connection file
require_once "connection.php";

// Fetch accounts based on role
$query = "SELECT * FROM usertable WHERE role='Traveler'";
$result = mysqli_query($con, $query);

// Check if there are any accounts for the current role
if (mysqli_num_rows($result) > 0) {
    echo "<div class='container'>";
    echo "<h2>Traveler Accounts</h2>";
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
            echo "<button type='submit' class='btn btn-disapprove'>Denied</button>";
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
    echo "<p>No Traveler accounts found.</p>";
}
?>
<script>
// Function to redirect to travelerprofile.php with user ID as parameter
function redirectToTravelerProfile(userId) {
    window.location.href = "travelerprofileview.php?user_id=" + userId;
}
</script>
