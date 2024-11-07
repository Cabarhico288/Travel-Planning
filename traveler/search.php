<?php
require_once "connection.php";

if (isset($_GET['query'])) {
    $searchQuery = $_GET['query'];
    $query = "
      SELECT destination.*, usertable.avatar 
      FROM destination 
      JOIN usertable ON destination.agent_name = usertable.name
      WHERE destination.placename LIKE '%$searchQuery%' 
      OR destination.location LIKE '%$searchQuery%'";
    $result = mysqli_query($con, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $agentAvatarSrc = '';
        if (!empty($row['avatar'])) {
            $agentAvatarData = base64_encode($row['avatar']);
            $agentAvatarSrc = 'data:image/jpeg;base64,' . $agentAvatarData;
        }
        ?>
        <div class="place-container">
            <a href="booking_form.php?id=<?php echo $row['id']; ?>" class="booking-link">
                <img src="uploads/<?php echo $row['image']; ?>" alt="Place Image">
                <div class="place-info">
                <?php if ($agentAvatarSrc): ?>
                      <img src="<?php echo $agentAvatarSrc; ?>" alt="Agent Avatar" class="agent-avatar">
                    <?php endif; ?>
                    <p><strong>Agent Name:</strong> <?php echo $row['agent_name']; ?></p>
                   
                    <p><strong>Place Name:</strong> <?php echo $row['placename']; ?></p>
                    <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
                    <p><strong>Description:</strong> <?php echo $row['description']; ?></p>
                    <br>
                </div>
            </a>
        </div>
        <?php
    }
}
?>
