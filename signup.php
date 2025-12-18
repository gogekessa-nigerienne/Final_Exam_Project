<?php
include 'includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $admin_code = $_POST['admin_code'];
    
    // Default role is customer
    $role = 'customer';
    
    // Check if admin code is correct
    if ($admin_code === 'ADMIN2023') { // Change this to your secret code
        $role = 'admin';
    }
    
    // Check if email already exists
    $check_email = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $check_email);
    
    if (mysqli_num_rows($result) > 0) {
        $error = "Email already registered!";
    } else {
        // Hash password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (name, email, phone, password, role) 
                VALUES ('$name', '$email', '$phone', '$hashed_password', '$role')";
        
        if (mysqli_query($conn, $sql)) {
            $_SESSION['user_id'] = mysqli_insert_id($conn);
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role'] = $role;  // Set role in session
            
            header("Location: index.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign Up - Hair Booking</title>
    <style>
        body { 
            font-family: 'Times New Roman', Times, serif; 
            background: #f8f5f0; 
        }
        .form-container{ 
            max-width: 400px; 
            margin: 50px auto; 
            background: white; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 0 15px rgba(139,69,19,0.2); 
        }
        h2{ 
            color: #8B4513; 
            text-align: center; 
        }
        input{ 
            width: 100%; 
            padding: 12px; 
            margin: 10px 0; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
        }
        button{ 
            background: #8B4513; 
            color: white; 
            padding: 12px; 
            width: 100%; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; }
        .var{ 
            color: red; 
            text-align: center; 
        }
        .login-link{ 
            text-align: center; 
            margin-top: 15px; 
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Create Account</h2>
        <?php if(isset($error)) echo "<div class='var'>$error</div>"; ?>
        
        <form method="POST" action="">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <input type="password" name="password" placeholder="Password (min 6 characters)" minlength="6" required>
            
            
            <button type="submit">Sign Up</button>
        </form>
        
        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
        <div style="text-align: center; margin-top: 15px;">
            <a href="index.php">← Back to Home</a>
        </div>
    </div>
</body>
</html>