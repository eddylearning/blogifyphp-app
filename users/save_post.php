<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: ../auth/login.php");
    exit();
}

$allowed_roles = ['admin', 'bloger'];
if (!in_array($_SESSION['role'], $allowed_roles)) {
    header("Location: dashboard.php");
    exit();
}

require_once('../db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);
    $category = trim($_POST["category"]);
    $blog_id = isset($_POST["blog_id"]) ? trim($_POST["blog_id"]) : '';

    if (empty($title) || empty($content) || empty($category)) {
        $_SESSION['error'] = "All fields are required.";
    } else {
        try {
            $image_url = '';
            if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
                $target_dir = "../uploads/";
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                
                $target_file = $target_dir . basename($_FILES["image"]["name"]);
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                
                $check = getimagesize($_FILES["image"]["tmp_name"]);
                if ($check !== false) {
                    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                        $_SESSION['error'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    } else {
                        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                            $image_url = "uploads/" . basename($_FILES["image"]["name"]);
                        } else {
                            $_SESSION['error'] = "Sorry, there was an error uploading your file.";
                        }
                    }
                } else {
                    $_SESSION['error'] = "File is not an image.";
                }
            }

            if (empty($_SESSION['error'])) {
                if (empty($blog_id)) {
                    $sql = "INSERT INTO blogs (user_id, title, content, category, image_url) 
                            VALUES (:user_id, :title, :content, :category, :image_url)";
                    $stmt = $conn->prepare($sql);
                    
                    $stmt->bindParam(":user_id", $_SESSION["user_id"]);
                    $stmt->bindParam(":title", $title);
                    $stmt->bindParam(":content", $content);
                    $stmt->bindParam(":category", $category);
                    $stmt->bindParam(":image_url", $image_url);
                    
                    if ($stmt->execute()) {
                        $_SESSION['success'] = "Blog post created successfully!";
                    }
                }
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = "An error occurred: " . $e->getMessage();
        }
    }
}

header("Location: dashboard.php");
exit();
?>
