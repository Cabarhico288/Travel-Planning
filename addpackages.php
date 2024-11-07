<?php
// Include database connection file
require_once "controllerUserData.php";

// Check if form is submitted
if(isset($_POST['delete-place'])) {
    $placeId = $_POST['delete-place-id'];
    
    /// Delete place from the database
$delete_query = "DELETE FROM packages WHERE id = $placeId";
$result = mysqli_query($con, $delete_query);

if(!$result) {
    // Handle the error if deletion fails
    die("Failed to delete place: " . mysqli_error($con));
}

}

?>


<div class="package-container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-outline-primary">
                <div class="card-body">
                    <form action="Addpackages.php" method="post">
                        <div class="form-body">
                            <h3 class="card-title m-t-15">Package Info</h3>
                            <hr>
                            <div class="row p-t-20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Destination</label>
                                        <select class="form-control" name="destination" required>
                                            <?php
                                            // Query to fetch destinations associated with the logged-in agent
                                            $agentName = isset($_SESSION['name']) ? $_SESSION['name'] : '';
                                            $query = "SELECT id, placename FROM destination WHERE agent_name = '$agentName'";
                                            $result = mysqli_query($con, $query);

                                            // Check if any destination is found
                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo "<option value='" . $row['id'] . "'>" . $row['placename'] . "</option>";
                                                }
                                            } else {
                                                echo "<option value=''>No destinations found</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Package Name </label>
                                        <input type="text" class="form-control" name="pname" pattern="[a-zA-Z][a-zA-Z ]+" placeholder="Enter Package Name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description">Description :</label>
                                        <textarea id="description" name="description" placeholder="Enter a description of the booking place" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="childprice">Child Price :</label>
                                        <input type="number" id="childprice" name="childprice" placeholder="Enter Child Price" required></input>
                                    </div>
                                    <div class="form-group">
                                        <label for="adultprice">Adult Price :</label>
                                        <input type="number" id="adultprice" name="adultprice" placeholder="Enter Adult Price" required></input>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success" name="submit"><i class="fa fa-check"></i> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include database connection file
require_once "controllerUserData.php";

// Check if connection established successfully
if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

// Check if form is submitted
if(isset($_POST['submit'])){
    // Get form data
    $destination = mysqli_real_escape_string($con, $_POST['destination']);
    $agentName = isset($_SESSION['name']) ? $_SESSION['name'] : ''; // Fetch agent's name from session
    $packageName = mysqli_real_escape_string($con, $_POST['pname']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $childPrice = mysqli_real_escape_string($con, $_POST['childprice']); // Get child price
    $adultPrice = mysqli_real_escape_string($con, $_POST['adultprice']); // Get adult price
    
    // Insert data into database
    $insert_query = "INSERT INTO packages (desti, agent_name, pname, description, child_price, adult_price) VALUES ('$destination', '$agentName', '$packageName', '$description', '$childPrice', '$adultPrice')";
    $insert_result = mysqli_query($con, $insert_query);
    
    // Check if insertion was successful
    if($insert_result) {
        // Redirect to a success page or perform any other action after storing the data
        header("Location: Agent.php");
        exit();
    } else {
        // Handle the error if insertion fails
        die("Failed to store data in the database: " . mysqli_error($con));
    }
}

?>

<?php
// Read operation
// Read operation with filter for specific agent
$query = "SELECT p.pname, p.description, p.child_price, p.adult_price, d.placename 
          FROM packages p 
          JOIN destination d ON p.desti = d.id 
          WHERE p.agent_name = '{$_SESSION['name']}'";
$result = mysqli_query($con, $query);
?>
<div class="container-fluid mt-5">
    <h3 class="mb-3">Package List</h3>
    <table class="table table-bordered" style="width: 100%;"> <!-- Set the table width to 100% -->
        <thead>
            <tr>    
                    <th style="width: 15%;">Place Name</th> <!-- Set the width of Place Name column -->
                    <th style="width: 15%;">Package Name</th> <!-- Set the width of Package Name column -->
                    <th style="width: 40%;">Description</th> <!-- Set the width of Description column -->
                    <th style="width: 10%;">Action</th> <!-- Set the width of Action column -->
                    <th style="width: 10%;">Child Price</th> <!-- Set the width of Child Price column -->
                    <th style="width: 10%;">Adult Price</th> <!-- Set the width of Adult Price column -->
            </tr>
        </thead>
        <tbody>
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?php echo $row['placename']; ?></td>
        <td><?php echo $row['pname']; ?></td>
        <td style="text-align: justify;"><?php echo nl2br($row['description']); ?></td> <!-- Use nl2br to preserve formatting -->
        <td><?php echo $row['child_price']; ?></td> <!-- Display Child Price -->
        <td><?php echo $row['adult_price']; ?></td> <!-- Display Adult Price -->
        <td>
        <button class="edit-btn btn btn-primary btn-sm mr-2" onclick="openEditModal(<?php echo $row['id']; ?>)">Edit</button>
        </td>
    </tr>
<?php } ?>

        </tbody>
    </table>
</div>

<!-- Popup modal for editing -->
<?php
$query = "SELECT * FROM packages";
$result = mysqli_query($con, $query);
while ($row = mysqli_fetch_assoc($result)) {
    ?>
    <div id="editModal_<?php echo $row['id']; ?>" class="modalpackage" style="display: none;">
        <div class="package-modal-content">
            <span class="close" onclick="closeEditModal(<?php echo $row['id']; ?>)">&times;</span>
            <form action="Agent.php" method="POST">
                <input type="hidden" name="edit-place-id" value="<?php echo $row['id']; ?>">
                <div class="form-group">
                    <label class="control-label">Package Name </label>
                    <input type="text" class="form-control" name="edit-pname" value="<?php echo $row['pname']; ?>" pattern="[a-zA-Z][a-zA-Z ]+" placeholder="Enter Package Name" required>
                </div>
               
                <button type="submit" class="btn btn-success save-btn" name="save-edit">Save</button>

            </form>
        </div>
    </div>
<?php } ?>

<script>
 function openEditModal(packageId) {
  var modal = document.getElementById('editModal_' + packageId);
  modal.style.display = "block";
}

    function closeEditModal(packageId) {
        var modal = document.getElementById('editModal_' + packageId);
        modal.style.display = "none";
    }
</script>



<?php
// Add the following code at the beginning of the PHP section

// Fetch package data based on package ID
if(isset($_GET['fetchPackage'])) {
    $packageId = $_GET['fetchPackage'];
    $fetch_query = "SELECT * FROM packages WHERE id=$packageId";
    $fetch_result = mysqli_query($con, $fetch_query);
    if($fetch_result && mysqli_num_rows($fetch_result) > 0) {
        $packageData = mysqli_fetch_assoc($fetch_result);
        echo json_encode($packageData);
        exit(); // Stop further execution
    } else {
        // Handle error
        echo json_encode(array('error' => 'Failed to fetch package data'));
        exit(); // Stop further execution
    }
}

?>

<?php
// Include database connection file
require_once "controllerUserData.php";

// Check if connection established successfully
if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

// Check if form is submitted
if(isset($_POST['save-edit'])) {
    // Get edited data from the form
    $packageId = $_POST['edit-place-id'];
    $editedPname = mysqli_real_escape_string($con, $_POST['edit-pname']);
    

    // Update data in the database
    $update_query = "UPDATE packages SET pname='$editedPname', WHERE id='$packageId'";
    $update_result = mysqli_query($con, $update_query);

    if($update_result) {
        // You can optionally display a success message or perform any other action here
    } else {
        // Handle the error if update fails
        echo "Failed to update data in the database: " . mysqli_error($con);
    }
}
?>