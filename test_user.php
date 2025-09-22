<?php
require_once('db.php');

echo "<h1>Testing User Data</h1>";

// Test database connection
try {
    $stmt = $conn->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>All Users in Database:</h2>";
    echo "<pre>";
    print_r($users);
    echo "</pre>";
    
    // Test specific user
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(":username", "user blogger");
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<h2>Specific User Data:</h2>";
    echo "<pre>";
    print_r($user);
    echo "</pre>";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
