<?php
// Include the database connection file
require_once "connection.php";

// Fetch accounts based on role
$query = "SELECT * FROM usertable";
$result = mysqli_query($con, $query);

// Initialize arrays to store accounts based on role
$roles = array(
    'Admin' => array(),
    'Agent' => array(),
    'Traveler' => array()
);

// Loop through the result and store accounts in respective role arrays
while ($row = mysqli_fetch_assoc($result)) {
    $role = $row['role'];
    array_push($roles[$role], $row);
}

// Display accounts in separate containers based on role
foreach ($roles as $role => $accounts) {
    echo "<div class='container'>";
    echo "<h2>{$role} Accounts</h2>";
    if (count($accounts) > 0) {
        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Name</th>";
       
        echo "<th>Contact</th>";
        echo "<th>Email</th>";
     
       
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($accounts as $account) {
            echo "<tr>";
            echo "<td>{$account['name']}</td>";
           
            echo "<td>{$account['contact']}</td>";
            echo "<td>{$account['email']}</td>";
           
            echo "<td>";
           
            echo "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>No {$role} accounts found.</p>";
    }
    echo "</div>";
}
?>
