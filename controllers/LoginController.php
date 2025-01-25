<?php
require_once('../models/dbmodel.php');
require_once('../views/Login.php');
try{
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {

        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
    
        $result = LoginData($email, $password);
    
        if ($result === 'Invalid email') {
            echo "<script>alert('Invalid email');</script>";
            //header("Location: ../views/Login.php");
            exit();
        }
        elseif ($result === 'Invalid password') {
            echo "<script>alert('Invalid password');</script>";
            //header("Location: ../views/Login.php");
            exit();
        }
        elseif ($result) {
            echo "<script>alert('Login Successfully');</script>";
            //header("Location: ../views/Home.php");
            exit();
        }
        else {
            echo "<script>alert('Login failed. Please try again later');</script>";
            //header("Location: ../views/Login.php");
            exit();
        }
    }
}
catch(Exception $e){
    echo $e->getMessage();
}