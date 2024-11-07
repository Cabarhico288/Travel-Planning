<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Data</title>
    <!-- Include SweetAlert library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .actions {
            text-align: center;
        }

        .delete, .approve, .view {
            padding: 6px 10px;
            margin: 0 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .delete {
            background-color: #dc3545;
            color: white;
        }

        .approve {
            background-color: #28a745;
            color: white;
        }

        .view {
            background-color: #007bff;
            color: white;
        }

        /* Styles for modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Modal Popup -->
    <div id="bookingModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeBookingModal()">&times;</span>
            <div id="bookingDetails"></div>
        </div>
    </div>

<?php
// Include the database connection file
require_once "connection.php";

// Query to fetch data from the booking table with agent name joined from the destination table
$query = "SELECT b.id, b.traveller_id, d.agent_name, d.placename, p.pname, b.no_adult, b.no_child, b.from_date, b.total_amount, b.status 
          FROM booking b 
          INNER JOIN destination d ON b.destination = d.id 
          INNER JOIN packages p ON b.package_id = p.id";
$result = mysqli_query($con, $query);

// Check if there are any rows returned
if (mysqli_num_rows($result) > 0) {
    // Display table header
    echo "<table>";
    echo "<tr>";
    echo "<th>Traveller Name</th>";
    echo "<th>Destination</th>";
    echo "<th>Package Name</th>";
    echo "<th>No of Adults</th>";
    echo "<th>No of Children</th>";
    echo "<th>From Date</th>";
    echo "<th>Total Amount</th>";
    echo "<th>Status</th>";
    echo "<th class='actions'>Actions</th>";
    echo "</tr>";

    // Fetch and display data for each row
    while ($row = mysqli_fetch_assoc($result)) {
        // Query to fetch traveler's name
        $travellerQuery = "SELECT name FROM usertable WHERE id = {$row['traveller_id']}";
        $travellerResult = mysqli_query($con, $travellerQuery);
        $travellerName = mysqli_fetch_assoc($travellerResult)['name'];

        echo "<tr>";
        echo "<td>" . $travellerName . "</td>";
        echo "<td>" . $row['placename'] . "</td>";
        echo "<td>" . $row['pname'] . "</td>";
        echo "<td>" . $row['no_adult'] . "</td>";
        echo "<td>" . $row['no_child'] . "</td>";
        echo "<td>" . $row['from_date'] . "</td>";
        echo "<td>" . $row['total_amount'] . "</td>";
        echo "<td>" . $row['status'] . "</td>";
        echo "<td class='actions'>";
        if ($row['status'] !== 'Approved') {
            echo "<button class='approve' onclick='approveBooking(" . $row['id'] . ")'>Approve</button>";
        }
        echo "<button class='delete' onclick='deleteBooking(" . $row['id'] . ")'>Delete</button>";
        echo "<button class='view' onclick='viewBooking(" . $row['id'] . ")'>View</button>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No booking data found.";
}

// Close database connection
mysqli_close($con);
?>

<script>
    function deleteBooking(bookingId) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You will not be able to recover this booking!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send AJAX request to delete the booking
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "Agentfunctions/delete_booking.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        if (xhr.responseText === 'success') {
                            Swal.fire(
                                'Deleted!',
                                'Your booking has been deleted.',
                                'success'
                            ).then(() => {
                                // Reload the page after successful deletion
                                window.location.reload();
                            });
                        } else {
                            // Handle deletion error
                            Swal.fire(
                                'Error!',
                                'Failed to delete booking.',
                                'error'
                            );
                        }
                    }
                };
                xhr.send("bookingId=" + bookingId);
            }
        });
    }

    function approveBooking(bookingId) {
        // Send AJAX request to update the status to "Approved"
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "Agentfunctions/update_status.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                if (xhr.responseText === 'success') {
                    Swal.fire(
                        'Approved!',
                        'Your booking has been approved.',
                        'success'
                    ).then(() => {
                        // Reload the page after successful approval
                        window.location.reload();
                    });
                } else {
                    // Handle approval error
                    Swal.fire(
                        'Error!',
                        'Failed to approve booking.',
                        'error'
                    );
                }
            }
        };
        xhr.send("bookingId=" + bookingId);
    }

    function viewBooking(bookingId) {
        // Send AJAX request to get booking details
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "Agentfunctions/get_booking_details.php?bookingId=" + bookingId, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Display booking details in modal popup
                document.getElementById("bookingDetails").innerHTML = xhr.responseText;
                document.getElementById("bookingModal").style.display = "block";
            }
        };
        xhr.send();
    }

    function closeBookingModal() {
        document.getElementById("bookingModal").style.display = "none";
    }
</script>
</body>
</html>
