<?php require_once "controllerUserData.php"; ?>
<?php require_once "password_checker.php"; ?>
</head><link rel="stylesheet" href="css/signup.css">
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form">
                <form id="signup-form" action="signup-user.php" method="POST" autocomplete="">
                    <h2 class="text-center">Signup Form</h2>
                    <p class="text-center">It's quick and easy.</p>
                    <?php
                    if(count($errors) == 1){
                        ?>
                        <div class="alert alert-danger text-center">
                            <?php
                            foreach($errors as $showerror){
                                echo $showerror;
                            }
                            ?>
                        </div>
                        <?php
                    }elseif(count($errors) > 1){
                        ?>
                        <div class="alert alert-danger">
                            <?php
                            foreach($errors as $showerror){
                                ?>
                                <li><?php echo $showerror; ?></li>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="form-group">
                        <input class="form-control" type="text" name="name" id="name" placeholder="Full Name" autocomplete="off" required value="<?php echo $name ?>">
                        <span id="name-error" class="error"></span>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="email" name="email" id="email" placeholder="Email Address" autocomplete="off" required value="<?php echo $email ?>">
                        <span id="email-error" class="error"></span>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="text" name="contactnum" id="contactnum" placeholder="Contact Number (Philippines)" autocomplete="off" required value="<?php echo $contact ?>">
                        <span id="contactnum-error" class="error"></span>
                    </div>
                    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const contactNumberInput = document.getElementById("contactnum");

        contactNumberInput.addEventListener("input", function() {
            let inputValue = contactNumberInput.value.trim();
            const countryCode = "+63";

          
            // Limit the input to 13 characters
            if (inputValue.length > 13) {
                inputValue = inputValue.slice(0, 13);
            }

            // Update the input value with country code if missing
            if (!inputValue.startsWith(countryCode)) {
                contactNumberInput.value = countryCode + inputValue;
            } else {
                contactNumberInput.value = inputValue;
            }
        });

        contactNumberInput.addEventListener("keypress", function(event) {
            // Prevent input of non-numeric characters
            if (isNaN(Number(event.key))) {
                event.preventDefault();
            }
        });
    });
</script>


                    <div class="form-group">
                        <select class="form-control" name="role" required >
                            <option value="Traveler" <?php if($role == 'Traveler') echo 'selected'; ?>>Traveler</option>
                            <option value="Agent" <?php if($role == 'Agent') echo 'selected'; ?>>Travel Agent</option>
                        </select>
                    </div>
                    <hr>

                    <h6>Please include Uppercase, Lowercase, Numbers and Speacial characters</h6>
                    <div class="form-group">
                    <div class="password-icons">
                    <span class="password-icon lowercase-icon">&#x1F521;</span>
                    <span class="password-icon uppercase-icon">&#x1F520;</span>
                    <span class="password-icon number-icon">&#x1F522;</span>
                    <span class="password-icon special-char-icon">&#x1F523;</span>

    </div>
    <div class="form-group password-container">
    <input class="form-control password-input" type="password" name="password" id="password" placeholder="Password" required>
  
</div>

<div class="form-group password-container">
    <input class="form-control" type="password" name="cpassword" id="confirmPassword" placeholder="Confirm password" required>
    <p id="password-match-message" class="error-message"></p>
</div>
<input type="checkbox" onclick="togglePassword()"> Show Password
<hr>
<script>
    // Function to validate password match
    function validatePasswordMatch() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        const message = document.getElementById('password-match-message');

        if (password !== confirmPassword) {
            message.textContent = "Passwords do not match!";
            message.classList.add('error-message');
        } else {
            message.textContent = "Passwords match!";
            message.classList.remove('error-message');
        }
    }

    // Listen for keyup events in the confirm password field
    document.getElementById('confirmPassword').addEventListener('keyup', validatePasswordMatch);
</script>
<script>
    function validatePassword() {
        var password = document.getElementById("password").value;

        // Regular expressions to check for lowercase, uppercase, number, and special character
        var lowercaseRegex = /[a-z]/;
        var uppercaseRegex = /[A-Z]/;
        var numberRegex = /[0-9]/;
        var specialCharRegex = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;

        // Check if all criteria are met
        var isLowercase = lowercaseRegex.test(password);
        var isUppercase = uppercaseRegex.test(password);
        var isNumber = numberRegex.test(password);
        var isSpecialChar = specialCharRegex.test(password);

        // Update password strength indicators
        document.querySelector(".lowercase-icon").innerHTML = isLowercase ? "&#x2713;" : "&#x1F521;";
        document.querySelector(".uppercase-icon").innerHTML = isUppercase ? "&#x2713;" : "&#x1F520;";
        document.querySelector(".number-icon").innerHTML = isNumber ? "&#x2713;" : "&#x1F522;";
        document.querySelector(".special-char-icon").innerHTML = isSpecialChar ? "&#x2713;" : "&#x1F523;";

        // Check if all criteria are met
        var isValidPassword = isLowercase && isUppercase && isNumber && isSpecialChar;

        // Enable/disable the "Signup" button based on password validity
        document.getElementById("signup").disabled = !isValidPassword;
    }

    // Listen for input events in the password field
    document.getElementById("password").addEventListener("input", validatePassword);
