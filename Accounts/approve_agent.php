<?php
// Include the database connection file
require_once "connection.php";

// Check if agent_id is set and not empty
if (isset($_POST['agent_id']) && !empty($_POST['agent_id'])) {
    // Sanitize the agent_id
    $agentId = mysqli_real_escape_string($con, $_POST['agent_id']);

    // Update the 'legit' column to 2 (approved) for the specified agent
    $query = "UPDATE usertable SET legit = 2 WHERE id = '$agentId'";
    $result = mysqli_query($con, $query);

    // Check if the query was successful
    if ($result) {
        // Return success message (you can return any message you want)
        echo "Agent approved successfully.";
    } else {
        // Return error message
        echo "Error: Unable to approve agent.";
    }
} else {
    // Return error message if agent_id is not set or empty
    echo "Error: Missing agent ID.";
}
?>
