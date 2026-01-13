<?php
include '../includes/db.php';
$id = $_GET['id'];
$db->query("DELETE FROM medicines WHERE id='$id'");
header("Location: dashboard.php");
exit;
