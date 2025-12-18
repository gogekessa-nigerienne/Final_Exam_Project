<?php
include 'includes/config.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=book");
    exit();
}

// Get selected hairstyle from URL or session
$selectedStyleName = '';
if(isset($_GET['style'])) {
    $selectedStyleName = urldecode($_GET['style']);
    $_SESSION['selected_style'] = $selectedStyleName;
} elseif(isset($_SESSION['selected_style'])) {
    $selectedStyleName = $_SESSION['selected_style'];
}

// If form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $customer_name = $_SESSION['user_name'];
    $customer_email = $_SESSION['user_email'];
    $phone = $_POST['phone'];
    $service = $_POST['service'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $notes = $_POST['notes'];
    $hairstyle_name = $selectedStyleName;
    
    // Validate date is not in the past
    if (strtotime($appointment_date) < strtotime(date('Y-m-d'))) {
        $error = "Please select a future date for your appointment.";
    } else {
        $sql = "INSERT INTO appointments (user_id, customer_name, customer_email, phone, service, appointment_date, appointment_time, notes, hairstyle_name) 
                VALUES ('$user_id', '$customer_name', '$customer_email', '$phone', '$service', '$appointment_date', '$appointment_time', '$notes', '$hairstyle_name')";
        
        if(mysqli_query($conn, $sql)) {
            $success = " Booking successful! You will receive a confirmation email.";
            
            // Clear selected style
            unset($_SESSION['selected_style']);
        } else {
            $error = " Error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - SaloonReserve</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Book Your Appointment</h1>
            <p class="page-subtitle">Schedule your preferred hairstyle with our expert stylists</p>
        </div>
        
        <div class="form-container">
            <?php if(isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php elseif(isset($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if(!empty($selectedStyleName)): ?>
            <div style="background: #E8F4FD; padding: 15px; border-radius: 8px; margin-bottom: 25px; border-left: 4px solid #2196F3;">
                <strong> Selected Hairstyle:</strong> <?php echo htmlspecialchars($selectedStyleName); ?>
                <br>
                <small style="color: #666;">This style will be mentioned in your appointment details.</small>
                <a href="gallery.php" style="float: right; color: #2196F3; text-decoration: none;">Change Style</a>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="">
               <div class="form-group">
    <label>Your Full Name</label>
    <input type="text" value="<?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : ''; ?>" readonly>
</div>

<div class="form-group">
    <label>Your Email</label>
    <input type="email" value="<?php echo isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : ''; ?>" readonly>
</div>
                
                <div class="form-group">
                    <label>Your Phone Number *</label>
                    <input type="tel" name="phone" placeholder="Enter your phone number" required>
                </div>
                
                <div class="form-group">
                    <label>Service Type *</label>
                    <select name="service" required>
                        <option value=""> Choose a service type </option>
                        <option value="Box Braids">Box Braids (GHS 80)</option>
                        <option value="Cornrows">Cornrows (GHS 60)</option>
                        <option value="Knotless Braids">Knotless Braids (GHS 120)</option>
                        <option value="Wig Installation">Wig Installation (GHS 70)</option>
                        <option value="Buzz Cut">Buzz Cut (GHS 30)</option>
                        <option value="Crew Cut">Crew Cut (GHS 35)</option>
                        <option value="Man Bun">Man Bun (GHS 25)</option>
                        <option value="Natural Hair Treatment">Natural Hair Treatment (GHS 50)</option>
                        <option value="Other">Other (Specify in notes)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Appointment Date *</label>
                    <input type="date" name="appointment_date" required min="<?php echo date('Y-m-d'); ?>">
                </div>
                
                <div class="form-group">
                    <label>Appointment Time *</label>
                    <input type="time" name="appointment_time" required min="09:00" max="18:00">
                    <small style="color: #666; display: block; margin-top: 5px;">We're open 9:00 AM - 6:00 PM</small>
                </div>
                
                <div class="form-group">
                    <label>Special Requests or Notes</label>
                    <textarea name="notes" placeholder="Ask Anything"></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 18px;">
                    Confirm Booking
                </button>
            </form>
            
            <p style="text-align: center; margin-top: 20px; color: #666;">
                Need help? Call  at 0503641976
            </p>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>