<?php include 'includes/config.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>African Hairstyle Gallery - SaloonReserve</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Additional styles for gallery */
        .gallery-page {
            padding: 20px;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .page-title {
            color: #8B4513;
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .page-subtitle {
            color: #666;
            font-size: 1.1em;
        }
        
        .filter-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .filter-buttons {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .filter-btn {
            padding: 10px 20px;
            border: 2px solid #8B4513;
            background: white;
            color: #8B4513;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
        }
        
        .filter-btn:hover,
        .filter-btn.active {
            background: #8B4513;
            color: white;
        }
        
        .hairstyle-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }
        
        .hairstyle-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .hairstyle-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        
        .hairstyle-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-bottom: 3px solid #8B4513;
        }
        
        .hairstyle-content {
            padding: 20px;
        }
        
        .hairstyle-name {
            color: #8B4513;
            font-size: 1.3em;
            margin-bottom: 10px;
        }
        
        .hairstyle-category {
            display: inline-block;
            background: #E8D9C5;
            color: #8B4513;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            margin-bottom: 10px;
        }
        
        .hairstyle-desc {
            color: #666;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 15px;
        }
        
        .hairstyle-meta {
            display: flex;
            justify-content: space-between;
            color: #777;
            font-size: 13px;
            margin-bottom: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        
        .select-btn {
            background: #8B4513;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }
        
        .select-btn:hover {
            background: #A0522D;
        }
        
        .inclusive-note {
            background: #FFF9E6;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin: 30px 0;
            border-left: 5px solid #8B4513;
        }
        
        @media (max-width: 768px) {
            .hairstyle-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 20px;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container gallery-page">
        <div class="page-header">
            <h1 class="page-title">African Hairstyle Gallery</h1>
            <p class="page-subtitle">Browse here to see a collection of traditional and modern African hairstyles</p>
        </div>
        
        <div class="inclusive-note">
            <strong> All hairstyles are available for men, women, and everyone!</strong>
            <p style="margin-top: 5px; color: #666;">Everybody is welcomed.</p>
        </div>
        
        <div class="filter-container">
            <div class="filter-buttons">
                <button class="filter-btn active" onclick="filterStyles('all')">All Styles</button>
                <button class="filter-btn" onclick="filterStyles('braids')">Braids</button>
                <button class="filter-btn" onclick="filterStyles('natural')">Natural Hair</button>
                <button class="filter-btn" onclick="filterStyles('wigs')">Wigs & Weaves</button>
                <button class="filter-btn" onclick="filterStyles('mens')">Men's Styles</button>
                <button class="filter-btn" onclick="filterStyles('locs')">Locs</button>
            </div>
        </div>
        
        <div class="hairstyle-grid" id="hairstyleGrid">
            <?php
            // FETCH HAIRSTYLES FROM DATABASE
            $sql = "SELECT * FROM hairstyles ORDER BY created_at DESC";
            $result = mysqli_query($conn, $sql);
            
            // Check if query was successful and if we have results
            if ($result && mysqli_num_rows($result) > 0) {
                // Loop through each hairstyle from the database
                while ($row = mysqli_fetch_assoc($result)) {
                    $image_file = $row['image_url'];
                    $name = $row['name'];
                    $category = $row['category'];
                    $gender = $row['gender'];
                    $time = $row['estimated_time'];
                    
                    // Create a description using the database data
                    // Note: You might want to add a 'description' column to your database later
                    $description = "A beautiful {$category} hairstyle. Estimated time: {$time}";
                    
                    // Set image path
                    $image_path = "images/" . $image_file;
                    $image_exists = file_exists($image_path);
            ?>
            <div class="hairstyle-card" data-category="<?php echo $category; ?>" data-gender="<?php echo $gender; ?>">
                <?php if($image_exists): ?>
                    <img src="<?php echo $image_path; ?>" 
                         alt="<?php echo $name; ?>" 
                         class="hairstyle-image">
                <?php else: ?>
                    <!-- Fallback placeholder if image doesn't exist on server -->
                    <div style="width: 100%; height: 250px; background: linear-gradient(45deg, #E8D9C5, #F5E8D0); display: flex; align-items: center; justify-content: center; color: #8B4513; font-size: 18px; font-weight: bold;">
                        <?php echo $name; ?>
                    </div>
                <?php endif; ?>
                
                <div class="hairstyle-content">
                    <h3 class="hairstyle-name"><?php echo $name; ?></h3>
                    
                    <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                        <span class="hairstyle-category"><?php echo ucfirst($category); ?></span>
                        <?php if($gender == 'unisex' || $gender == 'all'): ?>
                            <span class="hairstyle-category" style="background: #4CAF50; color: white;">Unisex</span>
                        <?php elseif($gender == 'men'): ?>
                            <span class="hairstyle-category" style="background: #2196F3; color: white;">Men</span>
                        <?php elseif($gender == 'women'): ?>
                            <span class="hairstyle-category" style="background: #E91E63; color: white;">Women</span>
                        <?php endif; ?>
                    </div>
                    
                    <p class="hairstyle-desc"><?php echo $description; ?></p>
                    
                    <div class="hairstyle-meta">
                        <span><?php echo $time; ?></span>
                    </div>
                    
                    <button class="select-btn" onclick="selectHairstyle('<?php echo addslashes($name); ?>')">
                         Select This Style
                    </button>
                </div>
            </div>
            <?php 
                } // End of while loop
            } else {
                // Display message if no hairstyles found in database
                echo '<div style="text-align: center; padding: 40px; color: #666; grid-column: 1 / -1;">';
                echo '<p style="font-size: 18px; margin-bottom: 20px;">No hairstyles available in the gallery yet.</p>';
                echo '<p>Add hairstyles from the Admin Panel to see them here.</p>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
    
    <script>
        // Filter hairstyles function
        function filterStyles(category) {
            // Update active button
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            
            // Get all hairstyle cards
            const cards = document.querySelectorAll('.hairstyle-card');
            
            // Show/hide based on category
            cards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');
                
                if (category === 'all') {
                    card.style.display = 'block';
                } else if (category === 'mens') {
                    const gender = card.getAttribute('data-gender');
                    if (gender === 'men') {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                } else {
                    if (cardCategory === category) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                }
            });
        }
        
        // Select hairstyle function
        function selectHairstyle(styleName) {
            // Store in session storage for booking page
            sessionStorage.setItem('selectedHairstyle', styleName);
            
            // Show confirmation
            const confirmed = confirm(`You selected: ${styleName}\n\nWould you like to book this style?`);
            
            if (confirmed) {
                <?php if(isset($_SESSION['user_id'])): ?>
                    // User is logged in - go to booking
                    window.location.href = `book.php?style=${encodeURIComponent(styleName)}`;
                <?php else: ?>
                    // User not logged in - go to login
                    window.location.href = `login.php?style=${encodeURIComponent(styleName)}`;
                <?php endif; ?>
            }
        }
        
        // Initialize filter buttons
        document.addEventListener('DOMContentLoaded', function() {
            // Make "All Styles" button active by default
            const allBtn = document.querySelector('.filter-btn[onclick*="all"]');
            if (allBtn) allBtn.classList.add('active');
            
            // Check for previously selected style
            const selectedStyle = sessionStorage.getItem('selectedHairstyle');
            if (selectedStyle) {
                console.log('Previously selected:', selectedStyle);
            }
        });
    </script>
</body>
</html>