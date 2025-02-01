<?php
require_once('../models/dbmodel.php');

class RegistrationValidator {
    private $errors = [];
    
    public function validate($data) {
        $this->validateFullName($data['fullName']);
        $this->validatePhone($data['phone']);
        $this->validateEmail($data['email']);
        $this->validatePassword($data['password'], $data['confirmPassword']);
        
        return empty($this->errors);
    }
    
    private function validateFullName($fullName) {
        $fullName = trim($fullName);
        if (empty($fullName)) {
            $this->errors['fullName'] = "Full name is required";
        } elseif (strlen($fullName) < 2 || strlen($fullName) > 50) {
            $this->errors['fullName'] = "Full name must be between 2 and 50 characters";
        } elseif (!preg_match("/^[a-zA-Z\s]+$/", $fullName)) {
            $this->errors['fullName'] = "Full name can only contain letters and spaces";
        }
    }
    
    private function validatePhone($phone) {
        $phone = trim($phone);
        if (empty($phone)) {
            $this->errors['phone'] = "Phone number is required";
        } elseif (!preg_match("/^[0-9]{10,14}$/", $phone)) {
            $this->errors['phone'] = "Phone number must be between 10 and 14 digits";
        }
    }
    
    private function validateEmail($email) {
        $email = trim($email);
        if (empty($email)) {
            $this->errors['email'] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = "Invalid email format";
        } elseif (strlen($email) > 255) {
            $this->errors['email'] = "Email is too long";
        }
    }
    
    private function validatePassword($password, $confirmPassword) {
        if (empty($password)) {
            $this->errors['password'] = "Password is required";
        } elseif (strlen($password) < 8) {
            $this->errors['password'] = "Password must be at least 8 characters long";
        } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/", $password)) {
            $this->errors['password'] = "Password must contain at least one uppercase letter, one lowercase letter, and one number";
        }
        
        if ($password !== $confirmPassword) {
            $this->errors['confirmPassword'] = "Passwords do not match";
        }
    }
    
    public function getErrors() {
        return $this->errors;
    }
}

function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {

        session_start();

        $formData = [
            'fullName' => sanitizeInput($_POST['fullName']),
            'phone' => sanitizeInput($_POST['phone']),
            'email' => sanitizeInput($_POST['email']),
            'password' => $_POST['password'],
            'confirmPassword' => $_POST['confirmPassword']
        ];

        $validator = new RegistrationValidator();
        if (!$validator->validate($formData)) {
            $_SESSION['registration_errors'] = $validator->getErrors();
            $_SESSION['old_input'] = array_diff_key($formData, ['password' => '', 'confirmPassword' => '']);
            header("Location: ../views/Registration.php");
            exit();
        }

        $hashedPassword = password_hash($formData['password'], PASSWORD_BCRYPT, ['cost' => 12]);

        $result = RegistrationData(
            $formData['fullName'],
            $formData['phone'],
            $formData['email'],
            $hashedPassword
        );

        if ($result === 'Email already used') {
            $_SESSION['registration_errors'] = ['email' => 'This email is already registered'];
            $_SESSION['old_input'] = array_diff_key($formData, ['password' => '', 'confirmPassword' => '']);
            header("Location: ../views/Registration.php");
            exit();
        } elseif ($result === true) {
            $_SESSION['success_message'] = 'Registration successful! Please log in.';
            header("Location: ../views/Login.php");
            exit();
        } else {
            $_SESSION['registration_errors'] = ['general' => 'Registration failed. Please try again later.'];
            header("Location: ../views/Registration.php");
            exit();
        }
    }
} catch (Exception $e) {
    error_log("Registration error: " . $e->getMessage());
    $_SESSION['registration_errors'] = ['general' => 'An unexpected error occurred. Please try again later.'];
    header("Location: ../views/Registration.php");
    exit();
}
?>