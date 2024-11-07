<?php
// Include the database connection file
require_once "connection.php";

if (isset($_GET['id'])) {
    $bookingId = $_GET['id'];

    // Query to retrieve booking details including the image from the destination table
    $query = "
        SELECT 
            destination.placename AS destination,
            destination.agent_name AS agent_name,
            packages.pname AS package_name,
            packages.description AS description,
            packages.adult_price AS adult_price,
            packages.child_price AS child_price,
            booking.no_adult,
            booking.no_child,
            booking.from_date,
            booking.total_amount,
            booking.status,
            booking.created_date,
            destination.image AS image
        FROM booking
        INNER JOIN destination ON booking.destination = destination.id
        INNER JOIN packages ON booking.package_id = packages.id
        WHERE booking.id = '$bookingId'
    ";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $bookingDetails = mysqli_fetch_assoc($result);
        
        $imageData = $bookingDetails['image'];
        $imageSrc = 'data:image/jpeg;base64,' . base64_encode($imageData);

        
        echo json_encode($bookingDetails);
    } else {
        echo json_encode(['error' => 'Booking not found']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>
