<?php
session_start();
$errors = $_SESSION['login_errors'] ?? [];
$old_input = $_SESSION['old_input'] ?? [];
unset($_SESSION['login_errors'], $_SESSION['old_input']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 25px;
        }
        .input-group-text {
            background-color: #f7f7f7;
            border-right: none;
            color: #adb5bd;
        }
        .form-control {
            border-left: none;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
            width: 100%;
            padding-left: 2.5rem;
        }
        .input-group-wrapper {
            margin-bottom: 1.5rem;
            position: relative;
        }
        .input-group {
            margin-bottom: 0;
        }
        .input-group.is-invalid .form-control {
            border-color: #dc3545;
        }
        .input-group.is-invalid .input-group-text {
            border-color: #dc3545;
            color: #dc3545;
        }
        .alert {
            margin-bottom: 0.5rem;
            border-radius: 0.5rem;
        }
    </style>
</head>
<body>
<section class="vh-100">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-xl-10">
                <div class="card text-black">
                    <div class="row g-0">
                        <div class="col-md-6 p-5">
                            <h3 class="text-center fw-bold mb-5">Login</h3>
                            
                            <?php if (isset($_SESSION['success_message'])): ?>
                            <div class="alert alert-success" role="alert">
                                <?php 
                                echo htmlspecialchars($_SESSION['success_message']);
                                unset($_SESSION['success_message']);
                                ?>
                            </div>
                            <?php endif; ?>

                            <?php if (isset($errors['general'])): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($errors['general']); ?>
                            </div>
                            <?php endif; ?>

                            <form method="POST" action="../controllers/loginController.php" id="loginForm">
                                <div class="input-group-wrapper">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="text" 
                                               name="email" 
                                               id="email" 
                                               class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                                               placeholder="Enter your Email"
                                               value="<?php echo htmlspecialchars($old_input['email'] ?? ''); ?>">
                                    </div>
                                    <?php if (isset($errors['email'])): ?>
                                        <div class="error-message"><?php echo htmlspecialchars($errors['email']); ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="input-group-wrapper">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" 
                                            name="password" 
                                            id="password" 
                                            class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" 
                                            placeholder="Password">
                                        <button type="button" class="btn border" id="toggle-password">
                                            <i class="fas fa-eye"></i> <!-- Eye icon to show password -->
                                        </button>
                                    </div>
                                    <?php if (isset($errors['password'])): ?>
                                        <div class="error-message"><?php echo htmlspecialchars($errors['password']); ?></div>
                                    <?php endif; ?>
                                </div>

                                <script>
                                    document.getElementById('toggle-password').addEventListener('click', function() {
                                        var passwordField = document.getElementById('password');
                                        var icon = this.querySelector('i');

                                        if (passwordField.type === "password") {
                                            passwordField.type = "text";
                                            icon.classList.remove('fa-eye');
                                            icon.classList.add('fa-eye-slash'); // Change icon to show the password is visible
                                        } else {
                                            passwordField.type = "password";
                                            icon.classList.remove('fa-eye-slash');
                                            icon.classList.add('fa-eye'); // Change icon back to hide the password
                                        }
                                    });
                                </script>

                                <div class="form-check d-flex justify-content-start mb-3">
                                    <input class="form-check-input me-2" type="checkbox" id="rememberMe" name="rememberMe">
                                    <label class="form-check-label" for="rememberMe">
                                        Remember me
                                    </label>
                                </div>

                                <button type="submit" name="login" id="login" class="btn btn-primary w-100">Login</button>

                                <div class="mt-3 text-center">
                                    <p>Don't have an account? <a href="./Registration.php">Register</a></p>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6 d-flex align-items-center justify-content-center" style="background-color: #f7f7f7;">
                            <img src="../public/img/image.png" alt="Login" class="img-fluid" style="max-width: 100%; height: 100%;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("loginForm");
    const email = document.getElementById("email");
    const password = document.getElementById("password");

    form.addEventListener("submit", function(e) {
        //e.preventDefault();
        clearErrors();
        let isValid = true;
        if (email.value.trim() === "") {
            showError(email, "Enter your Email");
            isValid = false;
        }
        else if (!isValidEmail(email.value.trim())) {
            showError(email, "Please enter a valid email address");
            isValid = false;
        }
        // else if(password.value === "") {
        //     showError(password, "Wrong password");
        //     isValid = false;
        // }

        if (!isValid) {
            e.preventDefault();
        }
    });
    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
    function clearErrors() {
        const errorMessages = document.querySelectorAll(".error-message");
        errorMessages.forEach(error => error.remove());

        const invalidInputGroups = document.querySelectorAll(".input-group.is-invalid");
        invalidInputGroups.forEach(group => group.classList.remove("is-invalid"));
    }

    function showError(input, message) {
        const inputGroup = input.closest(".input-group");
        const wrapper = inputGroup.parentElement;
        inputGroup.classList.add("is-invalid");
        const error = document.createElement("div");
        error.className = "error-message";
        error.innerText = message;
        wrapper.appendChild(error);
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>