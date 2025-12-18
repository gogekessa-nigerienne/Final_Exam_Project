<?php
// Remove the session_start() check - config.php already starts the session
// Just check if session variables are set
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaloonReserve - Book Your Style</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation Bar -->
    <header class="site-header">
        <div class="container">
            <div class="logo">
                <a href="index.php">SaloonReserve</a>
            </div>
            
            <nav class="main-nav">
                <a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">🏠<br> Home</a>
                <a href="gallery.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'gallery.php' ? 'active' : ''; ?>">💇<br>Style Gallery</a>
                <a href="stylists.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'stylists.php' ? 'active' : ''; ?>">👩‍🎨<br>Our Stylists</a>
                <a href="book.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'book.php' ? 'active' : ''; ?>">📅<br>Book Now</a>
                <a href="admin.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'admin.php' ? 'active' : ''; ?>">Admin</a>
                
                <?php if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])): ?>
                    <a href="logout.php" class="nav-logout">Logout</a>
            
                <?php else: ?>
                    <a href="login.php" class="nav-login">Login</a>
                    <a href="signup.php" class="nav-signup">Sign Up</a>
                <?php endif; ?>
            </nav>
            
            <button class="mobile-menu-btn">☰</button>
        </div>
    </header>
    
    <main class="main-content">