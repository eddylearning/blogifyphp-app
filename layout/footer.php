<style>
    /* Footer Styles */
    .footer {
        background: linear-gradient(135deg, #1a2a6c, #2d3561);
        color: rgba(255, 255, 255, 0.8);
        padding: 50px 0 30px;
        margin-top: 80px;
    }
    
    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }
    
    .footer-content {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr;
        gap: 40px;
        margin-bottom: 40px;
    }
    
    .footer-section h3 {
        color: white;
        font-size: 18px;
        margin-bottom: 20px;
        position: relative;
    }
    
    .footer-section h3::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 40px;
        height: 3px;
        background: #7c9eff;
    }
    
    .footer-about p {
        line-height: 1.6;
        margin-bottom: 20px;
    }
    
    .footer-social {
        display: flex;
        gap: 15px;
    }
    
    .social-icon {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .social-icon:hover {
        background: #7c9eff;
        transform: translateY(-3px);
    }
    
    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .footer-links li {
        margin-bottom: 12px;
    }
    
    .footer-links a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: color 0.3s ease;
    }
    
    .footer-links a:hover {
        color: #7c9eff;
    }
    
    .footer-contact p {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
    }
    
    .footer-contact i {
        margin-right: 10px;
        width: 20px;
        color: #7c9eff;
    }
    
    .footer-newsletter {
        background: rgba(255, 255, 255, 0.05);
        padding: 20px;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .footer-newsletter h4 {
        color: white;
        margin-bottom: 15px;
    }
    
    .footer-newsletter p {
        margin-bottom: 15px;
        font-size: 14px;
    }
    
    .newsletter-form {
        display: flex;
        gap: 10px;
    }
    
    .newsletter-input {
        flex: 1;
        padding: 10px 15px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border-radius: 5px;
        outline: none;
    }
    
    .newsletter-input::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }
    
    .newsletter-btn {
        padding: 10px 20px;
        background: linear-gradient(135deg, #7c9eff, #5f86ff);
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .newsletter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(124, 158, 255, 0.3);
    }
    
    .footer-bottom {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding-top: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }
    
    .footer-copyright {
        color: rgba(255, 255, 255, 0.6);
        font-size: 14px;
    }
    
    .footer-bottom-links {
        display: flex;
        gap: 20px;
    }
    
    .footer-bottom-links a {
        color: rgba(255, 255, 255, 0.6);
        text-decoration: none;
        font-size: 14px;
        transition: color 0.3s ease;
    }
    
    .footer-bottom-links a:hover {
        color: #7c9eff;
    }
    
    @media (max-width: 768px) {
        .footer-content {
            grid-template-columns: 1fr;
            gap: 30px;
        }
        
        .footer-bottom {
            flex-direction: column;
            text-align: center;
            gap: 15px;
        }
        
        .footer-bottom-links {
            justify-content: center;
        }
    }
</style>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-content">
            <div class="footer-section footer-about">
                <h3>About Blogify</h3>
                <p>Blogify is a modern blogging platform where creators share their stories, ideas, and expertise with the world. Join our community of writers and readers today.</p>
                <div class="footer-social">
                    <a href="#" class="social-icon">
                        <span>f</span>
                    </a>
                    <a href="#" class="social-icon">
                        <span>t</span>
                    </a>
                    <a href="#" class="social-icon">
                        <span>in</span>
                    </a>
                    <a href="#" class="social-icon">
                        <span>ig</span>
                    </a>
                </div>
            </div>
            
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="#featured">Featured Posts</a></li>
                    <li><a href="#topics">Topics</a></li>
                    <li><a href="#newsletter">Newsletter</a></li>
                    <li><a href="#latest">Latest Articles</a></li>
                    <li><a href="/auth/register.php">Join Us</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Categories</h3>
                <ul class="footer-links">
                    <li><a href="#">Technology</a></li>
                    <li><a href="#">Programming</a></li>
                    <li><a href="#">Lifestyle</a></li>
                    <li><a href="#">Guides</a></li>
                    <li><a href="#">Tutorials</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Contact</h3>
                <div class="footer-contact">
                    <p><i>📧</i> contact@blogify.com</p>
                    <p><i>📱</i> +1 (555) 123-4567</p>
                    <p><i>📍</i> San Francisco, CA</p>
                </div>
                
                <div class="footer-newsletter">
                    <h4>Subscribe to Newsletter</h4>
                    <p>Get the latest posts delivered to your inbox weekly.</p>
                    <form class="newsletter-form" onsubmit="subscribeNewsletter(event)">
                        <input type="email" class="newsletter-input" placeholder="Your email address" required>
                        <button type="submit" class="newsletter-btn">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="footer-copyright">
                © <?php echo date('Y'); ?> Blogify PHP. All rights reserved.
            </div>
            <div class="footer-bottom-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Cookie Policy</a>
                <a href="#">Sitemap</a>
            </div>
        </div>
    </div>
</footer>

<script>
    function subscribeNewsletter(event) {
        event.preventDefault();
        const email = event.target.querySelector('input[type="email"]').value;
        
        // In a real application, you would send this to your server
        alert('Thank you for subscribing with: ' + email);
        event.target.reset();
    }
</script>
