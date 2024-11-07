<?php require_once "controllerUserData.php"; ?>
<?php 
$email = $_SESSION['email'];
if($email == false){
  header('Location: login-user.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create a New Password</title>
   
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form">
                <form action="new-password.php" method="POST" autocomplete="off">
                    <h2 class="text-center">New Password</h2>
                    <?php 
                    if(isset($_SESSION['info'])){
                        ?>
                        <div class="alert alert-success text-center">
                            <?php echo $_SESSION['info']; ?>
                        </div>
                        <?php
                    }
                    ?>
                    <?php
                    if(count($errors) > 0){
                        ?>
                        <div class="alert alert-danger text-center">
                            <?php
                            foreach($errors as $showerror){
                                echo $showerror;
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                    <h6>Please include Uppercase, Lowercase, Numbers and Speacial characters</h6>
                    <div class="form-group password-icons">
    <span class="password-icon lowercase-icon">&#x1F521;</span>
    <span class="password-icon uppercase-icon">&#x1F520;</span>
    <span class="password-icon number-icon">&#x1F522;</span>
    <span class="password-icon special-char-icon">&#x1F523;</span>
</div>
<div class="form-group password-container">
    <input class="form-control" type="password" name="password" id="password" placeholder="Create new password" required>
</div>
                    <div class="form-group">
                        <input class="form-control" type="password" name="cpassword" placeholder="Confirm your password" required>
                        <input type="checkbox" onclick="togglePassword()"> Show Password
                    </div>
                    <div class="form-group">
                        <input class="form-control button" type="submit" name="change-password" value="Change">
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fafafa;
        }

        .container {
            margin-top: 50px;
        }

        .form {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .form h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            outline: none;
        }

        .button {
            background-color: #3897f0;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #357ae8;
        }

        .alert {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        <style>
.password-icons {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.password-icon {
    font-size: 20px;
}

.error-message {
    color: red;
    font-size: 14px;
}
</style>

    </style>
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
<script>
function togglePassword() {
    var passwordField = document.getElementById("password");
    var checkBox = document.querySelector("input[type='checkbox']");

    if (checkBox.checked) {
        passwordField.type = "text";
    } else {
        passwordField.type = "password";
    }
}
</script>
