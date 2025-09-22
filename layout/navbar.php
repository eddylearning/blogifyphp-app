<?php
require_once(dirname(__FILE__) . '/../config.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<style>
    /* Navbar Styles */
    .navbar {
        background: linear-gradient(135deg, #1a2a6c, #2d3561);
        padding: 0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        position: sticky;
        top: 0;
        z-index: 1000;
    }
    
    .navbar-container {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px;
    }
    
    .navbar-brand {
        display: flex;
        align-items: center;
        text-decoration: none;
        padding: 15px 0;
    }
    
    .navbar-logo {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #7c9eff, #5f86ff);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 18px;
        margin-right: 10px;
    }
    
    .navbar-name {
        color: white;
        font-size: 20px;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    
    .navbar-menu {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
    }
    
    .navbar-item {
        margin: 0;
    }
    
    .navbar-link {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        padding: 25px 20px;
        display: block;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .navbar-link:hover {
        color: white;
        background: rgba(255, 255, 255, 0.1);
    }
    
    .navbar-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 3px;
        background: #7c9eff;
        transform: translateX(-50%);
        transition: width 0.3s ease;
    }
    
    .navbar-link:hover::after {
        width: 80%;
    }
    
    .navbar-user {
        display: flex;
        align-items: center;
        margin-left: 20px;
    }
    
    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #7c9eff, #5f86ff);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        cursor: pointer;
        transition: transform 0.3s ease;
    }
    
    .user-avatar:hover {
        transform: scale(1.1);
    }
    
    .user-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        min-width: 180px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        transition: all 0.3s ease;
        z-index: 1001;
    }
    
    .user-menu:hover .user-dropdown {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    
    .user-dropdown-link {
        display: block;
        padding: 12px 20px;
        color: #333;
        text-decoration: none;
        transition: background 0.3s ease;
    }
    
    .user-dropdown-link:hover {
        background: #f8f9fa;
    }
    
    .user-dropdown-link:first-child {
        border-radius: 8px 8px 0 0;
    }
    
    .user-dropdown-link:last-child {
        border-radius: 0 0 8px 8px;
    }
    
    .user-divider {
        height: 1px;
        background: #e9ecef;
        margin: 5px 0;
    }
    
    .navbar-cta {
    background: linear-gradient(135deg, #7c9eff, #5f86ff);
    color: white;
    padding: 8px 20px;
    border-radius: 20px;
    text-decoration: none;
    margin-left: 20px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 5px rgba(124, 158, 255, 0.3);
    display: flex;
    align-items: center;
    height: 40px; /* Match the height of navbar items */
}
    
    .navbar-cta:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(124, 158, 255, 0.4);
    }
    
    .mobile-menu-btn {
        display: none;
        background: none;
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        padding: 15px 0;
    }
    
    @media (max-width: 768px) {
        .navbar-menu {
            position: fixed;
            top: 70px;
            left: 0;
            width: 100%;
            background: #2d3561;
            flex-direction: column;
            align-items: flex-start;
            padding: 0;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            z-index: 999;
        }
        
        .navbar-menu.active {
            transform: translateX(0);
        }
        
        .navbar-link {
            padding: 15px 20px;
            width: 100%;
        }
        
        .navbar-cta {
            margin: 15px 20px;
        }
        
        .mobile-menu-btn {
            display: block;
        }
        
        .user-dropdown {
            position: static;
            box-shadow: none;
            opacity: 1;
            visibility: visible;
            transform: none;
            display: none;
            background: rgba(255, 255, 255, 0.1);
            width: 100%;
            border-radius: 0;
        }
        
        .user-menu.active .user-dropdown {
            display: block;
        }
    }
</style>

<nav class="navbar">
    <div class="navbar-container">
        <a href="<?php echo BASE_URL; ?>" class="navbar-brand">
            <div class="navbar-logo">I&</div>
            <div class="navbar-name">Blogify App</div>
        </a>
        
        <button class="mobile-menu-btn" onclick="toggleMobileMenu()">☰</button>
        
        <ul class="navbar-menu" id="navbarMenu">
            <li class="navbar-item">
                <a href="#featured" class="navbar-link">Featured</a>
            </li>
            <li class="navbar-item">
                <a href="#topics" class="navbar-link">Topics</a>
            </li>
            <li class="navbar-item">
                <a href="#newsletter" class="navbar-link">Newsletter</a>
            </li>
            
            <?php if (isset($_SESSION['username'])): ?>
                <li class="navbar-item navbar-user user-menu">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                    </div>
                    <div class="user-dropdown">
                        <a href="<?php echo BASE_URL; ?>users/dashboard.php" class="user-dropdown-link">Dashboard</a>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <a href="<?php echo BASE_URL; ?>admin/dashboard.php" class="user-dropdown-link">Admin Panel</a>
                        <?php endif; ?>
                        <div class="user-divider"></div>
                        <a href="<?php echo BASE_URL; ?>auth/logout.php" class="user-dropdown-link">Logout</a>
                    </div>
                </li>
            <?php else: ?>
                <li class="navbar-item">
                    <a href="<?php echo BASE_URL; ?>auth/login.php" class="navbar-link">Login</a>
                </li>
                <li class="navbar-item">
                    <a href="<?php echo BASE_URL; ?>auth/register.php" class="navbar-link">Sign Up</a>
                </li>
            <?php endif; ?>
            
            <li class="navbar-item">
                <!-- <a href="#latest" class="navbar-cta">Read Latest</a> -->
               <a href="<?php echo BASE_URL; ?>" class="navbar-cta">Read Latest</a>  
            </li>
        </ul>
    </div>
</nav>

<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('navbarMenu');
        menu.classList.toggle('active');
    }
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('navbarMenu');
        const menuBtn = document.querySelector('.mobile-menu-btn');
        
        if (!menu.contains(event.target) && !menuBtn.contains(event.target)) {
            menu.classList.remove('active');
        }
    });
</script>
