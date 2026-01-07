<?php
session_start();
include("../api/connect.php");

// ✅ Only admin can open this page
if (!isset($_SESSION['userdata']) || $_SESSION['userdata']['role'] != 3) {
    header("Location: ../index.html");
    exit();
}

// ✅ Get all users who are waiting for approval (not admin)
$pending = mysqli_query($connect, "SELECT * FROM people WHERE approved = 0 AND role != 3");
?>

<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../css/stylesheet.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f6fc;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        a {
            text-decoration: none;
            color: #48dbfb;
            font-weight: bold;
        }
        div.user-box {
            border: 1px solid #ccc;
            background: white;
            padding: 15px;
            margin: 15px auto;
            width: 50%;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        button {
            padding: 7px 15px;
            background-color: #48dbfb;
            color: white;
            border: none;
            border-radius: 7px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background-color: #34bce0;
        }
        img {
            border-radius: 8px;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>
    <h1>Admin Panel - Approve Users</h1>
    <center><a href="../index.html">Logout</a></center>
    <hr>

    <?php if (mysqli_num_rows($pending) > 0): ?>
        <?php while ($u = mysqli_fetch_assoc($pending)): ?>
            <div class="user-box">
                <img src="../uploads/<?php echo htmlspecialchars($u['photo']); ?>" height="60" style="float:right;">
                <b><?php echo htmlspecialchars($u['name']); ?></b><br>
                Mobile: <?php echo htmlspecialchars($u['mobile']); ?><br>
                Role: <?php echo ($u['role'] == 1 ? 'Voter' : 'Group'); ?><br>
                <form action="../api/admin_approve.php" method="post" style="margin-top:10px;">
                    <input type="hidden" name="id" value="<?php echo $u['id']; ?>">
                    <button type="submit">Approve</button>
                </form>
                <div style="clear:both;"></div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <center><p>No pending users.</p></center>
    <?php endif; ?>
</body>
</html>
