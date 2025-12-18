<?php
include 'includes/config.php';
if($_POST) {
    $sql = "INSERT INTO stylists (name, specialty, email, phone) 
            VALUES ('{$_POST['name']}', '{$_POST['specialty']}', 
                    '{$_POST['email']}', '{$_POST['phone']}')";
    mysqli_query($conn, $sql);
    header("Location: admin.php?success=1");
}
?>