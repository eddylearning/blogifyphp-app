<!-- <?php
session_start();
session_unset();
session_destroy();
?>

<?php include('../layout/navbar.php'); ?>

<div style="text-align:center; padding: 50px;">
  <h2>You have been logged out.</h2>
  <p>Redirecting to login page...</p>
</div>

<?php include('../layout/footer.php'); ?>

<script>
  setTimeout(() => {
    window.location.href = 'login.php';
  }, 2000); // Redirect after 2 seconds
</script> -->

<?php
session_start();
session_unset();
session_destroy();
header("Location: login.php");
exit();