</script>


                    <div class="form-group">
                        <input class="form-control button" type="submit" name="signup" id="signup" value="Signup" >
                    </div>
                    <div class="link login-link text-center">Already member? <a href="index.php">Login here</a></div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#name, #email, #contactnum').on('keyup', function(){
                var name = $('#name').val();
                var email = $('#email').val();
                var contact = $('#contactnum').val();
                
                $.ajax({
                    type: 'POST',
                    url: 'check_existing.php',
                    data: {name: name, email: email, contactnum: contact},
                    dataType: 'json',
                    success: function(response){
                        if(response.name || response.email || response.contactnum) {
                            $('#signup').prop('disabled', true);
                            if(response.name) {
                                $('#name-error').text("Already Exists");   
                            } else {
                                $('#name-error').text("");
                            }
                            if(response.email) {
                                $('#email-error').text("Already Exists");
                            } else {
                                $('#email-error').text("");
                            }
                            if(response.contactnum) {
                                $('#contactnum-error').text("Already Exists");
                            } else {
                                $('#contactnum-error').text("");
                            }
                        } else {
                            $('#signup').prop('disabled', false);
                            $('#name-error, #email-error, #contactnum-error').text("");
                        }
                    }
                });
            });
        });
        
    </script>
    <script>
   document.getElementById("password").addEventListener("input", function() {
    var password = this.value;

    // Check if the password field is empty
    if (password === "") {
        // Reset all icons
        document.querySelector(".lowercase-icon").innerHTML = "&#x1F521;"; // Lowercase icon
        document.querySelector(".uppercase-icon").innerHTML = "&#x1F522;"; // Uppercase icon
        document.querySelector(".number-icon").innerHTML = "&#x1F523;"; // Number icon
        document.querySelector(".special-char-icon").innerHTML = "&#x1F523;"; // Special character icon
        return; // Exit the function early
    }

    // Reset icons
    document.querySelector(".lowercase-icon").innerHTML = "&#x1F521;"; // Lowercase icon
    document.querySelector(".uppercase-icon").innerHTML = "&#x1F522;"; // Uppercase icon
    document.querySelector(".number-icon").innerHTML = "&#x1F523;"; // Number icon
    document.querySelector(".special-char-icon").innerHTML = "&#x1F523;"; // Special character icon

    // Check each character in the password
    for (var i = 0; i < password.length; i++) {
        var charCode = password.charCodeAt(i);
        if (charCode >= 97 && charCode <= 122) { // Lowercase letter (a-z)
            document.querySelector(".lowercase-icon").innerHTML = "&#x2713;";
        } else if (charCode >= 65 && charCode <= 90) { // Uppercase letter (A-Z)
            document.querySelector(".uppercase-icon").innerHTML = "&#x2713;";
        } else if (charCode >= 48 && charCode <= 57) { // Number (0-9)
            document.querySelector(".number-icon").innerHTML = "&#x2713;";
        } else { // Special character
            document.querySelector(".special-char-icon").innerHTML = "&#x2713;";
        }
    }
});
    </script>
</body>

<script>
    // Function to check name availability
function checkNameAvailability(name) {
    $.ajax({
        type: 'POST',
        url: 'check_availability.php',
        data: { name: name },
        success: function(response) {
            if (response === 'exists') {
                $('#name-error').text('This name is already taken');
            } else {
                $('#name-error').text('');
            }
        }
    });
}

// Function to check email availability
function checkEmailAvailability(email) {
    $.ajax({
        type: 'POST',
        url: 'check_availability.php',
        data: { email: email },
        success: function(response) {
            if (response === 'exists') {
                $('#email-error').text('This email is already registered');
            } else {
                $('#email-error').text('');
            }
        }
    });
}

// Function to check contact number availability
function checkContactAvailability(contact) {
    $.ajax({
        type: 'POST',
        url: 'check_availability.php',
        data: { contact: contact },
        success: function(response) {
            if (response === 'exists') {
                $('#contactnum-error').text('This contact number is already in use');
            } else {
                $('#contactnum-error').text('');
            }
        }
    });
}

</script>
<script>
    function togglePassword() {
        var passwordField1 = document.getElementById("password");
        var passwordField2 = document.getElementById("confirmPassword");
        var checkBox = document.querySelector("input[type='checkbox']");

        if (checkBox.checked) {
            passwordField1.type = "text";
            passwordField2.type = "text";
        } else {
            passwordField1.type = "password";
            passwordField2.type = "password";
        }
    }
</script>