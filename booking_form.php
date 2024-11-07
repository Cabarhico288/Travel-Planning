<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Booking Form</title>
    <link rel="stylesheet" href="css/bookform2.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Lobster|Montserrat" rel="stylesheet">

</head>
<body>

    <?php
    // Start the session
    session_start();

    // Include the database connection file
    require_once "connection.php"; 

    // Initialize destination ID variable
    $destination_id = null;

    // Check if the destination ID is set in the URL
    if(isset($_GET['id'])) {
        $destination_id = $_GET['id'];
        
        // Query to retrieve the specific destination details from the database
        $query = "SELECT * FROM destination WHERE id = $destination_id";
        $result = mysqli_query($con, $query);
        
        // Check if the query was successful
        if($result) {
            // Fetch the destination details
            $destination_details = mysqli_fetch_assoc($result);
            
            // Display the destination details on the page
            echo "<div class='destination-details'>";
            echo "<img src='uploads/{$destination_details['image']}' alt='Place Image' class='destination-image'>";
            echo "<div class='details'>";
            echo "<p class='agent'><strong>Agent Name:</strong> {$destination_details['agent_name']}</p>"; // Display Agent Name
            echo "<p class='place'><strong>Place Name:</strong> {$destination_details['placename']}</p>";
            echo "<p class='location'><strong>Location:</strong> {$destination_details['location']}</p>";
            echo "<p class='description'><strong>Description:</strong> {$destination_details['description']}</p>";
            echo "<hr>";
           
            echo "</div>"; // End of .details
            echo "</div>"; // End of .destination-details
        } else {
            echo "Error: " . mysqli_error($con);
        }
    } else {
        echo "Destination ID not provided.";
    }
    ?>

    
    
    <script>
        function togglePackageSelection(packageId) {
            var packageCheckbox = document.getElementById('package_' + packageId);
            packageCheckbox.checked = !packageCheckbox.checked;
        }
    </script>





    <div class="container">
        <h2>Travel Booking Form</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="packages">Packages:</label><br>
                <?php
                // Include the database connection file
                require_once "connection.php";

                // Check if the destination ID is set in the URL
                if(isset($_GET['id'])) {
                    $destination_id = $_GET['id'];

                    // Query to fetch package names, descriptions, child_price, and adult_price associated with the selected destination
                    $query = "SELECT id, pname, description, child_price, adult_price FROM packages WHERE desti = $destination_id";
                    $result = mysqli_query($con, $query);

                    // Check if any packages are found
                    if (mysqli_num_rows($result) > 0) {
                        // Loop through each row and display packages as boxes
                        while ($row = mysqli_fetch_assoc($result)) {
                                echo "<div class='package-box' onclick='togglePackageSelection(" . $row['id'] . ")'>";
                                echo "<input type='radio' id='package_" . $row['id'] . "' name='package_id' value='" . $row['id'] . "' required>";
                                echo "<div class='package-content'>";
                                echo "<label><strong>Package Name:</strong> " . $row['pname'] . "</label><br>";
                                echo "<div><strong>Description:</strong> " . nl2br($row['description']) . "</div><br>"; // Use nl2br to preserve formatting
                                echo "<div><strong>Child Price:</strong> <span class='child-price'>" . $row['child_price'] . "</span></div><br>"; // Display Child Price
                                echo "<div><strong>Adult Price:</strong> <span class='adult-price'>" . $row['adult_price'] . "</span></div>"; // Display Adult Price
                                echo "</div>";
                                
                                echo "</div>";
                        }
                    } else {
                        echo "<p>No packages found for this destination</p>";
                    }
                } else {
                    echo "<p>Please select a destination first</p>";
                }
                ?>
            </div>
            <input type="hidden" name="traveller_id" value="<?php echo $_SESSION['traveller_id']; ?>">
            <?php
            // Display the agent name as a hidden input field
            if(isset($destination_details['agent_name'])) {
                echo "<input type='hidden' name='agent_name' value='{$destination_details['agent_name']}'>";
            }
            ?>

            <input type="hidden" name="destination_id" value="<?php echo $selected_package_desti; ?>">

            <label for="no_of_adults">Number of Adults:</label>
            <input type="number" id="no_of_adults" name="no_of_adults" required><br><br>

            <label for="no_of_children">Number of Children:</label>
            <input type="number" id="no_of_children" name="no_of_children" required><br><br>

            <label for="total_amount">Total Amount:</label>
            <input type="number" id="total_amount" name="total_amount" required readonly><br><br>

            <label for="from_date">Check In Date :</label>
            <input type="date" id="from_date" name="from_date" required><br><br>
           
            <input type="submit" name="submit" value="Submit">
        </form>
    </div>

    <script src="js/profileagent.js"></script>
   
    <?php
    // Include the database connection file
    require_once "connection.php";
    
    // Check if form is submitted
    if(isset($_POST['submit'])) {
        // Retrieve form data
        $traveller_id = $_POST['traveller_id'];
        $agent_name = $_POST['agent_name']; // Retrieve agent name from the form
        $package_id = $_POST['package_id'];
        $no_adult = $_POST['no_of_adults'];
        $no_child = $_POST['no_of_children'];
        $from_date = $_POST['from_date'];
        $total_amount = $_POST['total_amount'];

        // Query to fetch the destination ID associated with the selected package
        $query = "SELECT desti FROM packages WHERE id = $package_id";
        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $selected_package_desti = $row['desti'];

            // Insert data into the database
            $sql = "INSERT INTO booking (traveller_id, agent, destination, package_id, no_adult, no_child, from_date, total_amount, status) VALUES ('$traveller_id', '$agent_name', '$selected_package_desti', '$package_id', '$no_adult', '$no_child', '$from_date', '$total_amount', 'Pending')";
            
            if (mysqli_query($con, $sql)) {
                echo "Booking successfully stored in the database.";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($con);
            }
        } else {
            echo "Error: Failed to fetch destination ID associated with the selected package.";
        }
    }
    ?>
</body>
</html>
