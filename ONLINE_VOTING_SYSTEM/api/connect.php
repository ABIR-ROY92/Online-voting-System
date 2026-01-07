<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$connect = mysqli_connect(
  "sql305.infinityfree.com",
  "if0_40250694",
  "ud8HF4MRoHcUmBr",
  "if0_40250694_evoting"
);

if (!$connect) {
  die("Connection Failed: " . mysqli_connect_error());
}
?>
