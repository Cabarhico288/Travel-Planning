<?php
require_once "connection.php";
session_start(); // Start the session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $agentId = $_POST['agent_id'];

    // Delete the agent
    $query = "DELETE FROM usertable WHERE id = '$agentId'";
    if (mysqli_query($con, $query)) {
        $_SESSION['message'] = "Agent successfully deleted!";
    } else {
        $_SESSION['message'] = "Error deleting agent: " . mysqli_error($con);
    }
    
    // Redirect back to the same page
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
}
?>
