<!-- <?php
require_once('../config.php');
require_once('../db.php');
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: ../auth/login.php");
    exit();
}

$error = '';
$blog = null;

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $error = "Blog ID is required.";
} else {
    $blog_id = $_GET['id'];
    
    try {
        $sql = "SELECT b.*, u.username 
                FROM blogs b 
                JOIN users u ON b.user_id = u.id 
                WHERE b.id = :blog_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":blog_id", $blog_id);
        $stmt->execute();
        
        $blog = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$blog) {
            $error = "Blog not found.";
        }
    } catch (PDOException $e) {
        $error = "An error occurred: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($blog) ? htmlspecialchars($blog['title']) : 'Blog Not Found'; ?> - Blogify PHP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            flex: 1;
            margin: 40px 20px;
        }
        
        .blog-title {
            font-size: 28px;
            margin-bottom: 10px;
            color: #333;
        }
        
        .blog-meta {
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .blog-content {
            line-height: 1.6;
            color: #333;
        }
        
        .error {
            color: red;
            background: #ffebee;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .btn {
            padding: 10px 15px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
        }
        
        .btn-danger {
            background: #dc3545;
        }
        
        .blog-image {
            max-width: 100%;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .blog-actions {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <?php include('../layout/navbar.php'); ?>
    
    <div class="container">
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <a href="dashboard.php" class="btn">Back to Dashboard</a>
        <?php elseif ($blog): ?>
            <h1 class="blog-title"><?php echo htmlspecialchars($blog['title']); ?></h1>
            <div class="blog-meta">
                By <?php echo htmlspecialchars($blog['username']); ?> | 
                Category: <?php echo htmlspecialchars($blog['category']); ?> | 
                Created: <?php echo date('M j, Y, g:i a', strtotime($blog['created_at'])); ?>
            </div>
            
            <?php if (!empty($blog['image_url'])): ?>
                <img src="../<?php echo htmlspecialchars($blog['image_url']); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>" class="blog-image">
            <?php endif; ?>
            
            <div class="blog-content">
                <?php echo nl2br(htmlspecialchars($blog['content'])); ?>
            </div>
            
            <div class="blog-actions">
                <a href="dashboard.php" class="btn">Back to Dashboard</a>
                
                <?php if ($blog['user_id'] == $_SESSION['user_id'] || $_SESSION['role'] == 'admin'): ?>
                    <a href="#" class="btn" onclick="editBlog(<?php echo $blog['id']; ?>)">Edit</a>
                    <a href="#" class="btn btn-danger" onclick="deleteBlog(<?php echo $blog['id']; ?>)">Delete</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <?php include('../layout/footer.php'); ?>

    <script>
        function editBlog(id) {
            window.location.href = 'dashboard.php?edit=' + id;
        }
        
        function deleteBlog(id) {
            if (confirm('Are you sure you want to delete this blog?')) {
                window.location.href = 'delete_blog.php?id=' + id;
            }
        }
    </script>
</body>
</html> -->


<?php
require_once('../config.php');
require_once('../db.php');
session_start();

// Check if user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: ../auth/login.php");
    exit();
}

// Get blog ID from URL
$blog_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Initialize variables
$error = '';
$blog = null;
$prev_blog = null;
$next_blog = null;

if ($blog_id > 0) {
    try {
        // Get the current blog post with author information
        $sql = "SELECT b.*, u.username 
                FROM blogs b 
                JOIN users u ON b.user_id = u.id 
                WHERE b.id = :blog_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":blog_id", $blog_id);
        $stmt->execute();
        $blog = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$blog) {
            $error = "Blog post not found.";
        } else {
            // Get previous blog post
            $sql = "SELECT id, title FROM blogs 
                    WHERE id < :blog_id 
                    ORDER BY id DESC 
                    LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":blog_id", $blog_id);
            $stmt->execute();
            $prev_blog = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Get next blog post
            $sql = "SELECT id, title FROM blogs 
                    WHERE id > :blog_id 
                    ORDER BY id ASC 
                    LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":blog_id", $blog_id);
            $stmt->execute();
            $next_blog = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        $error = "An error occurred: " . $e->getMessage();
    }
} else {
    $error = "Invalid blog ID.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($blog) ? htmlspecialchars($blog['title']) : 'Blog Not Found'; ?> - Blogify PHP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            flex: 1;
            margin: 40px 20px;
        }
        
        .blog-header {
            margin-bottom: 30px;
        }
        
        .blog-title {
            font-size: 32px;
            margin-bottom: 10px;
            color: #333;
        }
        
        .blog-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .meta-icon {
            color: #7c9eff;
        }
        
        .category-tag {
            display: inline-block;
            background: #7c9eff;
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .blog-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .blog-content {
            line-height: 1.8;
            color: #333;
            font-size: 16px;
            margin-bottom: 40px;
        }
        
        .blog-navigation {
            display: flex;
            justify-content: space-between;
            padding-top: 30px;
            border-top: 1px solid #eee;
            margin-top: 40px;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #7c9eff;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .nav-link:hover {
            color: #5f86ff;
        }
        
        .nav-link.disabled {
            color: #ccc;
            cursor: not-allowed;
        }
        
        .nav-link.disabled:hover {
            color: #ccc;
        }
        
        .back-to-dashboard {
            display: inline-block;
            margin-bottom: 30px;
            color: #7c9eff;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .back-to-dashboard:hover {
            color: #5f86ff;
        }
        
        .error {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        .error h2 {
            color: #e74c3c;
        }
        
        .blog-actions {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #eee;
            display: flex;
            gap: 15px;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-edit {
            background: #7c9eff;
            color: white;
        }
        
        .btn-edit:hover {
            background: #5f86ff;
        }
        
        .btn-delete {
            background: #e74c3c;
            color: white;
        }
        
        .btn-delete:hover {
            background: #c0392b;
        }
        
        @media (max-width: 768px) {
            .container {
                margin: 20px 15px;
                padding: 20px;
            }
            
            .blog-title {
                font-size: 24px;
            }
            
            .blog-meta {
                flex-direction: column;
                gap: 10px;
            }
            
            .blog-navigation {
                flex-direction: column;
                gap: 20px;
            }
        }
    </style>
</head>
<body>
    <?php include('../layout/navbar.php'); ?>
    
    <div class="container">
        <?php if (!empty($error)): ?>
            <div class="error">
                <h2>Error</h2>
                <p><?php echo htmlspecialchars($error); ?></p>
                <a href="dashboard.php" class="back-to-dashboard">← Back to Dashboard</a>
            </div>
        <?php elseif ($blog): ?>
            <a href="dashboard.php" class="back-to-dashboard">← Back to Dashboard</a>
            
            <div class="blog-header">
                <h1 class="blog-title"><?php echo htmlspecialchars($blog['title']); ?></h1>
                
                <div class="blog-meta">
                    <div class="meta-item">
                        <span class="meta-icon">👤</span>
                        <span>By <?php echo htmlspecialchars($blog['username']); ?></span>
                    </div>
                    
                    <div class="meta-item">
                        <span class="meta-icon">📅</span>
                        <span><?php echo date('F j, Y', strtotime($blog['created_at'])); ?></span>
                    </div>
                    
                    <div class="meta-item">
                        <span class="meta-icon">🏷️</span>
                        <span class="category-tag"><?php echo htmlspecialchars($blog['category']); ?></span>
                    </div>
                </div>
            </div>
            
            <?php if (!empty($blog['image_url'])): ?>
                <img src="../<?php echo htmlspecialchars($blog['image_url']); ?>" 
                     alt="<?php echo htmlspecialchars($blog['title']); ?>" 
                     class="blog-image">
            <?php endif; ?>
            
            <div class="blog-content">
                <?php echo nl2br(htmlspecialchars($blog['content'])); ?>
            </div>
            
            <div class="blog-navigation">
                <?php if ($prev_blog): ?>
                    <a href="view_blog.php?id=<?php echo $prev_blog['id']; ?>" class="nav-link">
                        <span>←</span>
                        <span><?php echo htmlspecialchars($prev_blog['title']); ?></span>
                    </a>
                <?php else: ?>
                    <a href="#" class="nav-link disabled">
                        <span>←</span>
                        <span>No previous post</span>
                    </a>
                <?php endif; ?>
                
                <?php if ($next_blog): ?>
                    <a href="view_blog.php?id=<?php echo $next_blog['id']; ?>" class="nav-link">
                        <span><?php echo htmlspecialchars($next_blog['title']); ?></span>
                        <span>→</span>
                    </a>
                <?php else: ?>
                    <a href="#" class="nav-link disabled">
                        <span>No next post</span>
                        <span>→</span>
                    </a>
                <?php endif; ?>
            </div>
            
            <?php if ($blog['user_id'] == $_SESSION['user_id'] || $_SESSION['role'] == 'admin'): ?>
                <div class="blog-actions">
                    <a href="#" class="btn btn-edit" onclick="editBlog(<?php echo $blog['id']; ?>)">Edit Post</a>
                    <a href="#" class="btn btn-delete" onclick="deleteBlog(<?php echo $blog['id']; ?>)">Delete Post</a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    
    <?php include('../layout/footer.php'); ?>

    <script>
        function editBlog(id) {
            window.location.href = 'dashboard.php?edit=' + id;
        }
        
        function deleteBlog(id) {
            if (confirm('Are you sure you want to delete this blog post?')) {
                window.location.href = 'delete_blog.php?id=' + id;
            }
        }
    </script>
</body>
</html>