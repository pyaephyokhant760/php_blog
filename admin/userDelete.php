<?php
require '../config/config.php';
$pdoStatement = $conn->prepare("DELETE FROM users WHERE id=".$_GET['id']);
$pdoStatement->execute();

header("Location: user.php");

?>