<?php
require_once "controllerUserData.php"; 

// Fetch user info based on the email stored in the session
$fetch_info_query = "SELECT * FROM usertable WHERE email = '{$_SESSION['email']}'";
$fetch_info_result = mysqli_query($con, $fetch_info_query);
$fetch_info = mysqli_fetch_assoc($fetch_info_result);

// Store the user's ID in the session
$_SESSION['traveller_id'] = $fetch_info['id'];

// Convert avatar blob data to base64 if available
$avatarSrc = '';
if (!empty($fetch_info['avatar'])) {
    $avatarData = base64_encode($fetch_info['avatar']);
    $avatarSrc = 'data:image/jpeg;base64,' . $avatarData;
}
// Fetch credential photo if available
$credPhotoSrc = '';
if (!empty($fetch_info['cred'])) {
    $credId = $fetch_info['cred'];
    $cred_query = "SELECT certificate FROM acredentials WHERE id = '$credId'";
    $cred_result = mysqli_query($con, $cred_query);
    if ($cred_row = mysqli_fetch_assoc($cred_result)) {
        $credPhotoData = base64_encode($cred_row['certificate']);
        $credPhotoSrc = 'data:image/jpeg;base64,' . $credPhotoData;
    }
}
?>
<?php
// Check if the user's account status is pending
if ($fetch_info['legit'] == 2) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            var overlay = document.createElement('div');
            overlay.setAttribute('id', 'overlay2');
            overlay.setAttribute('class', 'overlay');
            document.body.appendChild(overlay);

            var modal = document.createElement('div');
            modal.setAttribute('class', 'modal2');
            modal.innerHTML = '<h2>Your account is pending</h2><p>Please wait for the admin to approve your account to make a transaction</p>';
            document.body.appendChild(modal);
        });
    </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traveler Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/traveler.css">
    <link rel="stylesheet" href="css/container.css">
    <link rel="stylesheet" href="css/popup.css">
    <script src="js/filter.js" defer></script> <!-- Include the JavaScript file -->
    <link rel="stylesheet" href="css/modal.css"> <!-- Add this line for modal CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
    href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap"
    rel="stylesheet">
</head>
<body id="top">
  <header class="header" data-header>
    <div class="header-btn-group">
      <button class="nav-open-btn" aria-label="Open Menu" data-nav-open-btn>
        <ion-icon name="menu-outline"></ion-icon>
      </button>
    </div>
    <div class="header-bottom">
      <div class="container">
        <nav class="navbar" data-navbar>
          <div class="navbar-top">
            <button class="nav-close-btn" aria-label="Close Menu" data-nav-close-btn>
              <ion-icon name="close-outline"></ion-icon>
            </button>
          </div>
          <ul class="navbar-list">
            <li>
              <a href="#home" class="navbar-link" data-nav-link>home</a>
            </li>
            <li>
              <a href="#destination" class="navbar-link" data-nav-link>destination</a>
            </li>
            <li>
              <a href="traveler/inquire.php" class="navbar-link" data-nav-link>Inquired Destination</a>
            </li>
          </ul>
        </nav>
        <div class="dropdown">
          <button onclick="toggleDropdown()" class="dropbtn">
            <?php if ($avatarSrc): ?>
              <img src="<?php echo $avatarSrc; ?>" alt="Profile Icon" class="profile-avatar" >
            <?php else: ?>
              <img src="img/profile.jpg" alt="Profile Icon">
            <?php endif; ?>
          </button>
          <div id="dropdown-menu" class="dropdown-content">
            <a href="#" onclick="showProfileModal()">Profile</a>
            <a href="logout-user.php">Logout</a>
          </div>
        </div>
      </div>
    </div>
  </header>
  <main>
  <article>
  <section class="hero" id="home">
    <div class="container">
      <h2 class="h1 hero-title">Welcome Traveler!</h2>
      <?php if (isset($fetch_info) && $fetch_info != null): ?>
        <p class="h1 hero-title">
          <?php echo $fetch_info['name']; ?>
         
        </p>
      <?php endif; ?>
    </div>
  </section>
  <br>
  <div class="dropdowns-containersearch">
  <div class="dropdownsearch">
    <select id="dropdown1" onchange="filterPackages()">
      <option value="" disabled selected>Activities</option>
      <option value="Hiking">Hiking</option>
      <option value="Swimming">Swimming</option>
      <option value="Jetski">Jetski</option>
    </select>
  </div>
  <div class="dropdownsearch">
    <select id="dropdown2" onchange="filterPackages()">
      <option value="" disabled selected>Available Flights</option>
      <option value="Economy">Economy</option>
      <option value="Premium Economy">Premium Economy</option>
      <option value="Business">Business</option>
      <option value="First Class">First Class</option>
    </select>
  </div>
  <div class="dropdownsearch">
    <select id="dropdown3" onchange="filterPackages()">
      <option value="" disabled selected>Accommodations</option>
      <option value="Resort">Resort</option>
      <option value="Hotel">Hotel</option>
      <option value="Hostel">Hostel</option>
    </select>
  </div>
