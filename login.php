<?php
include 'includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['user_role'] = $user['role'];
            
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "No account found with this email!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Hair Booking</title>
    <style>
        body{ 
            font-family: Arial; 
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
            cursor: pointer; 
        }
        .error{ 
            color: red; 
            text-align: center; 
        }
        .register-link{ 
            text-align: center;
            margin-top: 15px; 
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Login to Your Account</h2>
        <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
        
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            
            <button type="submit">Login</button>
        </form>
        
        <div class="register-link">
            Don't have an account? <a href="signup.php">Sign up here</a>
        </div>
        <div style="text-align: center; margin-top: 15px;">
            <a href="index.php">← Back to Home</a>
        </div>
    </div>
</body>
</html>