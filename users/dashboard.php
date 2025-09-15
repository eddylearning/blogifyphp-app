<?php
session_start();

// ✅ Already present: Check if user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: ../auth/login.php");
    exit();
}

// Sample blog posts (replace with DB fetch later)
$blogs = [
    ["title" => "My First Blog", "category" => "Programming", "image" => "https://via.placeholder.com/150", "content" => "This is a short intro to my blog..."],
    ["title" => "Healthy Lifestyle", "category" => "Lifestyle", "image" => "https://via.placeholder.com/150", "content" => "Some lifestyle tips and tricks..."],
];
?>

<!-- [Style section remains unchanged] -->

<!-- Dashboard Header -->
<div class="dashboard-header">
  <h2>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?> </h2>
  <form action="../auth/logout.php" method="POST">
    <button type="submit" class="logout-btn">Logout</button>
  </form>
</div>

<!-- Dashboard Content -->
<div class="dashboard-content">
  <div class="header-actions">
    <h1>Your Blogs</h1>

    <!-- ✅ Only show Create Post button to 'admin' or 'blogger' -->
    <?php if (in_array($_SESSION["role"], ['admin', 'blogger'])): ?>
      <button class="btn-create" onclick="openModal()">+ Create New Post</button>
    <?php endif; ?>
  </div>

  <!-- Blog List -->
  <div class="blog-list">
    <?php foreach ($blogs as $blog): ?>
      <div class="blog-card">
        <img src="<?= $blog['image'] ?>" alt="Blog Image">
        <div class="content">
          <h3><?= htmlspecialchars($blog['title']) ?></h3>
          <p><strong>Category:</strong> <?= htmlspecialchars($blog['category']) ?></p>
          <p><?= htmlspecialchars($blog['content']) ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- ✅ Show modal only if allowed role -->
<?php if (in_array($_SESSION["role"], ['admin', 'blogger'])): ?>
<div class="modal" id="createModal">
  <div class="modal-content">
    <h2>Create New Post</h2>
    <form action="save_post.php" method="POST" enctype="multipart/form-data">
      <div>
        <label for="title">Post Title</label>
        <input type="text" id="title" name="title" required>
      </div>

      <div>
        <label for="category">Category</label>
        <select id="category" name="category" required>
          <option value="">-- Select Category --</option>
          <option value="Guides">Guides</option>
          <option value="Programming">Programming</option>
          <option value="Lifestyle">Lifestyle</option>
          <option value="Technology">Technology</option>
        </select>
      </div>

      <div>
        <label for="image">Featured Image</label>
        <input type="file" id="image" name="image" accept="image/*">
      </div>

      <div>
        <label for="content">Content</label>
        <textarea id="content" name="content" required></textarea>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-submit">Publish</button>
        <button type="button" class="btn btn-cancel" onclick="closeModal()">Cancel</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<script>
  // Modal control scripts (unchanged)
  const modal = document.getElementById('createModal');
  function openModal() { modal.style.display = 'flex'; }
  function closeModal() { modal.style.display = 'none'; }
  window.onclick = function(e) {
    if (e.target === modal) closeModal();
  }
</script>

<?php include('../layout/footer.php'); ?>
