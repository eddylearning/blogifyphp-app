<?php
require_once('../config.php');
require_once('../db.php');
session_start();

$error = '';
$success = '';
$username = $email = $role = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);
    $role = trim($_POST["role"]);

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($role)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        $allowed_roles = ['user', 'blogger', 'employee'];
        if (!in_array($role, $allowed_roles)) {
            $error = "Invalid role selected.";
        } else {
            try {
                $checkEmail = "SELECT id FROM users WHERE email = :email";
                $stmt = $conn->prepare($checkEmail);
                $stmt->bindParam(":email", $email);
                $stmt->execute();
                
                if ($stmt->rowCount() > 0) {
                    $error = "Email address is already in use.";
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    
                    $sql = "INSERT INTO users (username, email, password, role) 
                            VALUES (:username, :email, :password, :role)";
                    $stmt = $conn->prepare($sql);
                    
                    $stmt->bindParam(":username", $username);
                    $stmt->bindParam(":email", $email);
                    $stmt->bindParam(":password", $hashedPassword);
                    $stmt->bindParam(":role", $role);
                    
                    if ($stmt->execute()) {
                        $success = "Registration successful! You can now login.";
                    }
                }
            } catch (PDOException $e) {
                $error = "An error occurred: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Blogify PHP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #1a2a6c, #b21f1f, #fdbb2d);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .form-container {
            width: 100%;
            max-width: 400px;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            margin: auto;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .form-container h3 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        input[type="text"], input[type="email"], input[type="password"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        
        button {
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        
        button:hover {
            background: #0056b3;
        }
        
        .error {
            background: #ffebee;
            color: #c62828;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .success {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .extra-links {
            text-align: center;
            margin-top: 15px;
        }
        
        .extra-links a {
            color: #007bff;
            text-decoration: none;
        }
        
        .extra-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php include('../layout/navbar.php'); ?>
    
    <div class="form-container">
        <h3>Create Your Account</h3>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
            <p><a href="login.php">Click here to login</a></p>
        <?php else: ?>
            <form action="register.php" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" required>
                </div>
                
                <div class="form-group">
                    <label for="role">Account Type</label>
                    <select name="role" id="role" required>
                        <option value="">-- Select Account Type --</option>
                        <option value="user" <?php echo ($role == 'user') ? 'selected' : ''; ?>>Regular User</option>
                        <option value="blogger" <?php echo ($role == 'bloger') ? 'selected' : ''; ?>>Blogger</option>
                        <option value="employee" <?php echo ($role == 'employee') ? 'selected' : ''; ?>>Employee</option>
                    </select>
                </div>
                
                <button type="submit">Create Account</button>
            </form>
            
            <div class="extra-links">
                <p>Already have an account? <a href="login.php">Sign in</a></p>
            </div>
        <?php endif; ?>
    </div>
    
    <?php include('../layout/footer.php'); ?>
</body>
</html>
