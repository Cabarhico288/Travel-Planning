function fetchPackageData(button) {
    var packageId = button.getAttribute('data-package-id');
  
    // Use AJAX to fetch package data
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "Agent.php?fetchPackage=" + packageId, true);
    xhr.onload = function() {
      if (xhr.status === 200) {
        var responseData = JSON.parse(xhr.responseText);
        if (responseData.error) {
          // Handle error (optional)
          console.error("Error fetching package data:", responseData.error);
        } else {
          // Populate edit modal with fetched data
          populateEditModal(responseData);
          openEditModal(packageId); // Now open the modal
        }
      } else {
        // Handle other errors (optional)
        console.error("Failed to fetch package data. Status:", xhr.status);
      }
    };
    xhr.send();
  }
  