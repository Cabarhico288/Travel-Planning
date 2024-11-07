
<?php
// Include the session initialization file
require_once "session_start.php";
// Include the database connection file
require_once "connection.php";

// Check if user is logged in
if(!isset($_SESSION['email']) || !isset($_SESSION['password'])){
    header('Location: login-user.php');
    exit; // Stop further execution
}

$email = $_SESSION['email'];
$password = $_SESSION['password'];

// Check if connection established successfully
if ($con === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$sql = "SELECT * FROM usertable WHERE email = '$email'";
$run_Sql = mysqli_query($con, $sql);

if($run_Sql){
    $fetch_info = mysqli_fetch_assoc($run_Sql);
    $_SESSION['name'] = $fetch_info['name']; // Store the user's name in session
    $userID = $fetch_info['id']; // Retrieve the user ID
    $_SESSION['user_id'] = $userID; // Set the user ID in the session
    $status = $fetch_info['status'];
    $code = $fetch_info['code'];
    $legit = $fetch_info['legit']; // New column to check if the account is legit
    if($status == "verified"){
        if($code != 0){
            header('Location: reset-code.php');
            exit; // Stop further execution
        }
    } else {
        header('Location: user-otp.php');
        exit; // Stop further execution
    }
} else {
    echo "Error: " . mysqli_error($con);
}


// Close the database connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Agent Dashboard</title>
    <link rel="stylesheet" href="css/dropdown.css">
    <link rel="stylesheet" href="css/agentcss.css">
    <link rel="stylesheet" href="css/message.css">
    <link rel="stylesheet" href="css/slide.css">
</head>
<body>


<br>
<br>
<br>
<br>
<br>
<br>
<div class="header">
    <h1>Travel Agent Dashboard</h1>
    <h1>Welcome <?php echo isset($_SESSION['name']) ? $_SESSION['name'] : ''; ?></h1>
    <div class="dropdown">
        <button onclick="toggleDropdown()" class="dropbtn">
            <img src="img/profile.jpg" alt="Profile Icon">
        </button>
        <div id="dropdown-menu" class="dropdown-content">
        <a href="agentprofile.php" onclick="fetchUserData();" >Profile</a>
            <a href="logout-user.php">Logout</a>
        </div>
    </div>
</div>

<script src="js/agentprofile.js"></script>
<div class="sidebar <?php echo $legit == 2 ? 'disabled' : ''; ?>">
    <div class="sidebar-cover"></div>
    <ul>
        <?php if($legit != 2): ?>
            <li><a href="#" onclick="<?php echo $legit == 2 ? 'showMessage()' : 'toggleAddPlace()'; ?>">Add Place</a></li>
            <li><a href="#" onclick="<?php echo $legit == 2 ? 'showMessage()' : 'toggleBookingData()'; ?>">Travelers Book</a></li>
            <li><a href="#" onclick="<?php echo $legit == 2 ? 'showMessage()' : 'togglePackageManagement()'; ?>">Package Management</a></li>
        <?php else: ?>
            <li><span>Add Place</span></li>
            <li><span>Travelers Book</span></li>
            <li><span>Package Management</span></li>
        <?php endif; ?>
    </ul>
</div>


<div> 
    <div >
    </div>

        <?php if($legit == 2): ?>
            <!-- Message for unlegit accounts -->
            <h1 class="error-message">Please upload your credentials on your profile first to make transactions.</h1>
        <?php else: ?>
            <div id="placeContainer" style="display: none;">
                <!-- Placeholder for dynamically added places -->
                <?php include('addplace.php'); ?>
            </div>
            <div id="packageContainer" style="display: none;">
                <!-- Placeholder for dynamically added packages -->
                <?php include('addPackages.php'); ?>
            </div>
            <div id="bookingContainer" style="display: none;">
                <!-- Placeholder for dynamically loaded booking data -->
                <?php include('booking.php'); ?>
            </div>
            
        <?php endif; ?>
    </div>
</div>


    <script src="js/script.js"></script>
    <script>
        
        function toggleAddPlace() {
    var placeContainer = document.getElementById('placeContainer');
    var packageContainer = document.getElementById('packageContainer');
    var bookingContainer = document.getElementById('bookingContainer');

    // Hide booking container if it's visible
    if (bookingContainer.style.display === 'block') {
        bookingContainer.style.display = 'none';
    }

    // Hide package container if it's visible
    if (packageContainer.style.display === 'block') {
        packageContainer.style.display = 'none';
    }

    // Toggle display of place container
    if (placeContainer.style.display === 'none') {
        placeContainer.style.display = 'block';
    } else {
        placeContainer.style.display = 'none';
    }
}

function togglePackageManagement() {
    var placeContainer = document.getElementById('placeContainer');
    var packageContainer = document.getElementById('packageContainer');
    var bookingContainer = document.getElementById('bookingContainer');

    // Hide booking container if it's visible
    if (bookingContainer.style.display === 'block') {
        bookingContainer.style.display = 'none';
    }

    // Hide place container if it's visible
    if (placeContainer.style.display === 'block') {
        placeContainer.style.display = 'none';
    }

    // Toggle display of package container
    if (packageContainer.style.display === 'none') {
        packageContainer.style.display = 'block';
    } else {
        packageContainer.style.display = 'none';
    }
}

function toggleBookingData() {
    var placeContainer = document.getElementById('placeContainer');
    var packageContainer = document.getElementById('packageContainer');
    var bookingContainer = document.getElementById('bookingContainer');

    // Hide place and package containers if they're visible
    if (placeContainer.style.display === 'block') {
        placeContainer.style.display = 'none';
    }
    if (packageContainer.style.display === 'block') {
        packageContainer.style.display = 'none';
    }

    // Toggle display of booking container
    if (bookingContainer.style.display === 'none') {
        bookingContainer.style.display = 'block';
    } else {
        bookingContainer.style.display = 'none';
    }
}


     // Function to toggle dropdown menu
     function toggleDropdown() {
            var dropdownMenu = document.getElementById("dropdown-menu");
            if (dropdownMenu.style.display === "block") {
                dropdownMenu.style.display = "none";
            } else {
                dropdownMenu.style.display = "block";
            }
        }

      
        // Close the dropdown menu if the user clicks outside of it
        window.onclick = function(event) {
            if (!event.target.matches('.dropbtn')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.style.display === "block") {
                        openDropdown.style.display = "none";
                    }
                }
            }
        }
    </script>
