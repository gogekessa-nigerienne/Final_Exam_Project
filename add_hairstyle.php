<?php
include 'includes/config.php';
// Check if admin - you'll need to implement admin check

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $image_url = $_POST['image_url']; // In real app, handle file upload
    $estimated_time = $_POST['estimated_time'];
    $difficulty = $_POST['difficulty'];
    
    $sql = "INSERT INTO hairstyles (name, description, category, image_url, estimated_time, difficulty) 
            VALUES ('$name', '$description', '$category', '$image_url', '$estimated_time', '$difficulty')";
    
    if(mysqli_query($conn, $sql)) {
        echo "Hairstyle added successfully!";
    }
}
?>

<form method="POST" style="max-width: 500px; margin: 50px auto;">
    <h2>Add New Hairstyle</h2>
    <input type="text" name="name" placeholder="Style Name" required><br><br>
    <textarea name="description" placeholder="Description" required></textarea><br><br>
    <select name="category" required>
        <option value="braids">Braids</option>
        <option value="natural_hair">Natural Hair</option>
        <option value="wigs">Wigs</option>
    </select><br><br>
    <input type="text" name="image_url" placeholder="Image URL/Filename" required><br><br>
    <input type="text" name="estimated_time" placeholder="Estimated Time (e.g., 3-4 hours)" required><br><br>
    <select name="difficulty">
        <option value="Easy">Easy</option>
        <option value="Medium">Medium</option>
        <option value="Hard">Hard</option>
    </select><br><br>
    <button type="submit">Add Hairstyle</button>
</form>