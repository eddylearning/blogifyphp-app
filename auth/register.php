<?php
// process the form

// connection
require('../db.php');

// register
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $role = trim($_POST["role"]); // <-- New: Get role from form

    // Validate input fields
    if (!empty($username) && !empty($email) && !empty($password) && !empty($role)) {

        // ✅ New: Role validation to allow only specific values
        $allowed_roles = ['user', 'blogger', 'employee'];
        if (!in_array($role, $allowed_roles)) {
            echo "Invalid role selected.";
            exit;
        }

        // Hash password
        $hashedpassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            // ✅ New: Insert role into database
            $sql = "INSERT INTO users (username, email, password, role) 
                    VALUES (:username, :email, :password, :role)";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $hashedpassword);
            $stmt->bindParam(":role", $role); // <-- New

            if ($stmt->execute()) {
                // Redirect to login after successful registration
                header("Location: ../auth/login.php");
                exit;
            }
        } catch (PDOException $e) {
            echo "An error occurred: " . $e->getMessage();
        }
    } else {
        echo "All fields are required.";
    }
}
?>

<?php include('../layout/navbar.php'); ?>

<style>
  /* [Same styling as before — no changes] */
</style>

<div class="form-container">
  <h3>Register</h3>
  <form action="" method="POST">
    <label for="username">User Name:</label>
    <input type="text" name="username" id="username" required>

    <label for="email">Enter Email:</label>
    <input type="email" name="email" id="email" required>

    <label for="password">Enter Password:</label>
    <input type="password" name="password" id="password" required>

    <!-- ✅ New: Role selection dropdown -->
    <label for="role">Select Role:</label>
    <select name="role" id="role" required>
      <option value="">-- Select Role --</option>
      <option value="user">User</option>
      <option value="blogger">Blogger</option>
      <option value="employee">Employee</option>
      <!-- Note: Admin role is NOT available here -->
    </select>

    <button type="submit">Submit</button>
  </form>
</div>

<?php include('../layout/footer.php'); ?>
