<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Profile View</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .profile-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
        }
        .profile-image {
            text-align: center;
        }
        .profile-image img {
            max-width: 200px;
            border-radius: 50%;
        }
        .profile-details {
            margin-top: 20px;
        }
        .profile-details h2 {
            color: #333;
            margin-bottom: 10px;
        }
        .profile-details p {
            color: #666;
            margin-bottom: 5px;
        }
        .certificate-container {
            margin-top: 20px;
        }
        .certificate-image img {
            max-width: 100%;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .delete-button {
            background-color: #ff0000;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<?php
// Include necessary files and start the session
require_once "connection.php";

// Check if the 'user_id' parameter is set in the URL
if (isset($_GET['user_id'])) {
    // Get the user ID from the URL parameter
    $userId = $_GET['user_id'];

    // Fetch user data based on the user ID
    $query = "SELECT * FROM usertable WHERE id = '$userId'";
    $result = mysqli_query($con, $query);

    // Check if user data is found
    if ($result && mysqli_num_rows($result) > 0) {
        $userData = mysqli_fetch_assoc($result);

        // Convert the blob data to a data URL for the avatar image
        $avatarData = base64_encode($userData['avatar']);
        $avatarSrc = 'data:image/jpeg;base64,' . $avatarData;

        // Fetch certificate data using the cred column
        $credId = $userData['cred'];
        $certificateQuery = "SELECT certificate FROM acredentials WHERE id = '$credId'";
        $certificateResult = mysqli_query($con, $certificateQuery);

        // Check if certificate data is found
        if ($certificateResult && mysqli_num_rows($certificateResult) > 0) {
            $certificateData = mysqli_fetch_assoc($certificateResult);
            $certificateSrc = 'data:image/jpeg;base64,' . base64_encode($certificateData['certificate']);
        }
?>
    <div class="profile-container">
    
        <div class="profile-image">
        
            <!-- Display the user's avatar -->
            <img src="<?php echo $avatarSrc; ?>" alt="Agent Avatar">
        </div>
        <div class="profile-details">
            <h2>Agent Profile</h2>
            <!-- Display user details -->
            <p><strong>Name:</strong> <?php echo $userData['name']; ?></p>
            <p><strong>Email:</strong> <?php echo $userData['email']; ?></p>
            <p><strong>Contact:</strong> <?php echo $userData['contact']; ?></p>
        
            <!-- Additional profile details can be added here -->
            <?php if (isset($certificateSrc)): ?>
            <div class="certificate-container">
            <p><strong>Certificate</strong> </p>
                <div class="certificate-image">
                    <!-- Display the certificate image -->
                    <img src="<?php echo $certificateSrc; ?>" alt="Certificate">
                    <!-- Add delete button if needed -->
                    <!-- <button class="delete-button">X</button> -->
                </div>
            </div>
            <?php else: ?>
            <p>No certificate uploaded</p>
            <?php endif; ?>
        </div>
    </div>
<?php
    } else {
        // Display message if user data is not found
        echo "<p>User not found.</p>";
    }
} else {
    // Display message if 'user_id' parameter is not set
    echo "<p>No user ID specified.</p>";
}
?>
</body>
</html>
