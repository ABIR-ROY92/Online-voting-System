<?php
session_start();
include("connect.php");

// Only admins should be able to approve users
if(!isset($_SESSION['userdata']) || $_SESSION['userdata']['role'] != 3){
    header("Location: ../index.html");
    exit();
}

if(isset($_POST['id'])){
    $id = $_POST['id'];

    // Update user approval status
    $update = mysqli_query($connect, "UPDATE people SET approved=1 WHERE id='$id'");

    if($update){
        echo '<script>alert("User approved successfully!"); window.location="../routes/admin_dashboard.php";</script>';
    } else {
        echo '<script>alert("Error approving user!"); window.location="../routes/admin_dashboard.php";</script>';
    }
} else {
    echo '<script>alert("Invalid request!"); window.location="../routes/admin_dashboard.php";</script>';
}
?>
