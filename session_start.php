<?php
// session_start.php

// Check if session is not already started
if (session_status() == PHP_SESSION_NONE) {
    // Start the session
    session_start();
}
?>
