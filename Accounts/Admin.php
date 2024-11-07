<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" type="text/css" href="../css/admin.css">
</head>
<body>

<div class="header">
  <h1>Travel Planning app</h1>
  <div>
    <div class="dropdown">
      <button onclick="toggleDropdown()" class="dropbtn">
        <img src="../img/profile.jpg" alt="Profile Icon">
      </button>
      <div id="dropdown-menu" class="dropdown-content">
        <a href="#">Profile</a>
        <a href="../logout-user.php">Logout</a>
      </div>
    </div>
  </div>
</div>

<div class="sidebar">
  <a href="#" id="Accounts">Accounts</a>
  <a href="#" id="agentButton">Agent</a>
  <a href="#" id="travelerButton">Traveler</a>
  
</div>

<div class="content" id="userAccounts" style="display: none;">
  <?php include("../Accounts/load-accounts.php"); ?>
</div>

<div class="content" id="agentAccounts" style="display: none;">
  <?php include("../Accounts/load-accounts-agent.php"); ?>
</div>

<div class="content" id="travelerAccounts" style="display: none;">
  <?php include("../Accounts/load-accounts-traveler.php"); ?>
</div>

<script>
  document.getElementById("Accounts").addEventListener("click", function() {
    toggleContent("userAccounts");
  });

  document.getElementById("travelerButton").addEventListener("click", function() {
    toggleContent("travelerAccounts");
  });

  document.getElementById("agentButton").addEventListener("click", function() {
    toggleContent("agentAccounts");
  });

  function toggleContent(contentId) {
    var contentDivs = document.getElementsByClassName("content");
    for (var i = 0; i < contentDivs.length; i++) {
      contentDivs[i].style.display = "none";
    }
    document.getElementById(contentId).style.display = "block";
  }
</script>

</body>
</html>
