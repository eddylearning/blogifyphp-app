<!-- <?php
$password = "password123"; // ← change this if needed
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Hashed password: " . $hash;
?> -->

<?php
echo password_hash('password123', PASSWORD_DEFAULT);
