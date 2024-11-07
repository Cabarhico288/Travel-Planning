<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Information</title>
  <link rel="stylesheet" type="text/css" href="../css/admin.css">
</head>
<body>

<div class="header">
  <h1>User Information</h1>
</div>

<div class="content">
  <table id="userTable">
    <thead>
      <tr>
        <th>Name</th>
        <th>Avatar</th>
        <th>Contact</th>
        <th>Email</th>
        <th>Password</th>
        <th>Code</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
    <?php
    // Include the database connection file
    require_once "connection.php";

    // Query to retrieve data from the usertable
    $query = "SELECT * FROM usertable";
    $result = mysqli_query($con, $query);

    // Check if there are any rows returned
    if (mysqli_num_rows($result) > 0) {
        // Output data of each row
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['avatar']}</td>";
            echo "<td>{$row['contact']}</td>";
            echo "<td>{$row['email']}</td>";
            echo "<td>{$row['password']}</td>";
            echo "<td>{$row['code']}</td>";
            echo "<td>{$row['status']}</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No data found</td></tr>";
    }

    // Close database connection
    mysqli_close($con);
    ?>
    </tbody>
  </table>
</div>

</body>
</html>
