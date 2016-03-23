<?php
session_start();
$_SESSION = array();
unset($_SESSION);
header("Location: ./index.php");
?>