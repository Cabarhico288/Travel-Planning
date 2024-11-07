<?php
// Include the database connection file
require_once "connection.php";

// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Fetch user's ID from the session
$travellerId = $_SESSION['traveller_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquire Destination</title>
    <link rel="stylesheet" href="../css/inquiredesign.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        /* Styles for Cancelled overlay */
        .booking-item {
            position: relative;
            overflow: hidden;
        }

        .booking-item.cancelled::before {
            content: "Cancelled";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 0, 0, 0.7);
            color: white;
            font-size: 2em;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            z-index: 2;
        }

        .booking-item img {
            opacity: 1;
            transition: opacity 0.3s;
        }

        .booking-item.cancelled img {
            opacity: 0.3;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Inquire Destination</h1>
</div>
<div class="display-container">
    <?php
    // Query to retrieve stored booking places from the database for the logged-in user
    $query = "
        SELECT 
            booking.id,
            destination.placename AS destination,
            destination.agent_name AS agent_name,
            packages.pname AS package_name,
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
        WHERE booking.traveller_id = '$travellerId'
    ";
    $result = mysqli_query($con, $query);

    // Loop through each stored booking place and display it in a container
    while ($row = mysqli_fetch_assoc($result)) {
        // Check if the image data exists
        if (!empty($row['image'])) {
            // Convert blob data to base64 format for image display
            $imageData = base64_encode($row['image']);
            $src = 'data:image/jpeg;base64,' . $imageData;
        ?>
        <div class="booking-item <?php echo ($row['status'] == 'Cancelled') ? 'cancelled' : ''; ?>">
            <div class="place-info">
                <h2><?php echo $row['destination']; ?></h2>
                <?php if (!empty($row['image'])): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image']); ?>" alt="Destination Image">
                <?php endif; ?>
                <p><strong>Agent Name:</strong> <?php echo $row['agent_name']; ?></p>
                <p><strong>Package Name:</strong> <?php echo $row['package_name']; ?></p>
                <p><strong>Number of Adults:</strong> <?php echo $row['no_adult']; ?></p>
                <p><strong>Number of Children:</strong> <?php echo $row['no_child']; ?></p>
                <p><strong>Date:</strong> <?php echo $row['from_date']; ?></p>
                <p><strong>Total Amount:</strong> <?php echo $row['total_amount']; ?></p>
                <p><strong>Status:</strong> <?php echo $row['status']; ?></p>
                <p><strong>Check-in Date:</strong> <?php echo $row['created_date']; ?></p>
                <button class="book-button" onclick="showInquiryForm(<?php echo $row['id']; ?>, '<?php echo $row['status']; ?>')">View</button>
                <?php if ($row['status'] == 'Pending'): ?>
                    <button class="cancel-button" onclick="cancelBooking(<?php echo $row['id']; ?>)">Cancel</button>
                <?php endif; ?>
            </div>
        </div>
    <?php 
        }
    } 
    ?>
</div>

<!-- Modal for Inquiry Form -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Inquired Destinations</h2>
        <form id="inquiryForm">
            <input type="hidden" id="destination-id" name="destination-id">
            <input type="hidden" id="booking-status" name="booking-status">
            <!-- Add other form fields as required -->
            <div id="booking-details">
                <!-- Booking details will be inserted here via JavaScript -->
            </div>
        </form>
        <br>
    </div>
</div>

<script>
    function showInquiryForm(id, status) {
        if (status === 'Pending') {
            Swal.fire({
                icon: 'info',
                title: 'Booking Pending',
                text: 'Your booking is still pending. Please wait for approval.'
            });
        } else if (status === 'Approved') {
            var modal = document.getElementById('myModal');
            modal.style.display = "block";
            document.getElementById('destination-id').value = id;
            document.getElementById('booking-status').value = status;
            
            // Fetch booking details via AJAX and display in the modal
            fetchBookingDetails(id);
        }
    }

    function closeModal() {
        var modal = document.getElementById('myModal');
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        var modal = document.getElementById('myModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    function fetchBookingDetails(id) {
        // AJAX request to fetch booking details based on booking ID
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch_booking_details.php?id=' + id, true);
        xhr.onload = function() {
            if (this.status === 200) {
                var bookingDetails = JSON.parse(this.responseText);
                var bookingDetailsContainer = document.getElementById('booking-details');
                var imageSrc = bookingDetails.image.startsWith('data:') ? bookingDetails.image : bookingDetails.image;

                // Replace newline characters with <br> tags in the description
                var formattedDescription = bookingDetails.description.replace(/\n/g, '<br>');

                bookingDetailsContainer.innerHTML = `
                    <p><strong>Destination:</strong> ${bookingDetails.destination}</p>
                    <p><strong>Agent Name:</strong> ${bookingDetails.agent_name}</p>
                    <p><strong>Package Name:</strong> ${bookingDetails.package_name}</p>
                    <div><strong>Description:</strong> ${formattedDescription}</div><br>
                    <p><strong>Adult Price:</strong> ${bookingDetails.adult_price}</p>
                    <p><strong>Child Price:</strong> ${bookingDetails.child_price}</p>
                    <p><strong>Number of Adults:</strong> ${bookingDetails.no_adult}</p>
                    <p><strong>Number of Children:</strong> ${bookingDetails.no_child}</p>
                    <p><strong>Date:</strong> ${bookingDetails.from_date}</p>
                    <p><strong>Total Amount:</strong> ${bookingDetails.total_amount}</p>
                    <p><strong>Status:</strong> ${bookingDetails.status}</p>
                    <p><strong>Check-in Date:</strong> ${bookingDetails.created_date}</p>
                `;
            }
        };
        xhr.send();
    }

    function cancelBooking(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you really want to cancel this booking?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, cancel it!',
            cancelButtonText: 'No, keep it'
        }).then((result) => {
            if (result.isConfirmed) {
                // AJAX request to cancel the booking
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'cancel_booking.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (this.status === 200) {
                        Swal.fire(
                            'Cancelled!',
                            'Your booking has been cancelled.',
                            'success'
                        ).then(() => {
                            location.reload(); // Reload the page to reflect changes
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            'There was an error cancelling your booking.',
                            'error'
                        );
                    }
                };
                xhr.send('id=' + id);
            }
        });
    }
</script>

</body>
</html>
