<?php
session_start();
require_once('../models/dbmodel.php');
//require_once('../views/Login.php');
require_once('../controllers/loginController.php');
if (!isset($_SESSION['userid'])) {
    header("Location: ../views/Login.php"); // Redirect to login page if not logged in
    exit();
}
$userId = $_SESSION['userid'];
$fullName = getFullName($userId);
?>