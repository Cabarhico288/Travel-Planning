<?php
// Include database connection file
require_once "controllerUserData.php";

// Check if connection established successfully
if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

// Call the function to add a place
if (isset($_POST['add-place'])) {
    addPlace($con);
    // Redirect to a success page or perform any other action after storing the data
    header("Location: Agent.php");
    exit();
}

// Function to delete a place
if (isset($_POST['delete-place'])) {
    $placeId = $_POST['delete-place-id'];
    
    // Delete place from the database
    $delete_query = "DELETE FROM destination WHERE id = $placeId";
    $result = mysqli_query($con, $delete_query);

    if ($result) {
        echo "<script>
                setTimeout(function() {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Place deleted successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'Agent.php';
                    });
                }, 100);
              </script>";
    } else {
        // Handle the error if deletion fails
        die("Failed to delete place: " . mysqli_error($con));
    }
}

// Function to save edits to the database
function saveEdit($con) {
    $placeId = $_POST['edit-place-id'];
    $placeName = mysqli_real_escape_string($con, $_POST['edit-placename']);
    $location = mysqli_real_escape_string($con, $_POST['edit-location']);
    $description = mysqli_real_escape_string($con, $_POST['edit-description']);
    $imagePath = '';

    // Check if a new image file is uploaded
    if (isset($_FILES['edit-image']) && $_FILES['edit-image']['name'] != '') {
        $targetDir = "uploads/";
        $imagePath = $targetDir . basename($_FILES["edit-image"]["name"]);
        move_uploaded_file($_FILES["edit-image"]["tmp_name"], $imagePath);
    }

    // Update other details in the database
    $update_query = "UPDATE destination SET placename=?, location=?, description=?";
    if ($imagePath) {
        $update_query .= ", image='$imagePath'";
    }
    $update_query .= " WHERE id=?";
    $update_statement = mysqli_prepare($con, $update_query);
    mysqli_stmt_bind_param($update_statement, "sssi", $placeName, $location, $description, $placeId);
    $result = mysqli_stmt_execute($update_statement);

    if ($result) {
        echo "<script>
                setTimeout(function() {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Place updated successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'Agent.php';
                    });
                }, 100);
              </script>";
    } else {
        // Handle the error if update fails
        die("Failed to save edit: " . mysqli_error($con));
    }
}

if (isset($_POST['save-edit'])) {
    saveEdit($con);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Booking Place</title>
    <!-- Include jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include SweetAlert library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" href="css/addplace.css">
    <style>
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
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 10px;
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
    <div class="place-container">
        <div class="place-form-container">
            <form action="" method="post" enctype="multipart/form-data">
                <!-- Your form for adding a new booking place goes here -->
                <input type="hidden" id="agent_name" name="agent_name" value="<?php echo isset($_SESSION['name']) ? $_SESSION['addname'] : ''; ?>">
                <div class="form-group">
                    <label for="image">Image:</label>
                    <input type="file" id="image" name="image" accept="image/*" required>
                </div>
                <div class="form-group">
                    <label for="placename">Place Name:</label>
                    <input type="text" id="placename" name="placename" placeholder="Enter the name of the booking place" required>
                </div>
                <div class="form-group">
                    <label for="location">Location:</label>
                    <input type="text" id="location" name="location" placeholder="Enter the location of the booking place" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" placeholder="Enter a description of the booking place" required></textarea>
                </div>
                <button type="submit" name="add-place">Add Booking Place</button>
            </form>
        </div>
    </div>
    <div class="display-container">
        <table>
            <thead>
                <tr>
                    <th>Place Image</th>
                    <th>Place Name</th>
                    <th>Location</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query to retrieve stored booking places from the database for the specific agent
                $query = "SELECT * FROM destination WHERE agent_name = '{$_SESSION['name']}'";
                $result = mysqli_query($con, $query);

                // Loop through each stored booking place uploaded by the specific agent and display it in a table row
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <td><img src="uploads/<?php echo $row['image']; ?>" alt="Place Image" style="width: 100px; height: 100px;"></td>
                    <td><?php echo $row['placename']; ?></td>
                    <td><?php echo $row['location']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td>
                        <form method="post" onsubmit="return confirm('Are you sure you want to delete this place?');">
                            <input type="hidden" name="delete-place-id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="delete-btn" name="delete-place">Delete</button>
                            <button class="edit-btn" onclick="showEditPopup(event, <?php echo $row['id']; ?>)">Edit</button>
                        </form>
                    </td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- Popup modal for editing -->
    <?php
    $query = "SELECT * FROM destination";
    $result = mysqli_query($con, $query);
    while ($row = mysqli_fetch_assoc($result)) {
    ?>
    <div id="editModal_<?php echo $row['id']; ?>" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditPopup(<?php echo $row['id']; ?>)">&times;</span>
            <form method="post" action="" enctype="multipart/form-data">
                <input type="hidden" name="edit-place-id" value="<?php echo $row['id']; ?>">
                <div class="form-group">
                    <label for="edit-image">Image:</label>
                    <input type="file" id="edit-image" name="edit-image" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="edit-placename">Place Name:</label>
                    <input type="text" id="edit-placename" name="edit-placename" value="<?php echo $row['placename']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="edit-location">Location:</label>
                    <input type="text" id="edit-location" name="edit-location" value="<?php echo $row['location']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="edit-description">Description:</label>
                    <textarea id="edit-description" name="edit-description" required><?php echo $row['description']; ?></textarea>
                </div>
                <button type="submit" name="save-edit">Save</button>
            </form>
        </div>
    </div>
    <?php
    }
    ?>
    <script>
        // JavaScript functions to show and close edit popup
        function showEditPopup(event, placeId) {
            event.preventDefault(); // Prevent the default behavior of the button click
            var modal = document.getElementById('editModal_' + placeId);
            modal.style.display = "block";
        }

        function closeEditPopup(placeId) {
            var modal = document.getElementById('editModal_' + placeId);
            modal.style.display = "none";
        }
    </script>
</body>
</html>
