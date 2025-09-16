<?php
if (session_status() === PHP_SESSION_NONE){
  session_start(); // Required to access session variables
}
?>

<header>
  <div class="container nav" role="navigation" aria-label="Main">
    <a href="/" class="brand">
      <span class="brand-badge" aria-hidden="true">I&</span>
      <span>blogify app</span>
    </a>

    <nav class="menu">
      <a href="#featured">Featured</a>
      <a href="#topics">Topics</a>
      <a href="#newsletter">Newsletter</a>

      <?php if (isset($_SESSION['username'])): ?>
        <!-- Show if user is logged in -->

        <?php if ($_SESSION['role'] === 'admin'): ?>
          <!-- Admin-specific link -->
          <a href="/admin/dashboard.php">Admin Panel</a>
        <?php elseif ($_SESSION['role'] === 'blogger'): ?>
          <!-- Blogger-specific link -->
          <a href="users\dashboard.php">Dashboard</a>
        <?php endif; ?>

        <a href="/auth/logout.php">Logout</a>

      <?php else: ?>
        <!-- Show if user is NOT logged in -->
        <a href="auth\login.php">Login</a>
        <a href="auth\register.php">Sign Up</a>
      <?php endif; ?>

      <a class="cta" href="#latest">Read Latest</a>
    </nav>
  </div>
</header>