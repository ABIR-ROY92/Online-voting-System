<?php
session_start();
if(!isset($_SESSION['userdata'])){
    header("location:../");
    exit();
}

$userdata = $_SESSION['userdata'];
$groupsdata = $_SESSION['groupsdata'];

// Status
$status = ($userdata['status'] == 0) 
          ? '<b style="color:red;">Not Voted</b>' 
          : '<b style="color:green;">Voted</b>';

// Function to get image path, fallback to default
function getImage($filename){
    $path = "../uploads/" . $filename;
    if(!file_exists($path) || empty($filename)){
        return "../uploads/default.png"; // fallback if missing
    }
    return $path;
}
?>

<html>
<head>
    <title>E-Voting System</title>
    <link rel="stylesheet" href="../css/stylesheet.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f2f6fc; margin:0; padding:0; }
        #headerSection { padding: 10px 20px; background-color: #48dbfb; color:white; display:flex; justify-content:space-between; align-items:center; }
        #headerSection h1 { margin:0 auto; text-align:center; }
        #backbtn, #logoutbtn { padding:7px 15px; border-radius:7px; background-color:white; color:#48dbfb; border:none; cursor:pointer; font-weight:bold; }
        #backbtn:hover, #logoutbtn:hover { background-color:#e8f8ff; }
        #mainSection { display:flex; justify-content:space-between; align-items:flex-start; gap:20px; margin:30px; }
        #Profile, #Group { background-color:white; padding:20px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.2); text-align:left; height:auto; }
        #Profile { width:35%; }
        #Group { width:60%; }
        #votebtn { padding:7px 15px; border-radius:7px; background-color:#48dbfb; color:white; border:none; cursor:pointer; font-weight:bold; }
        #votebtn:hover { background-color:#34bce0; }
        #votebtn[disabled] { background-color:#28a745; cursor:not-allowed; }
        img { display:block; margin:0 auto 15px auto; border-radius:10px; border:2px solid #48dbfb; }
        hr { border:1px solid #ccc; }
        @media screen and (max-width:768px){ #mainSection { flex-direction:column; } #Profile, #Group { width:100%; } }
    </style>
</head>

<body>
    <div id="headerSection">
        <a href="../"><button id="backbtn">Back</button></a>
        <h1>E-Voting System</h1>
        <a href="logout.php"><button id="logoutbtn">Logout</button></a>
    </div>

    <hr>

    <div id="mainSection">  
        <!-- Profile Section -->
        <div id="Profile">
            <center>
                <img src="<?php echo getImage($userdata['photo']); ?>" height="100" width="100"><br><br>
            </center>
            <b>Name:</b> <?php echo htmlspecialchars($userdata['name']); ?> <br><br>
            <b>Mobile:</b> <?php echo htmlspecialchars($userdata['mobile']); ?> <br><br>
            <b>Address:</b> <?php echo htmlspecialchars($userdata['address']); ?> <br><br> 
            <b>Status:</b> <?php echo $status ?><br><br>
        </div>
     
        <!-- Group Section -->
        <div id="Group">
            <?php
            if(!empty($groupsdata)){
                foreach($groupsdata as $group){  
                    ?>
                    <div style="margin-bottom: 20px;">
                        <img style="float:right;" src="<?php echo getImage($group['photo']); ?>" height="100" width="100"><br><br>
                        <b>Group Name:</b> <?php echo htmlspecialchars($group['name']); ?><br><br>
                        <b>Votes:</b> <?php echo intval($group['votes']); ?><br><br>

                        <form action="../api/vote.php" method="post">
                            <input type="hidden" name="gid" value="<?php echo intval($group['id']); ?>">
                            <input type="hidden" name="gvotes" value="<?php echo intval($group['votes']); ?>">

                            <?php if($userdata['status']==0){ ?>
                                <input type="submit" name="votebtn" value="Vote" id="votebtn">
                            <?php } else { ?>
                                <button id="votebtn" disabled>Voted</button>
                            <?php } ?>
                        </form>
                    </div>
                    <hr>
                    <?php
                }
            } else {
                echo "<p>No group data available.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
