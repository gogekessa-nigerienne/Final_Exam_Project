<?php
include 'includes/config.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if user is admin
$user_id = $_SESSION['user_id'];
$result = mysqli_query($conn, "SELECT role FROM users WHERE id = $user_id");

if($result && $row = mysqli_fetch_assoc($result)) {
    if($row['role'] != 'admin') {
        header("Location: index.php");
        exit();
    }
}

// Initialize message variables
$stylist_success = $stylist_error = '';
$hairstyle_success = $hairstyle_error = '';
$admin_success = $admin_error = '';
$appointment_success = $appointment_error = '';
$delete_success = $delete_error = '';

// ========== APPOINTMENT MANAGEMENT ==========
// Handle appointment status update
if(isset($_POST['update_appointment_status'])) {
    $appointment_id = intval($_POST['appointment_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    
    if(!empty($appointment_id) && !empty($new_status)) {
        $sql = "UPDATE appointments SET status='$new_status' WHERE id='$appointment_id'";
        if(mysqli_query($conn, $sql)) {
            $appointment_success = "Appointment status updated to " . ucfirst($new_status) . "!";
        } else {
            $appointment_error = "Error: " . mysqli_error($conn);
        }
    } else {
        $appointment_error = "Appointment ID and status are required!";
    }
}

// Handle appointment deletion
if(isset($_GET['action']) && isset($_GET['type']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $type = $_GET['type'];
    $id = intval($_GET['id']);
    
    if($action == 'delete') {
        if($type == 'appointment') {
            $sql = "DELETE FROM appointments WHERE id = $id";
            if(mysqli_query($conn, $sql)) {
                $delete_success = "Appointment deleted successfully!";
            } else {
                $delete_error = "Error deleting appointment: " . mysqli_error($conn);
            }
        } elseif($type == 'stylist') {
            $sql = "DELETE FROM stylists WHERE id = $id";
            if(mysqli_query($conn, $sql)) {
                $delete_success = "Stylist deleted successfully!";
            } else {
                $delete_error = "Error deleting stylist: " . mysqli_error($conn);
            }
        } elseif($type == 'hairstyle') {
            $sql = "DELETE FROM hairstyles WHERE id = $id";
            if(mysqli_query($conn, $sql)) {
                $delete_success = "Hairstyle deleted successfully!";
            } else {
                $delete_error = "Error deleting hairstyle: " . mysqli_error($conn);
            }
        }
    }
}

// ========== STATISTICS ==========
$today_appointments = 0;
$pending_approvals = 0;
$this_week = 0;

// Query for today's appointments
$today_query = "SELECT COUNT(*) as count FROM appointments WHERE DATE(appointment_date) = CURDATE()";
$today_result = mysqli_query($conn, $today_query);
if ($today_result) {
    $today_row = mysqli_fetch_assoc($today_result);
    $today_appointments = $today_row['count'];
}

// Query for pending approvals
$pending_query = "SELECT COUNT(*) as count FROM appointments WHERE status = 'pending'";
$pending_result = mysqli_query($conn, $pending_query);
if ($pending_result) {
    $pending_row = mysqli_fetch_assoc($pending_result);
    $pending_approvals = $pending_row['count'];
}

// Query for this week's appointments
$week_query = "SELECT COUNT(*) as count FROM appointments WHERE YEARWEEK(appointment_date) = YEARWEEK(CURDATE())";
$week_result = mysqli_query($conn, $week_query);
if ($week_result) {
    $week_row = mysqli_fetch_assoc($week_result);
    $this_week = $week_row['count'];
}

// ========== FORM HANDLING ==========
if($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // ADD/UPDATE STYLIST
    if(isset($_POST['add_stylist'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $specialty = mysqli_real_escape_string($conn, $_POST['specialty']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $photo = mysqli_real_escape_string($conn, $_POST['photo']);
        
        if(!empty($name)) {
            $sql = "INSERT INTO stylists (name, specialty, email, phone, photo) 
                    VALUES ('$name', '$specialty', '$email', '$phone', '$photo')";
            
            if(mysqli_query($conn, $sql)) {
                $stylist_success = "Stylist added successfully!";
            } else {
                $stylist_error = "Error: " . mysqli_error($conn);
            }
        } else {
            $stylist_error = "Name is required!";
        }
    }
    
    // UPDATE STYLIST
    if(isset($_POST['update_stylist'])) {
        $stylist_id = intval($_POST['stylist_id']);
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $specialty = mysqli_real_escape_string($conn, $_POST['specialty']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $photo = mysqli_real_escape_string($conn, $_POST['photo']);
        
        if(!empty($name) && !empty($stylist_id)) {
            $sql = "UPDATE stylists SET 
                    name = '$name', 
                    specialty = '$specialty', 
                    email = '$email', 
                    phone = '$phone', 
                    photo = '$photo' 
                    WHERE id = $stylist_id";
            
            if(mysqli_query($conn, $sql)) {
                $stylist_success = "Stylist updated successfully!";
            } else {
                $stylist_error = "Error: " . mysqli_error($conn);
            }
        } else {
            $stylist_error = "Name and ID are required!";
        }
    }
    
    // ADD HAIRSTYLE
    if(isset($_POST['add_hairstyle'])) {
        $hairstyle_name = mysqli_real_escape_string($conn, $_POST['hairstyle_name']);
        $category = mysqli_real_escape_string($conn, $_POST['category']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $image_url = mysqli_real_escape_string($conn, $_POST['image_url']);
        $estimated_time = mysqli_real_escape_string($conn, $_POST['estimated_time']);
        
        if(!empty($hairstyle_name) && !empty($category) && !empty($image_url)) {
            $sql = "INSERT INTO hairstyles (name, category, gender, image_url, estimated_time) 
                    VALUES ('$hairstyle_name', '$category', '$gender', '$image_url', '$estimated_time')";
            
            if(mysqli_query($conn, $sql)) {
                $hairstyle_success = "Hairstyle added successfully!";
            } else {
                $hairstyle_error = "Error: " . mysqli_error($conn);
            }
        } else {
            $hairstyle_error = "Name, category, and image URL are required!";
        }
    }
    
    // UPDATE HAIRSTYLE
    if(isset($_POST['update_hairstyle'])) {
        $hairstyle_id = intval($_POST['hairstyle_id']);
        $hairstyle_name = mysqli_real_escape_string($conn, $_POST['hairstyle_name']);
        $category = mysqli_real_escape_string($conn, $_POST['category']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $image_url = mysqli_real_escape_string($conn, $_POST['image_url']);
        $estimated_time = mysqli_real_escape_string($conn, $_POST['estimated_time']);
        
        if(!empty($hairstyle_name) && !empty($hairstyle_id)) {
            $sql = "UPDATE hairstyles SET 
                    name = '$hairstyle_name', 
                    category = '$category', 
                    gender = '$gender', 
                    image_url = '$image_url', 
                    estimated_time = '$estimated_time' 
                    WHERE id = $hairstyle_id";
            
            if(mysqli_query($conn, $sql)) {
                $hairstyle_success = "Hairstyle updated successfully!";
            } else {
                $hairstyle_error = "Error: " . mysqli_error($conn);
            }
        } else {
            $hairstyle_error = "Name and ID are required!";
        }
    }
    
    // MAKE ADMIN
    if(isset($_POST['make_admin'])) {
        $user_email = mysqli_real_escape_string($conn, $_POST['user_email']);
        $admin_password = $_POST['admin_password'];
        
        // Verify admin password
        $admin_check = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
        if($admin_row = mysqli_fetch_assoc($admin_check)) {
            if(password_verify($admin_password, $admin_row['password'])) {
                // Update user role
                $update_sql = "UPDATE users SET role = 'admin' WHERE email = '$user_email'";
                if(mysqli_query($conn, $update_sql)) {
                    $admin_success = "User $user_email is now an admin!";
                } else {
                    $admin_error = "Error updating user: " . mysqli_error($conn);
                }
            } else {
                $admin_error = "Incorrect admin password!";
            }
        } else {
            $admin_error = "Admin not found!";
        }
    }
}

// ========== FETCH DATA FOR DISPLAY ==========
// Fetch appointments with filters
$appointment_filter = "";
if(isset($_GET['appointment_status']) && !empty($_GET['appointment_status'])) {
    $status = mysqli_real_escape_string($conn, $_GET['appointment_status']);
    $appointment_filter = " WHERE a.status = '$status'";
}

if(isset($_GET['appointment_date']) && !empty($_GET['appointment_date'])) {
    $date = mysqli_real_escape_string($conn, $_GET['appointment_date']);
    $appointment_filter .= ($appointment_filter ? " AND" : " WHERE") . " DATE(a.appointment_date) = '$date'";
}

$appointments_sql = "SELECT a.*, u.name as customer_name 
                     FROM appointments a 
                     LEFT JOIN users u ON a.user_id = u.id 
                     $appointment_filter
                     ORDER BY a.appointment_date DESC, a.appointment_time DESC 
                     LIMIT 20";
$appointments_result = mysqli_query($conn, $appointments_sql);

// Fetch stylists and hairstyles
$stylists = mysqli_query($conn, "SELECT * FROM stylists ORDER BY id DESC");
$hairstyles = mysqli_query($conn, "SELECT * FROM hairstyles ORDER BY id DESC");

// Check if we're in edit mode
$edit_mode = false;
$edit_type = '';
$edit_data = [];
if(isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['type']) && isset($_GET['id'])) {
    $edit_mode = true;
    $edit_type = $_GET['type'];
    $edit_id = intval($_GET['id']);
    
    if($edit_type == 'stylist') {
        $edit_result = mysqli_query($conn, "SELECT * FROM stylists WHERE id = $edit_id");
        if($edit_result && mysqli_num_rows($edit_result) > 0) {
            $edit_data = mysqli_fetch_assoc($edit_result);
        } else {
            $edit_mode = false;
        }
    } elseif($edit_type == 'hairstyle') {
        $edit_result = mysqli_query($conn, "SELECT * FROM hairstyles WHERE id = $edit_id");
        if($edit_result && mysqli_num_rows($edit_result) > 0) {
            $edit_data = mysqli_fetch_assoc($edit_result);
        } else {
            $edit_mode = false;
        }
    }
}

// Get all users for admin management
$users_result = mysqli_query($conn, "SELECT * FROM users ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - SaloonReserve</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Keep all your existing CSS styles here - they are fine */
        .tab-navigation {
            display: flex;
            background: #f5f5f5;
            border-radius: 10px 10px 0 0;
            overflow: hidden;
            margin-bottom: 0;
        }
        
        .tab-btn {
            padding: 15px 30px;
            background: none;
            border: none;
            cursor: pointer;
            font-weight: bold;
            color: #666;
            flex: 1;
            text-align: center;
            transition: all 0.3s;
        }
        
        .tab-btn:hover {
            background: #e8e8e8;
        }
        
        .tab-btn.active {
            background: white;
            color: #8B4513;
            border-bottom: 3px solid #8B4513;
        }
        
        .tab-content {
            display: none;
            background: white;
            padding: 30px;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-top: 0;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .filter-section {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .filter-form {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .filter-form select, .filter-form input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .filter-btn {
            background: #8B4513;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }
        
        .status-pending {
            background: #FFF3CD;
            color: #856404;
        }
        
        .status-confirmed {
            background: #D4EDDA;
            color: #155724;
        }
        
        .status-completed {
            background: #D1ECF1;
            color: #0C5460;
        }
        
        .status-cancelled {
            background: #F8D7DA;
            color: #721C24;
        }
        
        .appointment-actions {
            display: flex;
            gap: 5px;
        }
        
        .action-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
        }
        
        .status-select {
            background: #17A2B8;
            color: white;
        }
        
        .delete-btn {
            background: #DC3545;
            color: white;
        }
        
        .quick-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .quick-stat-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            border-top: 4px solid #8B4513;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .quick-stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #8B4513;
        }
        
        .quick-stat-label {
            color: #666;
            font-size: 0.9em;
        }
        
        .admin-container {
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
        
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }
        
        @media (max-width: 768px) {
            .dashboard-stats {
                grid-template-columns: 1fr;
            }
            .quick-stats {
                grid-template-columns: 1fr;
            }
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-number {
            font-size: 3em;
            font-weight: bold;
            color: #8B4513;
            margin: 10px 0;
        }
        
        .stat-label {
            color: #666;
            font-size: 1.1em;
            margin-top: 5px;
        }
        
        .stat-icon {
            font-size: 2.5em;
            margin-bottom: 15px;
        }
        
        .admin-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 30px;
        }
        
        @media (max-width: 768px) {
            .admin-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .admin-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .admin-card h2 {
            color: #8B4513;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid #E8D9C5;
        }
        
        .admin-form .form-group {
            margin-bottom: 20px;
        }
        
        .admin-form label {
            display: block;
            margin-bottom: 8px;
            color: #8B4513;
            font-weight: bold;
        }
        
        .admin-form input,
        .admin-form select,
        .admin-form textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        .admin-form input:focus,
        .admin-form select:focus,
        .admin-form textarea:focus {
            border-color: #8B4513;
            outline: none;
        }
        
        .admin-btn {
            background: #8B4513;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
            font-size: 16px;
            margin-top: 10px;
        }
        
        .admin-btn:hover {
            background: #A0522D;
        }
        
        .message {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .admin-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }
        
        .admin-link {
            background: #8B4513;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        
        .admin-link:hover {
            background: #A0522D;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #8B4513;
            color: white;
        }

        table tr:hover {
            background-color: #f5f5f5;
        }

        .action-buttons a {
            display: inline-block;
            padding: 5px 10px;
            margin: 2px;
            border-radius: 3px;
            text-decoration: none;
            font-size: 14px;
        }

        .edit-btn {
            background: #4CAF50;
            color: white;
        }

        .delete-btn {
            background: #f44336;
            color: white;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container admin-container">
        <div class="page-header">
            <h1 class="page-title">Admin Panel</h1>
            <p class="page-subtitle">Admin Management Dashboard</p>
        </div>
        
        <!-- Success/Error Messages -->
        <?php if(isset($appointment_success) && !empty($appointment_success)): ?>
            <div class="message success"><?php echo $appointment_success; ?></div>
        <?php elseif(isset($appointment_error) && !empty($appointment_error)): ?>
            <div class="message error"><?php echo $appointment_error; ?></div>
        <?php endif; ?>
        
        <?php if(isset($delete_success) && !empty($delete_success)): ?>
            <div class="message success"><?php echo $delete_success; ?></div>
        <?php elseif(isset($delete_error) && !empty($delete_error)): ?>
            <div class="message error"><?php echo $delete_error; ?></div>
        <?php endif; ?>
        
        <?php if(isset($stylist_success) && !empty($stylist_success)): ?>
            <div class="message success"><?php echo $stylist_success; ?></div>
        <?php elseif(isset($stylist_error) && !empty($stylist_error)): ?>
            <div class="message error"><?php echo $stylist_error; ?></div>
        <?php endif; ?>
        
        <?php if(isset($hairstyle_success) && !empty($hairstyle_success)): ?>
            <div class="message success"><?php echo $hairstyle_success; ?></div>
        <?php elseif(isset($hairstyle_error) && !empty($hairstyle_error)): ?>
            <div class="message error"><?php echo $hairstyle_error; ?></div>
        <?php endif; ?>
        
        <?php if(isset($admin_success) && !empty($admin_success)): ?>
            <div class="message success"><?php echo $admin_success; ?></div>
        <?php elseif(isset($admin_error) && !empty($admin_error)): ?>
            <div class="message error"><?php echo $admin_error; ?></div>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <!-- Dashboard Statistics -->
            <div class="dashboard-stats">
                <div class="stat-card">
                    <div class="stat-icon">📅</div>
                    <div class="stat-number"><?php echo $today_appointments; ?></div>
                    <div class="stat-label">Today's Appointments</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">⏳</div>
                    <div class="stat-number"><?php echo $pending_approvals; ?></div>
                    <div class="stat-label">Pending Approvals</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">📊</div>
                    <div class="stat-number"><?php echo $this_week; ?></div>
                    <div class="stat-label">This Week's Appointments</div>
                </div>
            </div>
            
            <!-- Tab Navigation -->
            <div class="tab-navigation">
                <button class="tab-btn active" onclick="showTab('appointments')"> Appointments record</button>
                <button class="tab-btn" onclick="showTab('stylists')"> Stylists record</button>
                <button class="tab-btn" onclick="showTab('hairstyles')"> Hairstyles record</button>
                <button class="tab-btn" onclick="showTab('users')"> Users</button>
            </div>
            
            <!-- APPOINTMENTS TAB -->
            <div id="appointments-tab" class="tab-content active">
                <h2>Appointment Management</h2>
                
                <!-- Quick Stats -->
                <div class="quick-stats">
                    <div class="quick-stat-card">
                        <div class="quick-stat-number"><?php echo $today_appointments; ?></div>
                        <div class="quick-stat-label">Today</div>
                    </div>
                    <div class="quick-stat-card">
                        <div class="quick-stat-number"><?php echo $pending_approvals; ?></div>
                        <div class="quick-stat-label">Pending</div>
                    </div>
                    <div class="quick-stat-card">
                        <div class="quick-stat-number"><?php echo $this_week; ?></div>
                        <div class="quick-stat-label">This Week</div>
                    </div>
                </div>
                
                <!-- Filter Section -->
                <div class="filter-section">
                    <form method="GET" action="" class="filter-form">
                        <input type="hidden" name="page" value="admin">
                        <select name="appointment_status">
                            <option value="">All Statuses</option>
                            <option value="pending" <?php echo isset($_GET['appointment_status']) && $_GET['appointment_status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="confirmed" <?php echo isset($_GET['appointment_status']) && $_GET['appointment_status'] == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                            <option value="completed" <?php echo isset($_GET['appointment_status']) && $_GET['appointment_status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                            <option value="cancelled" <?php echo isset($_GET['appointment_status']) && $_GET['appointment_status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                        
                        <input type="date" name="appointment_date" value="<?php echo isset($_GET['appointment_date']) ? $_GET['appointment_date'] : ''; ?>">
                        
                        <button type="submit" class="filter-btn">Filter</button>
                        
                        <?php if(isset($_GET['appointment_status']) || isset($_GET['appointment_date'])): ?>
                            <a href="admin.php" class="filter-btn" style="background: #666; text-decoration: none; color: white; padding: 8px 15px; border-radius: 5px;">Clear Filters</a>
                        <?php endif; ?>
                    </form>
                </div>
                
                <!-- Appointments Table -->
                <?php if(mysqli_num_rows($appointments_result) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Date & Time</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($appointment = mysqli_fetch_assoc($appointments_result)): 
                            $date = date('M j, Y', strtotime($appointment['appointment_date']));
                            $time = date('g:i A', strtotime($appointment['appointment_time']));
                        ?>
                        <tr>
                            <td><?php echo $appointment['id']; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($appointment['customer_name']); ?></strong><br>
                                <small><?php echo htmlspecialchars($appointment['customer_email']); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($appointment['service']); ?></td>
                            <td>
                                <?php echo $date; ?><br>
                                <small><?php echo $time; ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($appointment['phone']); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $appointment['status']; ?>">
                                    <?php echo ucfirst($appointment['status']); ?>
                                </span>
                            </td>
                            <td>
                                <div class="appointment-actions">
                                    <form method="POST" action="" style="display: inline;">
                                        <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                                        <select name="status" onchange="this.form.submit()" style="padding: 5px; font-size: 12px; border-radius: 3px; border: 1px solid #ddd;">
                                            <option value="">Change</option>
                                            <option value="pending" <?php echo $appointment['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="confirmed" <?php echo $appointment['status'] == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                            <option value="completed" <?php echo $appointment['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                            <option value="cancelled" <?php echo $appointment['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                        <input type="hidden" name="update_appointment_status" value="1">
                                    </form>
                                    
                                    <a href="?action=delete&type=appointment&id=<?php echo $appointment['id']; ?>" 
                                       onclick="return confirm('Are you sure you want to delete this appointment?')"
                                       class="action-btn delete-btn">
                                        Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <div style="text-align: center; padding: 40px; color: #666;">
                    <h3>No appointments found</h3>
                    <p><?php echo (isset($_GET['appointment_status']) || isset($_GET['appointment_date'])) ? 
                        'Try changing your filter criteria.' : 'No appointments have been booked yet.'; ?></p>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- STYLISTS TAB -->
            <div id="stylists-tab" class="tab-content">
                <h2>Stylist Management</h2>
                
                <?php if(isset($stylist_success)): ?>
                    <div class="message success"><?php echo $stylist_success; ?></div>
                <?php elseif(isset($stylist_error)): ?>
                    <div class="message error"><?php echo $stylist_error; ?></div>
                <?php endif; ?>
                
                <!-- Stylist Form and Table -->
                <div class="admin-grid">
                    <div class="admin-card">
                        <h3><?php echo $edit_mode && $edit_type == 'stylist' ? 'Edit Stylist' : 'Add New Stylist'; ?></h3>
                        <form method="POST" action="" class="admin-form" enctype="multipart/form-data">
                            <?php if($edit_mode && $edit_type == 'stylist'): ?>
                                <input type="hidden" name="stylist_id" value="<?php echo $edit_data['id']; ?>">
                                <button type="submit" name="update_stylist" class="admin-btn">Update Stylist</button>
                            <?php else: ?>
                                <button type="submit" name="add_stylist" class="admin-btn">Add Stylist</button>
                            <?php endif; ?>
                            
                            <!-- Stylist form fields -->
                            <div class="form-group">
                                <label for="name">Full Name *</label>
                                <input type="text" id="name" name="name" required 
                                       value="<?php echo $edit_mode && $edit_type == 'stylist' ? htmlspecialchars($edit_data['name']) : ''; ?>"
                                       placeholder="Samy Joe">
                            </div>
                            
                            <div class="form-group">
                                <label for="specialty">Specialty</label>
                                <input type="text" id="specialty" name="specialty"
                                       value="<?php echo $edit_mode && $edit_type == 'stylist' ? htmlspecialchars($edit_data['specialty']) : ''; ?>"
                                       placeholder="Natural Hair, Wigs, Braids">
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email"
                                       value="<?php echo $edit_mode && $edit_type == 'stylist' ? htmlspecialchars($edit_data['email']) : ''; ?>"
                                       placeholder="stylist@email.com">
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone"
                                       value="<?php echo $edit_mode && $edit_type == 'stylist' ? htmlspecialchars($edit_data['phone']) : ''; ?>"
                                       placeholder="+1234567890">
                            </div>
                            
                            <div class="form-group">
                                <label for="photo">Photo Filename</label>
                                <input type="text" id="photo" name="photo"
                                       value="<?php echo $edit_mode && $edit_type == 'stylist' ? htmlspecialchars($edit_data['photo']) : ''; ?>"
                                       placeholder="stylist1.jpg">
                                <small style="color: #666;">Upload image to images/ folder first</small>
                            </div>
                        </form>
                    </div>
                    
                    <div class="admin-card">
                        <h3>Current Stylists</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Specialty</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // Reset pointer for stylists
                                mysqli_data_seek($stylists, 0);
                                while($stylist = mysqli_fetch_assoc($stylists)): 
                                ?>
                                <tr>
                                    <td><?php echo $stylist['id']; ?></td>
                                    <td><?php echo htmlspecialchars($stylist['name']); ?></td>
                                    <td><?php echo htmlspecialchars($stylist['specialty']); ?></td>
                                    <td><?php echo htmlspecialchars($stylist['email']); ?></td>
                                    <td>
                                        <a href="?action=edit&type=stylist&id=<?php echo $stylist['id']; ?>" 
                                           class="edit-btn">Edit</a>
                                        <a href="?action=delete&type=stylist&id=<?php echo $stylist['id']; ?>" 
                                           onclick="return confirm('Are you sure you want to delete this stylist?')"
                                           class="delete-btn">Delete</a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- HAIRSTYLES TAB -->
            <div id="hairstyles-tab" class="tab-content">
                <h2>Hairstyle Management</h2>
                
                <?php if(isset($hairstyle_success)): ?>
                    <div class="message success"><?php echo $hairstyle_success; ?></div>
                <?php elseif(isset($hairstyle_error)): ?>
                    <div class="message error"><?php echo $hairstyle_error; ?></div>
                <?php endif; ?>
                
                <!-- Hairstyle Form and Table -->
                <div class="admin-grid">
                    <div class="admin-card">
                        <h3><?php echo $edit_mode && $edit_type == 'hairstyle' ? 'Edit Hairstyle' : 'Add New Hairstyle'; ?></h3>
                        <form method="POST" action="" class="admin-form" enctype="multipart/form-data">
                            <?php if($edit_mode && $edit_type == 'hairstyle'): ?>
                                <input type="hidden" name="hairstyle_id" value="<?php echo $edit_data['id']; ?>">
                                <button type="submit" name="update_hairstyle" class="admin-btn">Update Hairstyle</button>
                            <?php else: ?>
                                <button type="submit" name="add_hairstyle" class="admin-btn">Add Hairstyle</button>
                            <?php endif; ?>
                            
                            <!-- Hairstyle form fields -->
                            <div class="form-group">
                                <label for="hairstyle_name">Style Name *</label>
                                <input type="text" id="hairstyle_name" name="hairstyle_name" required 
                                       value="<?php echo $edit_mode && $edit_type == 'hairstyle' ? htmlspecialchars($edit_data['name']) : ''; ?>"
                                       placeholder="Box Braids">
                            </div>
                            
                            <div class="form-group">
                                <label for="category">Category *</label>
                                <select id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="braids" <?php echo ($edit_mode && $edit_type == 'hairstyle' && $edit_data['category'] == 'braids') ? 'selected' : ''; ?>>Braids</option>
                                    <option value="natural" <?php echo ($edit_mode && $edit_type == 'hairstyle' && $edit_data['category'] == 'natural') ? 'selected' : ''; ?>>Natural Hair</option>
                                    <option value="wigs" <?php echo ($edit_mode && $edit_type == 'hairstyle' && $edit_data['category'] == 'wigs') ? 'selected' : ''; ?>>Wigs & Weaves</option>
                                    <option value="mens" <?php echo ($edit_mode && $edit_type == 'hairstyle' && $edit_data['category'] == 'mens') ? 'selected' : ''; ?>>Men's Styles</option>
                                    <option value="locs" <?php echo ($edit_mode && $edit_type == 'hairstyle' && $edit_data['category'] == 'locs') ? 'selected' : ''; ?>>Locs</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select id="gender" name="gender">
                                    <option value="unisex" <?php echo ($edit_mode && $edit_type == 'hairstyle' && $edit_data['gender'] == 'unisex') ? 'selected' : ''; ?>>Unisex (All)</option>
                                    <option value="men" <?php echo ($edit_mode && $edit_type == 'hairstyle' && $edit_data['gender'] == 'men') ? 'selected' : ''; ?>>Men</option>
                                    <option value="women" <?php echo ($edit_mode && $edit_type == 'hairstyle' && $edit_data['gender'] == 'women') ? 'selected' : ''; ?>>Women</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="image_url">Image Filename *</label>
                                <input type="text" id="image_url" name="image_url" required 
                                       value="<?php echo $edit_mode && $edit_type == 'hairstyle' ? htmlspecialchars($edit_data['image_url']) : ''; ?>"
                                       placeholder="box_braids.jpg">
                                <small style="color: #666;">Upload image to images/ folder first</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="estimated_time">Estimated Time</label>
                                <input type="text" id="estimated_time" name="estimated_time"
                                       value="<?php echo $edit_mode && $edit_type == 'hairstyle' ? htmlspecialchars($edit_data['estimated_time']) : ''; ?>"
                                       placeholder="3-4 hours">
                            </div>
                        </form>
                    </div>
                    
                    <div class="admin-card">
                        <h3>Current Hairstyles</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Gender</th>
                                    <th>Image</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // Reset pointer for hairstyles
                                mysqli_data_seek($hairstyles, 0);
                                while($hairstyle = mysqli_fetch_assoc($hairstyles)): 
                                ?>
                                <tr>
                                    <td><?php echo $hairstyle['id']; ?></td>
                                    <td><?php echo htmlspecialchars($hairstyle['name']); ?></td>
                                    <td><?php echo htmlspecialchars($hairstyle['category']); ?></td>
                                    <td><?php echo htmlspecialchars($hairstyle['gender']); ?></td>
                                    <td><?php echo htmlspecialchars($hairstyle['image_url']); ?></td>
                                    <td>
                                        <a href="?action=edit&type=hairstyle&id=<?php echo $hairstyle['id']; ?>" 
                                           class="edit-btn">Edit</a>
                                        <a href="?action=delete&type=hairstyle&id=<?php echo $hairstyle['id']; ?>" 
                                           onclick="return confirm('Are you sure you want to delete this hairstyle?')"
                                           class="delete-btn">Delete</a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- USERS TAB -->
            <div id="users-tab" class="tab-content">
                <h2>User Management</h2>
                
                <!-- Make Admin Form -->
                <div class="admin-card" style="margin-bottom: 30px;">
                    <h3>Make User Admin</h3>
                    <form method="POST" action="" class="admin-form">
                        <div class="form-group">
                            <label for="user_email">User Email to Make Admin</label>
                            <input type="email" id="user_email" name="user_email" placeholder="user@example.com" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="admin_password">Your Admin Password</label>
                            <input type="password" id="admin_password" name="admin_password" placeholder="Your admin password" required>
                        </div>
                        
                        <button type="submit" name="make_admin" class="admin-btn">Make User Admin</button>
                    </form>
                </div>
                
                <!-- Users Table -->
                <div class="admin-card">
                    <h3>All Users</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($user = mysqli_fetch_assoc($users_result)): 
                                $joined_date = date('M j, Y', strtotime($user['created_at']));
                            ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo htmlspecialchars($user['name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['phone']); ?></td>
                                <td>
                                    <span style="background: <?php echo $user['role'] == 'admin' ? '#8B4513' : '#666'; ?>; 
                                          color: white; padding: 5px 10px; border-radius: 20px; font-size: 12px;">
                                        <?php echo ucfirst($user['role']); ?>
                                    </span>
                                </td>
                                <td><?php echo $joined_date; ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Navigation Links -->
            <div class="admin-links">
                <a href="index.php" class="admin-link">← Back to Home</a>
                <a href="gallery.php" class="admin-link">View Gallery</a>
                <a href="stylists.php" class="admin-link">View Stylists</a>
                <a href="book.php" class="admin-link">Book Appointment</a>
            </div>
            
        <?php else: ?>
            <div style="text-align: center; padding: 50px; background: white; border-radius: 10px;">
                <h2 style="color: #8B4513;">Access Denied</h2>
                <p>You must be logged in to access the admin panel.</p>
                <a href="login.php" class="btn btn-primary" style="margin-top: 20px;">Login</a>
            </div>
        <?php endif; ?>
    </div>
    
    <?php include 'includes/footer.php'; ?>
    
    <script>
        // Tab switching function
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Remove active class from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tabName + '-tab').classList.add('active');
            
            // Add active class to clicked button
            event.target.classList.add('active');
        }
        
        // Auto-show the correct tab based on messages
        document.addEventListener('DOMContentLoaded', function() {
            <?php if(isset($appointment_success) || isset($appointment_error)): ?>
                showTab('appointments');
            <?php elseif(isset($stylist_success) || isset($stylist_error) || (isset($_GET['type']) && $_GET['type'] == 'stylist')): ?>
                showTab('stylists');
            <?php elseif(isset($hairstyle_success) || isset($hairstyle_error) || (isset($_GET['type']) && $_GET['type'] == 'hairstyle')): ?>
                showTab('hairstyles');
            <?php elseif(isset($_GET['type']) && $_GET['type'] == 'appointment'): ?>
                showTab('appointments');
            <?php endif; ?>
        });
        
        // Confirmation for delete actions
        function confirmDelete(type, id, name) {
            return confirm(`Are you sure you want to delete this ${type}?`);
        }
    </script>
</body>
</html>