</body>
</html>

<?php


// Function to add a place
function addPlace($con) {
    // Retrieve data from the form
    $imageName = $_FILES['image']['name'];
    $imageTmp = $_FILES['image']['tmp_name'];
    $placeName = mysqli_real_escape_string($con, $_POST['placename']);
    $location = mysqli_real_escape_string($con, $_POST['location']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $agentName = isset($_SESSION['name']) ? $_SESSION['name'] : ''; // Use $_SESSION['name'] instead of $_SESSION['addname']

    // Create uploads directory if it doesn't exist
    if (!file_exists('uploads')) {
        mkdir('uploads', 0777, true);
    }

    // Move uploaded image to a folder
    $targetDir = "uploads/";
    $targetFilePath = $targetDir . basename($imageName);
    move_uploaded_file($imageTmp, $targetFilePath);

    // Insert data into the database, including the agent's name
    $insert_data = "INSERT INTO destination (image, placename, location, description, agent_name)
                    VALUES ('$imageName', '$placeName', '$location', '$description', '$agentName')";

    $data_check = mysqli_query($con, $insert_data);

    if(!$data_check){
        // Handle the error if insertion fails
        die("Failed to store data in the database: " . mysqli_error($con));
    }
}?>
<div id="profileFormContainer" class="profile-form-container" style="display: none; padding: 20px; background-color: #f9f9f9; border: 1px solid #ccc; border-radius: 5px;">
    <!-- Placeholder for dynamically loaded profile form -->
    <?php include('agentprofile.php'); ?>
</div>
