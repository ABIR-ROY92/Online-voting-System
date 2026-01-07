<?php
session_start();
include('connect.php');

// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in
if(!isset($_SESSION['userdata'])) {
    echo '<script>
            alert("You must be logged in to vote!");
            window.location = "../routes/login.php";
          </script>';
    exit;
}

// Check if POST data exists
if(!isset($_POST['gid'])) {
    echo '<script>
            alert("Invalid request!");
            window.location = "../routes/dashboard.php";
          </script>';
    exit;
}

$gid = $_POST['gid']; // Group ID
$uid = $_SESSION['userdata']['id']; // User ID

// Check if user already voted
if($_SESSION['userdata']['status'] == 1) {
    echo '<script>
            alert("You have already voted!");
            window.location = "../routes/dashboard.php";
          </script>';
    exit;
}

// Increment vote safely
$update_votes = mysqli_query($connect, "UPDATE `people` SET votes = votes +1 WHERE id = '$gid'") or die(mysqli_error($connect));

// Update user status
$update_user_status = mysqli_query($connect, "UPDATE `people` SET status = 1 WHERE id = '$uid'") or die(mysqli_error($connect));

if($update_votes && $update_user_status) {
    // Fetch updated groups (role=2 assumed for groups)
    $groups = mysqli_query($connect, "SELECT id, name, votes, photo FROM `people` WHERE role = 2") or die(mysqli_error($connect));
    $groupsdata = mysqli_fetch_all($groups, MYSQLI_ASSOC);

    // Update session
    $_SESSION['userdata']['status'] = 1;
    $_SESSION['groupsdata'] = $groupsdata;

    echo '<script>
            alert("Voting Successful!");
            window.location = "../routes/dashboard.php";
          </script>';
} else {
    echo '<script>
            alert("Some error occurred!");
            window.location = "../routes/dashboard.php";
          </script>';
}
?>

