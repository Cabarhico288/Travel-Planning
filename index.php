<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Planner</title>
    <link href="https://fonts.googleapis.com/css2?family=Industrial+Script&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
   
</head>
<style>
    .header {
        text-align: center;
        padding: 10px;
        font-family: 'Industrial Script', cursive;
    }

    h1 {
        font-size: 3rem;
        margin: 0;
        margin-bottom: 10px;
        text-transform: uppercase;
  
    }

    p {
        font-size: 1.5rem;
        margin: 0;
        margin-bottom: 20px;
        color: #FFF;
    }
</style>

<div class="header">
<h1 >Welcome to Travel Planner</h1>
<p >Plan Your Trip Now!!</p>
</div>
<body>
   
  


    <div class="container">
        <div id="loginForm" class="hidden">
            <div class="col-md-4 offset-md-4 form login-form">
                <form action="login-user.php" method="POST" autocomplete="">
                    <h2 class="text-center">Login Form</h2>
                    <p class="text-center">Login with your email and password.</p>
                    <div class="form-group">
                        <input class="form-control" type="email" name="email" placeholder="Email Address" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <input id="password" class="form-control" type="password" name="password" placeholder="Password" required>
                        <input type="checkbox" onclick="togglePassword()"> Show Password
                    </div>
                    <br>
                    <div class="link forget-pass text-left"><a href="forgot-password.php">Forgot password?</a></div>
                    <div class="form-group">
                        <input class="form-control button" type="submit" name="login" value="Login">
                    </div>
                    <div class="link login-link text-center">Not yet a member? <a href="signup-user.php">Signup now</a></div>
                </form>
            </div>
        </div>
    </div>
 

 
</body>
</html>
