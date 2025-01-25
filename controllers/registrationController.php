<?php
require_once('../models/dbmodel.php');
require_once('../views/Registration.php');
try{
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {

        $fullName = htmlspecialchars($_POST['fullName']);
        $phone = htmlspecialchars($_POST['phone']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $confirmPassword = htmlspecialchars($_POST['confirmPassword']);
    
        if ($password !== $confirmPassword) {
            echo "<script>alert('Passwords do not match!');</script>";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $result = RegistrationData($fullName, $phone, $email, $hashedPassword);
    
            if ($result === 'Email already used') {
                echo "<script>alert('Email already used');</script>";
                exit();
            }
            elseif ($result === true) {
                echo "<script>alert('Registration successful');</script>";
                header("Location: ../views/Login.php");
                exit();
            }
            else {
                echo "<script>alert('Registration failed. Please try again later');</script>";
                exit();
            }
        }
    }
}
catch(Exception $e){
    echo $e->getMessage();
}
?>
