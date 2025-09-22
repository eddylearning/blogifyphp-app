<!-- <?php
// Enable error reporting
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

// Start session
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

// Database connection
// require_once('db.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blogify PHP</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Blogify PHP</h1>
        <p>If you see this, the basic page is working.</p>
        
        <?php
        // Test database connection
        // try {
        //     $stmt = $conn->query("SELECT COUNT(*) as total FROM users");
        //     $result = $stmt->fetch();
        //     echo "<p class='success'>Database connected. Users in database: " . $result['total'] . "</p>";
        // } catch (Exception $e) {
        //     echo "<p class='error'>Database error: " . $e->getMessage() . "</p>";
        // }
        // ?>
        
        <hr>
        
        <?php include('layout/navbar.php'); ?>
        
        <hr>
        
        <h2>Test Links:</h2>
        <ul>
            <li><a href="auth/login.php">Login</a></li>
            <li><a href="auth/register.php">Register</a></li>
            <li><a href="users/dashboard.php">Dashboard</a></li>
        </ul>
    </div>
</body>
</html> -->



<?php
require_once('config.php');
require_once('db.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blogify PHP - Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: radial-gradient(1200px 700px at 80% -10%, rgba(124,158,255,.25), transparent 60%),
                        radial-gradient(900px 600px at -10% 20%, rgba(155,255,209,.18), transparent 60%),
                        #0b0d13;
            color: #e8ebf1;
            line-height: 1.6;
            min-height: 100vh;
        }
        
        .hero {
            padding: 5rem 0 3rem;
            text-align: center;
        }
        
        .hero h1 {
            font-size: clamp(2rem, 2.2rem + 2vw, 3.4rem);
            line-height: 1.1;
            margin: 0.6rem 0 1rem;
            background: linear-gradient(90deg, #e8ebf1, #9bffd1);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .lead {
            color: #a7b1c2;
            font-size: clamp(1rem, .95rem + .3vw, 1.15rem);
            max-width: 600px;
            margin: 0 auto 2rem;
        }
        
        .hero-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn {
            background: linear-gradient(135deg, #7c9eff, #5f86ff);
            border: none;
            color: #0b0d13;
            font-weight: 900;
            padding: .8rem 1.1rem;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(0,0,0,.35);
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(124,158,255,.4);
        }
        
        .btn.secondary {
            background: transparent;
            color: #e8ebf1;
            border: 1px solid #2a3652;
            box-shadow: none;
        }
        
        .btn.secondary:hover {
            background: rgba(124,158,255,.18);
        }
        
        .features {
            padding: 4rem 0;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .features h2 {
            text-align: center;
            margin-bottom: 3rem;
            font-size: 2.5rem;
        }
        
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        
        .feature-card {
            background: rgba(26,32,48,.8);
            border: 1px solid rgba(124,158,255,.18);
            border-radius: 18px;
            padding: 2rem;
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
        }
        
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .feature-card h3 {
            margin: 0 0 1rem;
            color: #7c9eff;
        }
        
        .cta-section {
            background: linear-gradient(180deg, rgba(18,22,34,.8), rgba(18,22,34,.95));
            border: 1px solid rgba(124,158,255,.18);
            border-radius: 18px;
            padding: 3rem;
            margin: 4rem auto;
            max-width: 800px;
            text-align: center;
        }
        
        .cta-section h2 {
            margin-top: 0;
            margin-bottom: 1rem;
        }
        
        .newsletter-form {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .newsletter-input {
            flex: 1;
            padding: 1rem;
            border: 1px solid rgba(124,158,255,.18);
            background: rgba(26,32,48,.8);
            color: #e8ebf1;
            border-radius: 12px;
            outline: none;
        }
        
        .newsletter-btn {
            background: linear-gradient(135deg, #7c9eff, #5f86ff);
            color: #0b0d13;
            border: none;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 800;
        }
    </style>
</head>
<body>
    <?php include('layout/navbar.php'); ?>
    
    <section class="hero">
        <div class="container">
            <div class="eyebrow">Fresh takes, every week</div>
            <h1>Stories on design, code, and the creative process.</h1>
            <p class="lead">Practical tutorials, opinion pieces, and industry interviews curated for makers. Zero fluff, just useful reads.</p>
            <div class="hero-actions">
                <?php if (isset($_SESSION['username'])): ?>
                    <a href="<?php echo BASE_URL; ?>users/dashboard.php" class="btn">Go to Dashboard</a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>auth/register.php" class="btn">Get Started</a>
                    <a href="<?php echo BASE_URL; ?>auth/login.php" class="btn secondary">Sign In</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="features">
        <h2>Why Choose Blogify?</h2>
        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-icon">✍️</div>
                <h3>Easy Writing</h3>
                <p>Intuitive editor that makes writing and formatting your posts a breeze.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🎨</div>
                <h3>Beautiful Design</h3>
                <p>Modern, responsive themes that look great on any device.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🚀</div>
                <h3>Fast Performance</h3>
                <p>Optimized for speed so your readers get the best experience.</p>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <h2>Join Our Community</h2>
        <p>Get the latest posts and updates delivered to your inbox every week.</p>
        <form class="newsletter-form" onsubmit="subscribeNewsletter(event)">
            <input type="email" class="newsletter-input" placeholder="Enter your email" required>
            <button type="submit" class="newsletter-btn">Subscribe</button>
        </form>
    </section>

    <?php include('layout/footer.php'); ?>

    <script>
        function subscribeNewsletter(event) {
            event.preventDefault();
            const email = event.target.querySelector('input[type="email"]').value;
            alert('Thank you for subscribing with: ' + email);
            event.target.reset();
        }
    </script>
</body>
</html>