</div>
<br>
  <div class="search-bar">
    <input type="text" class="search-input" id="search-input" placeholder="Enter destination">
    <button class="search-btn" id="search-btn" onclick="filterPackages()">Search</button>
  </div>
  

  <div id="display-container" class="display-container">
    <!-- This will be populated with filtered results -->
  </div>
  
  <section class="popular" id="destination">
    <div class="container">
      <p class="section-subtitle">Uncover place</p>
      <h2 class="h2 section-title">Popular destination and Packages</h2>
    </div>
  
  </section>
  <div id="popular-container" class="popular-container">
        <!-- This will be populated with popular destinations -->
      </div>
</article>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    $('#search-btn').on('click', function() {
      var searchQuery = $('#search-input').val();
      $.ajax({
        url: 'traveler/search.php',
        type: 'GET',
        data: { query: searchQuery },
        success: function(response) {
          $('#display-container').html(response);
        }
      });
    });
  });
    // Fetch popular destinations on page load
    $(document).ready(function() {
      fetchPopularDestinations();
    });

    function fetchPopularDestinations() {
      $.ajax({
        url: 'traveler/popular_destinations.php',
        method: 'GET',
        success: function(data) {
          $('#popular-container').html(data);
        },
        error: function(error) {
          console.error('Error fetching popular destinations:', error);
        }
      });
    }
</script>


<script>
document.getElementById('search-btn').addEventListener('click', function() {
  var query = document.getElementById('search-input').value;
  var xhr = new XMLHttpRequest();
  xhr.open('GET', 'search.php?q=' + encodeURIComponent(query), true);
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
      document.getElementById('display-container').innerHTML = xhr.responseText;
    }
  };
  xhr.send();
});
</script>

  </main>

  
<!-- Profile Modal -->
<div id="profileModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeProfileModal()">&times;</span>
        <h2>User Profile</h2>
        <form id="profileForm" method="POST" enctype="multipart/form-data" action="traveler/update_profile.php">
            <table>
                <tr>
                    <th>Avatar</th>
                    <td>
                        <div class="avatar-container">
                            <?php if ($avatarSrc): ?>
                                <img id="avatar-preview" src="<?php echo $avatarSrc; ?>" alt="Avatar">
                            <?php else: ?>
                                <img id="avatar-preview" src="" alt="Avatar">
                            <?php endif; ?>
                        </div>
                        <input type="file" name="avatar" accept="image/*" onchange="previewImage(event, 'avatar-preview')">
                    </td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td><?php echo $fetch_info['name']; ?></td>
                </tr>
                <tr>
                    <th>Contact</th>
                    <td><?php echo $fetch_info['contact']; ?></td>
                </tr>
                <tr>
                    <th>Role</th>
                    <td><?php echo $fetch_info['role']; ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo $fetch_info['email']; ?></td>
                </tr>
               
                <tr>
                    <th>Credential</th>
                    <td>
                        <div class="credential-container">
                            <?php if ($credPhotoSrc): ?>
                                <img id="credential-preview" src="<?php echo $credPhotoSrc; ?>" alt="Credential">
                            <?php else: ?>
                                <img id="credential-preview" src="" alt="Credential">
                            <?php endif; ?>
                        </div>
                        <input type="file" name="credential_photo" accept="image/*" onchange="previewImage(event, 'credential-preview')">
                    </td>
                </tr>
            </table>
            <button type="submit">Update Profile</button>
        </form>
    </div>
</div>

<script>
    function previewImage(event, previewId) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById(previewId);
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function submitProfileForm() {
        var form = document.getElementById('profileForm');
        var formData = new FormData(form);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_profile.php', true);
        xhr.onload = function() {
            var response = JSON.parse(this.responseText);
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Profile Updated',
                    text: response.message
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message
                });
            }
        };
        xhr.send(formData);
    }
</script>


  <script>
    // JavaScript functions to show and close the profile modal
    function showProfileModal() {
      document.getElementById('profileModal').style.display = 'block';
    }

    function closeProfileModal() {
      document.getElementById('profileModal').style.display = 'none';
    }

    // Function to toggle the dropdown menu
    function toggleDropdown() {
      document.getElementById('dropdown-menu').classList.toggle('show');
    }

    // Close the dropdown menu if the user clicks outside of it
    window.onclick = function(event) {
      if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        for (var i = 0; i < dropdowns.length; i++) {
          var openDropdown = dropdowns[i];
          if (openDropdown.classList.contains('show')) {
            openDropdown.classList.remove('show');
          }
        }
      }
    }
  </script>
</body>
</html>

<style>
  .overlay2 {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
}

.modal2 {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    z-index: 1001;
    text-align: center;
}

  .profile-avatar {
    max-width: 200px;
    border-radius: 50%;
    border: 2px solid #fff; /* Optional: Add a border */
}

.dropdowns-containersearch {
  display: flex;
  gap: 10px; /* Add gap between dropdowns */
}

.dropdownsearch {
  flex: 1;
}

/* Adjust dropdown styles */
.dropdownsearch select {
  width: 100%;
  padding: 10px;
  border: 2px solid #ccc;
  border-radius: 5px;
  font-size: 16px;
}
.search-bar {
  display: flex;
  align-items: center;
  margin-bottom: 20px; /* Add margin to separate from dropdowns */
}

.search-input {
  padding: 10px;
  border: 2px solid #ccc;
  border-radius: 5px;
  margin-right: 10px;
  font-size: 16px;
  flex: 1;
}

.search-btn {
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  background-color: #007bff;
  color: #fff;
  font-size: 16px;
  cursor: pointer;
}

.search-btn:hover {
  background-color: #0056b3;
}
</style>