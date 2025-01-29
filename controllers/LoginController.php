<?php
require_once('../models/dbmodel.php');

class LoginValidator {
    private $errors = [];
    
    public function validate($data) {
        try {
            $this->validateEmail($data['email']);
            //$this->validatePassword($data['password']);
        } catch (Exception $e) {
            error_log("Login validation error: " . $e->getMessage());
            $this->errors['general'] = 'An unexpected error occurred. Please try again later.';
        }
        return empty($this->errors);
    }
    
    private function validateEmail($email) {
        try{
            $email = trim($email);
            if (empty($email)) {
                $this->errors['email'] = "Email is required";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->errors['email'] = "Invalid email format";
            } elseif (strlen($email) > 255) {
                $this->errors['email'] = "Email is too long";
            }
        } catch (Exception $e) {
            error_log("Email validation error: " . $e->getMessage());
            $this->errors['general'] = 'An unexpected error occurred. Please try again later.';
        }
       
    }
    
    // private function validatePassword($password) {
    //     if (empty($password)) {
    //         $this->errors['password'] = "Password is required";
    //     }
    // }
    
    public function getErrors() {
        return $this->errors;
    }
}

function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        session_start();
        $formData = [
            'email' => sanitizeInput($_POST['email']),
            'password' => $_POST['password'] 
        ];

        $validator = new LoginValidator();
        if (!$validator->validate($formData)) {
            $_SESSION['login_errors'] = $validator->getErrors();
            $_SESSION['old_input'] = ['email' => $formData['email']];
            header("Location: ../views/Login.php");
            exit();
        }

        $result = LoginData($formData['email'], $formData['password']);
        if ($result === 'Invalid Email') {
            $_SESSION['login_errors'] = ['email' => 'No account found with this email'];
            $_SESSION['old_input'] = ['email' => $formData['email']];
            header("Location: ../views/Login.php");
            exit();

        } elseif ($result === 'Invalid Password') {
            $_SESSION['login_errors'] = ['password' => 'Incorrect password'];
            $_SESSION['old_input'] = ['email' => $formData['email']];
            header("Location: ../views/Login.php");
            exit();

        } elseif (is_numeric($result)) {
            $_SESSION['userid'] = $result;

            if (isset($_POST['rememberMe'])) {
                setcookie('user_email', $formData['email'], time() + (86400 * 30), "/");
            }
            
            header("Location: ../views/Dashboard.php");
            exit();
        } else {
            $_SESSION['login_errors'] = ['general' => 'Login failed. Please try again later.'];
            header("Location: ../views/Login.php");
            exit();
        }
    }
} catch (Exception $e) {
    error_log("Login error: " . $e->getMessage());
    $_SESSION['login_errors'] = ['general' => 'An unexpected error occurred. Please try again later.'];
    header("Location: ../views/Login.php");
    exit();
}