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

    // Function to toggle profile form container
    function toggleProfile() {
        var profileContainer = document.getElementById("profileFormContainer");
        if (profileContainer.style.display === "block") {
            profileContainer.style.display = "none";
        } else {
            profileContainer.style.display = "block";
        }
    }