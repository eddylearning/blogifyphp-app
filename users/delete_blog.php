<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once('../db.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Blog ID is required.";
} else {
    $blog_id = $_GET['id'];
    
    try {
        $checkSql = "SELECT user_id FROM blogs WHERE id = :blog_id";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bindParam(":blog_id", $blog_id);
        $checkStmt->execute();
        
        $blog = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$blog) {
            $_SESSION['error'] = "Blog not found.";
        } elseif ($blog['user_id'] != $_SESSION['user_id'] && $_SESSION['role'] != 'admin') {
            $_SESSION['error'] = "You don't have permission to delete this blog.";
        } else {
            $deleteSql = "DELETE FROM blogs WHERE id = :blog_id";
            $deleteStmt = $conn->prepare($deleteSql);
            $deleteStmt->bindParam(":blog_id", $blog_id);
            
            if ($deleteStmt->execute()) {
                $_SESSION['success'] = "Blog deleted successfully!";
            } else {
                $_SESSION['error'] = "Failed to delete the blog.";
            }
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "An error occurred: " . $e->getMessage();
    }
}

header("Location: dashboard.php");
exit();
?>
