<?php
require_once('../models/dbmodel.php');
//require_once('../views/Login.php');

try{
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
        session_start();
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
    
        $result = LoginData($email, $password);
    
        if ($result === 'Invalid Email') {
            echo "<script>alert('Invalid Email');</script>";
            exit();
        } elseif ($result === 'Invalid Password') {
            echo "<script>alert('Invalid Password');</script>";
            exit();
        } elseif (is_numeric($result)) {
            $_SESSION['userid'] = $result;
            header("Location: ../views/Dashboard.php");
            exit();
        } else {
            echo "<script>alert('Login failed. Please try again later');</script>";
            exit();
        }
    } 
}
catch(Exception $e){
    echo $e->getMessage();
}
?>