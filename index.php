<?php include 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaloonReserve - Professional Hair Booking</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container">
        <!-- Hero Section -->
        <section class="hero-section">
            <h1 class="hero-title">Welcome to SaloonReserve</h1>
            <p class="hero-subtitle">
                Professional braiding, natural hair care, wigs, buzz cuts, crew cuts, man buns - 
                all types of hairstyles for men and women. We welcome everyone!
            </p>
            
            <div class="hero-buttons">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="book.php" class="btn btn-primary">Book Appointment</a>
                    <a href="gallery.php" class="btn btn-secondary">Browse Styles</a>
                <?php else: ?>
                    <a href="signup.php" class="btn btn-primary">Get Started - Sign Up</a>
                    <a href="login.php" class="btn btn-secondary">Login to Account</a>
                <?php endif; ?>
            </div>
        </section>
        
        <!-- Features Section -->
        <section class="features-section">
            <h2 style="text-align: center; color: #8B4513; margin-bottom: 40px; font-size: 2em;">
                Why Choose SaloonReserve?
            </h2>
            
            <div class="features-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
                <div class="feature-card" style="background: white; padding: 25px; border-radius: 10px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    <div style="font-size: 40px; margin-bottom: 15px;"></div>
                    <h3 style="color: #8B4513; margin-bottom: 10px;">All Genders Welcome</h3>
                    <p style="color: #666;">Men, women, and everyone in between. Hair care is for all!</p>
                </div>
                
                <div class="feature-card" style="background: white; padding: 25px; border-radius: 10px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    <div style="font-size: 40px; margin-bottom: 15px;"></div>
                    <h3 style="color: #8B4513; margin-bottom: 10px;">Expert Stylists</h3>
                    <p style="color: #666;">Professional stylists specializing in African hair and modern styles</p>
                </div>
                
                <div class="feature-card" style="background: white; padding: 25px; border-radius: 10px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    <div style="font-size: 40px; margin-bottom: 15px;"></div>
                    <h3 style="color: #8B4513; margin-bottom: 10px;">Easy Booking</h3>
                    <p style="color: #666;">Book appointments online in just a few clicks</p>
                </div>
            </div>
        </section>
        
        <!-- Services Section -->
        <section style="margin-top: 60px; text-align: center;">
            <h2 style="color: #8B4513; margin-bottom: 30px; font-size: 2em;">Our Services</h2>
            <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 20px;">
                <div style="background: white; padding: 20px; border-radius: 8px; width: 200px; border-top: 4px solid #8B4513;">
                    <h4>African Braids</h4>
                    <p>Box, cornrows, Fulani</p>
                </div>
                <div style="background: white; padding: 20px; border-radius: 8px; width: 200px; border-top: 4px solid #8B4513;">
                    <h4>Natural Hair Care</h4>
                    <p>Wash, treatment, styling</p>
                </div>
                <div style="background: white; padding: 20px; border-radius: 8px; width: 200px; border-top: 4px solid #8B4513;">
                    <h4>Wig Installation</h4>
                    <p>Lace front, custom wigs</p>
                </div>
                <div style="background: white; padding: 20px; border-radius: 8px; width: 200px; border-top: 4px solid #8B4513;">
                    <h4>Men's Styles</h4>
                    <p>Buzz cut, crew cut, man bun</p>
                </div>
                
            </div>
        </section>
        
    </div>
    
    <?php include 'includes/footer.php'; ?>
    
    
    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-menu-btn').addEventListener('click', function() {
            document.querySelector('.main-nav').classList.toggle('show');
        });
    </script>
    
</body>
</html>