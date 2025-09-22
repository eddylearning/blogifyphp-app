<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once('../db.php');

// Ensure role is set in session
if (!isset($_SESSION["role"])) {
    try {
        $stmt = $conn->prepare("SELECT role FROM users WHERE id = :user_id");
        $stmt->bindParam(":user_id", $_SESSION["user_id"]);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $_SESSION["role"] = $user["role"];
        }
    } catch (PDOException $e) {
        // Log error if needed
    }
}

$error = '';
$success = '';

if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}

if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}

try {
    $stmt = $conn->prepare("SELECT * FROM blogs WHERE user_id = :user_id ORDER BY created_at DESC");
    $stmt->bindParam(":user_id", $_SESSION["user_id"]);
    $stmt->execute();
    $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching blogs: " . $e->getMessage();
    $blogs = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Blogify PHP</title>
    <style>
        /* Dashboard Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .main-content {
            flex: 1;
            padding: 40px 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .header h1 {
            margin: 0;
            color: #333;
        }
        
        .header-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-info {
            text-align: right;
        }
        
        .user-name {
            font-weight: bold;
            color: #333;
        }
        
        .user-role {
            font-size: 14px;
            color: #666;
        }
        
        .blog-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
        }
        
        .blog-card {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .blog-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .blog-image {
            height: 180px;
            background: linear-gradient(135deg, #7c9eff, #5f86ff);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        .blog-content {
            padding: 20px;
        }
        
        .blog-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        
        .blog-meta {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .blog-excerpt {
            color: #555;
            line-height: 1.5;
            margin-bottom: 15px;
        }
        
        .blog-actions {
            display: flex;
            gap: 10px;
        }
        
        .error {
            background: #ffebee;
            color: #c62828;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #c62828;
        }
        
        .success {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #2e7d32;
        }
        
        .btn {
            padding: 10px 15px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }
        
        .btn-danger {
            background: #dc3545;
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .btn-success {
            background: #28a745;
            margin-bottom: 20px;
            font-size: 16px;
            padding: 12px 20px;
        }
        
        .btn-success:hover {
            background: #218838;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .empty-state-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
        
        .empty-state h3 {
            margin: 0 0 10px;
            color: #333;
        }
        
        .empty-state p {
            color: #666;
            margin: 0;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 5px 30px rgba(0,0,0,0.3);
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .modal-title {
            margin: 0;
            color: #333;
        }
        
        .close {
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            color: #999;
            transition: color 0.3s ease;
        }
        
        .close:hover {
            color: #333;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        
        input[type="text"], textarea, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        
        input[type="text"]:focus, textarea:focus, select:focus {
            border-color: #7c9eff;
            outline: none;
        }
        
        textarea {
            min-height: 150px;
            resize: vertical;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 30px;
        }
        
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .blog-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include('../layout/navbar.php'); ?>
    
    <div class="main-content">
        <div class="container">
            <div class="header">
                <h1>Dashboard</h1>
                <div class="header-user">
                    <div class="user-info">
                        <div class="user-name"><?php echo htmlspecialchars($_SESSION["username"]); ?></div>
                        <div class="user-role"><?php echo ucfirst(htmlspecialchars($_SESSION["role"])); ?></div>
                    </div>
                    <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
                </div>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <?php if (in_array($_SESSION['role'], ['admin', 'bloger'])): ?>
                <button class="btn btn-success" onclick="openModal()">+ Create New Post</button>
            <?php endif; ?>
            
            <?php if (empty($blogs)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">📝</div>
                    <h3>No blogs yet</h3>
                    <p>Start sharing your thoughts with the world by creating your first blog post.</p>
                </div>
            <?php else: ?>
                <div class="blog-list">
                    <?php foreach ($blogs as $blog): ?>
                        <div class="blog-card">
                            <div class="blog-image">
                    <?php if (!empty($blog['image_url'])): ?>
                <?php if (strpos($blog['image_url'], 'http') === 0): ?>
                     <img src="<?php echo htmlspecialchars($blog['image_url']); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                  <?php else: ?>
                  <img src="../<?php echo htmlspecialchars($blog['image_url']); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                <?php endif; ?>
    <?php else: ?>
        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #7c9eff, #5f86ff); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
            No Image
        </div>
    <?php endif; ?>
</div>
                            <div class="blog-content">
                                <div class="blog-title"><?php echo htmlspecialchars($blog['title']); ?></div>
                                <div class="blog-meta">
                                    Category: <?php echo htmlspecialchars($blog['category']); ?> | 
                                    Created: <?php echo date('M j, Y', strtotime($blog['created_at'])); ?>
                                </div>
                                <div class="blog-excerpt">
                                    <?php echo substr(htmlspecialchars($blog['content']), 0, 100) . '...'; ?>
                                </div>
                                <div class="blog-actions">
                                    <a href="view_blog.php?id=<?php echo $blog['id']; ?>" class="btn">View</a>
                                    <a href="#" class="btn" onclick="editBlog(<?php echo $blog['id']; ?>)">Edit</a>
                                    <a href="#" class="btn btn-danger" onclick="deleteBlog(<?php echo $blog['id']; ?>)">Delete</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal for creating/editing blog posts -->
    <div id="blogModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modalTitle">Create New Post</h2>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <form id="blogForm" action="save_post.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="blogId" name="blog_id" value="">
                
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" required>
                </div>
                
                <div class="form-group">
                    <label for="category">Category:</label>
                    <select id="category" name="category" required>
                        <option value="">-- Select Category --</option>
                        <option value="Guides">Guides</option>
                        <option value="Programming">Programming</option>
                        <option value="Lifestyle">Lifestyle</option>
                        <option value="Technology">Technology</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="image">Featured Image:</label>
                    <input type="file" id="image" name="image" accept="image/*">
                </div>
                
                <div class="form-group">
                    <label for="content">Content:</label>
                    <textarea id="content" name="content" required></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>

    <?php include('../layout/footer.php'); ?>

    <script>
        function openModal() {
            document.getElementById('modalTitle').textContent = 'Create New Post';
            document.getElementById('blogForm').reset();
            document.getElementById('blogId').value = '';
            document.getElementById('blogModal').style.display = 'flex';
        }
        
        function closeModal() {
            document.getElementById('blogModal').style.display = 'none';
        }
        
        function editBlog(id) {
            document.getElementById('modalTitle').textContent = 'Edit Post';
            document.getElementById('blogId').value = id;
            document.getElementById('title').value = 'Sample Blog Title';
            document.getElementById('category').value = 'Programming';
            document.getElementById('content').value = 'This is the blog content...';
            document.getElementById('blogModal').style.display = 'flex';
        }
        
        function deleteBlog(id) {
            if (confirm('Are you sure you want to delete this blog?')) {
                window.location.href = 'delete_blog.php?id=' + id;
            }
        }
        
        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('blogModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>