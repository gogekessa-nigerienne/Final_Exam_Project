<?php
include 'includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to book an appointment.");
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get form data
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $customer_email = mysqli_real_escape_string($conn, $_POST['customer_email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $service = mysqli_real_escape_string($conn, $_POST['service']);
    $appointment_date = mysqli_real_escape_string($conn, $_POST['appointment_date']);
    $appointment_time = mysqli_real_escape_string($conn, $_POST['appointment_time']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);
    
    $user_id = $_SESSION['user_id'];
    
    // Insert into database
    $sql = "INSERT INTO appointments (user_id, customer_name, customer_email, phone, service, appointment_date, appointment_time, notes) 
            VALUES ('$user_id', '$customer_name', '$customer_email', '$phone', '$service', '$appointment_date', '$appointment_time', '$notes')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: book.php?success=1");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>