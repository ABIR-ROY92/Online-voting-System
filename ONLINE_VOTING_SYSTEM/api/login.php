<?php
session_start();
include("connect.php");

$mobile = $_POST['mobile'];
$password = $_POST['password'];
$role = $_POST['role'];

// Fetch user by mobile & role only
$check = mysqli_query($connect, "SELECT * FROM people WHERE mobile='$mobile' AND role='$role' LIMIT 1");

if(mysqli_num_rows($check) > 0){
    $user = mysqli_fetch_assoc($check);

    // Verify password
    if(password_verify($password, $user['password']) || $user['password'] == $password){
        // Save user data to session
        $_SESSION['userdata'] = $user;

        // ✅ If user is Admin (role 3), skip approval check
        if($role == 3){
            header("Location: ../routes/admin_dashboard.php");
            exit();
        }

        // ✅ For Voter (1) or Group (2)
        if($user['approved'] == 0){
            echo '<script>alert("Waiting for admin approval!"); window.location="../";</script>';
            exit();
        }

        // Load all groups for dashboard
        $groups = mysqli_query($connect, "SELECT * FROM people WHERE role=2");
        $_SESSION['groupsdata'] = mysqli_fetch_all($groups, MYSQLI_ASSOC);

        header("Location: ../routes/dashboard.php");
        exit();

    } else {
        echo '<script>alert("Invalid password!"); window.location="../";</script>';
        exit();
    }

} else {
    echo '<script>alert("User not found!"); window.location="../";</script>';
    exit();
}
?>
