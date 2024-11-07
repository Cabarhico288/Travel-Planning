<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Profile</title>
    <link rel="stylesheet" href="css/profiletraveler.css">
</head>
<body>
    <h2>Travel Profile</h2>
    <form action="process_profile.php" method="post" enctype="multipart/form-data">
    <div class="avatar-wrapper" onclick="document.getElementById('avatar').click();">
            <input type="file" id="avatar" name="avatar" accept="image/*" style="display: none;" onchange="displayAvatar(this);">
            <img id="avatar-img" src="placeholder.jpg" alt="Avatar" onclick="document.getElementById('avatar').click();" title="Choose avatar" />
            <div class="choose-avatar">Choose Avatar</div>
        </div>
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br>
        
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>
        
        <label for="travel_dates">Preferred Travel Dates:</label><br>
        <input type="text" id="travel_dates" name="travel_dates"><br>
        
        <label for="destinations">Interested Destinations:</label><br>
        <input type="text" id="destinations" name="destinations"><br>
        
        <label for="activities">Preferred Activities:</label><br>
        <input type="text" id="activities" name="activities"><br>
        
        <label for="budget">Budget:</label><br>
        <input type="text" id="budget" name="budget"><br>
        
        <label for="interests">Interests and Hobbies:</label><br>
        <textarea id="interests" name="interests" rows="4" cols="50"></textarea><br>
        
        <label for="travel_companions">Travel Companions:</label><br>
        <input type="text" id="travel_companions" name="travel_companions"><br>
        
        <input type="submit" value="Submit">
    </form>
    <script>
        function displayAvatar(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    document.getElementById('avatar-img').src = e.target.result;
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
