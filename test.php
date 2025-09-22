<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Testing Components</h1>";

// Test 1: Basic PHP
echo "<h2>1. Basic PHP: Working</h2>";

// Test 2: Database Connection
echo "<h2>2. Database Connection:</h2>";
try {
    require_once('db.php');
    echo "✓ Database connection successful<br>";
    
    // Test a simple query
    $stmt = $conn->query("SELECT 1");
    echo "✓ Test query successful<br>";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
}

// Test 3: Session
echo "<h2>3. Session:</h2>";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['test'] = 'working';
echo "✓ Session started and working<br>";

// Test 4: Include Navbar
echo "<h2>4. Navbar Include:</h2>";
try {
    include('layout/navbar.php');
    echo "✓ Navbar included successfully<br>";
} catch (Exception $e) {
    echo "✗ Navbar error: " . $e->getMessage() . "<br>";
}

echo "<h2>Test Complete</h2>";
?>
