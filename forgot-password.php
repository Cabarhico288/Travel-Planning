<?php require_once "controllerUserData.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
   
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form">
                <form action="forgot-password.php" method="POST" autocomplete="">
                    <h2 class="text-center">Forgot Password ?</h2>
                    <p class="text-center">Enter your email address</p>
                    <?php
                        if(count($errors) > 0){
                            ?>
                            <div class="alert alert-danger text-center">
                                <?php 
                                    foreach($errors as $error){
                                        echo $error;
                                    }
                                ?>
                            </div>
                            <?php
                        }
                    ?>
                    <div class="form-group">
                        <input class="form-control" type="email" name="email" placeholder="Enter email address" required value="<?php echo $email ?>">
                    </div>
                    <div class="form-group">
                        <input class="form-control button" type="submit" name="check-email" value="Continue">
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>

<style>
    /* CSS for Instagram Style with wider container and textboxes */

body {
    background: #fafafa; /* Light gray background */
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; /* System font */
    margin: 0;
    padding: 0;
}

::selection {
    color: #fff;
    background: #4b4b4b; /* Dark gray selection color */
}

.container {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 500px; /* Increased container width */
    max-width: 90%; /* Limit the maximum width to ensure responsiveness */
}

.container .form {
    background: #fff;
    padding: 30px 35px; /* Adjust padding */
    border-radius: 5px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1); /* Soft shadow */
}

.container .form form .form-control {
    height: 40px;
    font-size: 15px;
    width: calc(100% - 16px); /* Adjusted width to fit wider container */
    border: 1px solid #dbdbdb; /* Light gray border */
    border-radius: 5px; /* Add border radius */
    padding: 8px; /* Add padding */
    margin-bottom: 10px; /* Add margin bottom */
    background-color: #fafafa; /* Light gray background */
}

.container .form form .button-container {
    display: flex;
    justify-content: center; /* Center align the button */
}

.container .form form .button {
    background: #3897f0; /* Instagram blue */
    color: #fff;
    font-size: 17px;
    font-weight: 500;
    transition: background-color 0.3s;
    border: none;
    padding: 12px 20px; /* Adjust padding */
    cursor: pointer;
    border-radius: 5px;
}

.container .form form .button:hover {
    background: #3182ca; /* Darker shade of Instagram blue */
}

.container .form form p,
.container .row .alert {
    font-size: 14px;
}

/* Additional CSS for responsiveness */
@media (max-width: 600px) {
    .container .form {
        padding: 20px; /* Reduce padding for smaller screens */
    }

    .container .form form .button {
        width: 100%; /* Make the button full width for smaller screens */
    }
}

    </style>