<?php
// Include necessary files and start the session
require_once "connection.php";
require_once "session_start.php";

// Fetch user data from the database
$email = $_SESSION['email'];
$sql = "SELECT u.cred, a.id as credential_id
        FROM usertable u
        LEFT JOIN acredentials a ON u.cred = a.id
        WHERE email = '$email'";
$result = mysqli_query($con, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $userData = mysqli_fetch_assoc($result);
} else {
    // Handle error if user data is not found
    $userData = array(); // Default empty array
}

// Retrieve the certificate blob data based on the primary key
if (!empty($userData['credential_id'])) {
    $credentialId = $userData['credential_id'];
    $certificateQuery = "SELECT certificate FROM acredentials WHERE id = $credentialId";
    $certificateResult = mysqli_query($con, $certificateQuery);
    if ($certificateResult && mysqli_num_rows($certificateResult) > 0) {
        $certificateData = mysqli_fetch_assoc($certificateResult);
        $certificateBlob = $certificateData['certificate'];
    } else {
        // Handle error if certificate data is not found
        $certificateBlob = null;
    }
} else {
    // Handle error if credential ID is not found
    $certificateBlob = null;
}
?>


<?php
// Include necessary files and start the session
require_once "connection.php";
require_once "session_start.php";

// Fetch user data from the database
$email = $_SESSION['email'];
$sql = "SELECT * FROM usertable WHERE email = '$email'";
$result = mysqli_query($con, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $userData = mysqli_fetch_assoc($result);
} else {
    // Handle error if user data is not found
    $userData = array(); // Default empty array
}

// Display the avatar image if available
if (!empty($userData['avatar'])) {
    $avatarData = base64_encode($userData['avatar']); // Convert blob data to base64
    $avatarSrc = 'data:image/jpeg;base64,' . $avatarData; // Set image source with base64 data
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Check if files are uploaded
    if (!empty($_FILES['credentials']['name'][0])) {
        // Loop through each uploaded file
        foreach ($_FILES['credentials']['tmp_name'] as $key => $tmp_name) {
            // Get file details
            $file_name = $_FILES['credentials']['name'][$key];
            $file_tmp = $_FILES['credentials']['tmp_name'][$key];

            // Read file data
            $file_data = file_get_contents($file_tmp);
            // Prevent SQL injection
            $file_data = mysqli_real_escape_string($con, $file_data);

            // Insert file data into database
            $insert_query = "INSERT INTO acredentials (certificate) VALUES ('$file_data')";
            $insert_result = mysqli_query($con, $insert_query);
            if ($insert_result) {
                // Get the ID of the inserted credentials
                $credentials_id = mysqli_insert_id($con);

                // Update user table to store the credentials ID
                $update_query = "UPDATE usertable SET cred = '$credentials_id' WHERE email = '$email'";
                $update_result = mysqli_query($con, $update_query);
                if (!$update_result) {
                    echo "Error updating user table: " . mysqli_error($con);
                }
            } else {
                echo "Error inserting credentials: " . mysqli_error($con);
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Profile</title>
    <link rel="stylesheet" href="css/agentdesign.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.css">
</head>
<body>

<form action="process_profile.php" method="post" enctype="multipart/form-data">
    <div class="avatar-wrapper">
        <label for="avatar" class="choose-avatar">Choose Avatar:</label>
        <input type="file" id="avatar" name="avatar" accept="image/*" onchange="displayAvatar(this);">
        <img id="avatar-img" src="<?php echo $avatarSrc; ?>" alt="Avatar" title="Avatar">
    </div>
    <div class="form-group">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" value="<?php echo isset($userData['name']) ? $userData['name'] : ''; ?>" required>
    </div>
    <div class="form-group">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?php echo isset($userData['email']) ? $userData['email'] : ''; ?>" required>
    </div>
    <div class="form-group">
        <label for="Username">Username:</label><br>
        <input type="text" id="username" name="username" value="<?php echo isset($userData['name']) ? $userData['name'] : ''; ?>" required>
    </div>
    <div class="form-group">
        <label for="Number">Contact Number:</label><br>
        <input type="Text" id="number" name="number" value="<?php echo isset($userData['contact']) ? $userData['contact'] : ''; ?>" required>
    </div>
    <div class="form-group">
    <label for="Number">Credentials</label><br>
    <!-- Display the certificate image -->
    <?php if (!empty($certificateBlob)): ?>
        <div class="certificate-container">
    <div class="certificate-image">
        <img src="data:image/jpeg;base64,<?php echo base64_encode($certificateBlob); ?>" alt="Certificate">
        <button class="delete-button">X</button>
    </div>
</div>

    <?php else: ?>
        <p>No certificate uploaded</p>
    <?php endif; ?>
</div>


    <input id="infoupdate" type="submit" name="submit" value="Update">
</form>

<form action="" method="post" enctype="multipart/form-data">
    <div class="credentials-wrapper">
        <label for="credentials" class="choose-credentials">Update Credentials:</label>
        <div class="upload-link" onclick="document.getElementById('credentials').click();">
            Click here to upload additional credentials
        </div>
        <input type="file" id="credentials" name="credentials[]" accept="image/*" multiple onchange="displayCredentials(event);" style="display: none;">
    </div>
    <div class="container">
        <div id="credentials-preview" class="image-preview">
            <!-- Credential images will be displayed here -->
        </div>
        <input id="credentials-update" type="submit" name="submit" value="Add Credentials">
    </div>
</form>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
// Function to display SweetAlert message
function displaySweetAlert(message, icon) {
    Swal.fire({
        title: message,
        icon: icon,
        showConfirmButton: false,
        timer: 1500 // Timer to auto-close the alert after 1.5 seconds
    });
}

// Check if the form was submitted and if files were uploaded
<?php if (isset($_POST['submit']) && !empty($_FILES['credentials']['name'][0])): ?>
    // Display SweetAlert message after successful upload
    displaySweetAlert("Image uploaded successfully", "success");
<?php endif; ?>
</script>
</body>
<script src="js/agentprofile.js"></script>
</html>
