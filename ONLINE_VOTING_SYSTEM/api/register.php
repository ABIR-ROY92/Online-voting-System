<?php
include("connect.php");

$name = $_POST['name'];
$mobile = $_POST['mobile'];
$password = $_POST['password'];
$cpassword = $_POST['cpassword'];
$address = $_POST['Address'];
$role = $_POST['role'];

// Handle image
$image = 'default.png';
$uploads_dir = $_SERVER['DOCUMENT_ROOT']."/uploads/"; // Absolute path to uploads folder

if(isset($_FILES['photo']['name']) && $_FILES['photo']['name'] != ''){
    // Replace spaces and unsafe characters for safe filenames
    $filename = time().'_'.preg_replace('/[^A-Za-z0-9_\-\.]/', '_', basename($_FILES['photo']['name']));
    
    // Create uploads folder if it doesn't exist
    if (!is_dir($uploads_dir)) mkdir($uploads_dir, 0755, true);
    
    // Move uploaded file, fallback to default if it fails
    if(move_uploaded_file($_FILES['photo']['tmp_name'], $uploads_dir.$filename)){
        $image = $filename;
    }
}

// Check password match
if($password !== $cpassword){
    echo '<script>alert("Password mismatch!"); window.location="../routes/register.html";</script>';
    exit();
}

// Hash password
$hashed_pass = password_hash($password, PASSWORD_DEFAULT);

// Insert into DB
$insert = mysqli_query($connect, "INSERT INTO people (name,mobile,address,password,photo,role,status,votes,approved,voted)
VALUES ('$name','$mobile','$address','$hashed_pass','$image','$role',0,0,0,0)");

if($insert){
    echo '<script>alert("Registration successful! Wait for admin approval."); window.location="../";</script>';
}else{
    echo '<script>alert("Registration failed!"); window.location="../routes/register.html";</script>';
}
?>
