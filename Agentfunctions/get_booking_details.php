<?php
// Include the database connection file
require_once "../connection.php";

// Check if bookingId is set and numeric
if (isset($_GET['bookingId']) && is_numeric($_GET['bookingId'])) {
    $bookingId = $_GET['bookingId'];

    // Query to fetch booking details based on the provided booking ID
    $query = "SELECT b.id, b.traveller_id, d.agent_name, d.placename, d.image, p.pname, p.adult_price, p.child_price, b.no_adult, b.no_child, b.from_date, b.total_amount, b.status, b.created_date 
              FROM booking b 
              INNER JOIN destination d ON b.destination = d.id 
              INNER JOIN packages p ON b.package_id = p.id 
              WHERE b.id = $bookingId";
    $result = mysqli_query($con, $query);

    // Check if the query executed successfully
    if ($result) {
        // Check if the booking exists
        if (mysqli_num_rows($result) > 0) {
            $booking = mysqli_fetch_assoc($result);

            // Fetch traveler's name
            $travellerQuery = "SELECT name FROM usertable WHERE id = {$booking['traveller_id']}";
            $travellerResult = mysqli_query($con, $travellerQuery);
            $travellerName = mysqli_fetch_assoc($travellerResult)['name'];

            // Format the booking details as HTML
            $output = "<table>";
            $output .= "<tr><th>Booking ID</th><td>{$booking['id']}</td></tr>";
            $output .= "<tr><th>Traveller Name</th><td>{$travellerName}</td></tr>";
            $output .= "<tr><th>Agent Name</th><td>{$booking['agent_name']}</td></tr>";
            $output .= "<tr><th>Destination</th><td>{$booking['placename']}</td></tr>";
            $output .= "<tr><th>Package</th><td>{$booking['pname']} (Adult Price: {$booking['adult_price']}, Child Price: {$booking['child_price']})</td></tr>";
            $output .= "<tr><th>No of Adults</th><td>{$booking['no_adult']}</td></tr>";
            $output .= "<tr><th>No of Children</th><td>{$booking['no_child']}</td></tr>";
            $output .= "<tr><th>From Date</th><td>{$booking['from_date']}</td></tr>";
            $output .= "<tr><th>Total Amount</th><td>{$booking['total_amount']}</td></tr>";
            $output .= "<tr><th>Status</th><td>{$booking['status']}</td></tr>";
            $output .= "<tr><th>Created Date</th><td>{$booking['created_date']}</td></tr>";
            $output .= "</table>";

            echo $output;
        } else {
            echo "Booking not found.";
        }
    } else {
        // Log the SQL error message
        error_log("MySQL Error: " . mysqli_error($con));
        echo "Error fetching booking details: " . mysqli_error($con);
    }
} else {
    echo "Invalid booking ID.";
}

// Close database connection
mysqli_close($con);
?>
