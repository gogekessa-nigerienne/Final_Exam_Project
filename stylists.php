<?php
include 'includes/config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Our Stylists - SaloonReserve</title>
    <style>
        body {
            font-family: Arial;
            background: #f8f5f0;
            margin: 0;
        }
        
        /* Header */
        .header {
            background: #8B4513;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }
        
        .logo {
            font-size: 28px;
            font-weight: bold;
        }
        
        .logo a {
            color: white;
            text-decoration: none;
        }
        
        .nav-links a {
            color: white;
            margin-left: 20px;
            text-decoration: none;
        }
        
        /* Main Content */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        h1 {
            text-align: center;
            color: #8B4513;
        }
        
        .stylist-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }
        
        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .card h3 {
            color: #8B4513;
            margin-top: 0;
        }
        
        .book-btn {
            background: #8B4513;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 15px;
            font-weight: bold;
        }
        
        .book-btn:hover {
            background: #A0522D;
        }
        
        /* Footer */
        .footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            background: white;
            border-radius: 10px;
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <!-- Header with SaloonReserve Title -->
    <div class="header">
        <div class="logo">
            <a href="index.php">SaloonReserve</a>
        </div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="gallery.php">Gallery</a>
            <a href="stylists.php" style="font-weight: bold;">Stylists</a>
            <a href="book.php">Book Now</a>
        </div>
    </div>
    
    <div class="container">
        <h1>Our Professional Stylists</h1>
        <p style="text-align: center; color: #666;">Welcoming clients of all genders</p>
        
        <div class="stylist-cards">
            <?php
            // Fetch stylists from database
            $sql = "SELECT * FROM stylists ORDER BY name ASC";
            $result = mysqli_query($conn, $sql);
            
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <div class="card">
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p><strong>Specialty:</strong> <?php echo htmlspecialchars($row['specialty']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($row['phone']); ?></p>
                        
                        <?php if(!empty($row['photo'])): ?>
                            <img src="images/<?php echo htmlspecialchars($row['photo']); ?>" 
                                 alt="<?php echo htmlspecialchars($row['name']); ?>" 
                                 style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; margin: 10px 0;">
                        <?php endif; ?>
                        
                        <p style="color: green; font-weight: bold;"> All Genders Welcome</p>
                        
                        <?php
                        // Check if user is logged in
                        if(isset($_SESSION['user_id'])) {
                            echo '<a href="book.php" class="book-btn">Book with ' . htmlspecialchars($row['name']) . '</a>';
                        } else {
                            echo '<a href="login.php" class="book-btn">Login to Book</a>';
                        }
                        ?>
                    </div>
                    <?php
                }
            } else {
                echo '<div class="no-data">No stylists available yet. Check back soon!</div>';
            }
            ?>
        </div>
    </div>
    
    
</body>
</html>