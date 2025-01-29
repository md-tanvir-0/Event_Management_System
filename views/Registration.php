<?php
session_start();
$errors = $_SESSION['registration_errors'] ?? [];
$old_input = $_SESSION['old_input'] ?? [];
unset($_SESSION['registration_errors'], $_SESSION['old_input']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
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
            margin-bottom: 1rem;
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
                                <h3 class="text-center fw-bold mb-5">Sign Up</h3>
                                <form method="POST" action="../controllers/registrationController.php" enctype='multipart/form-data'>
                                    <div class="input-group-wrapper">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            <input type="text" name="fullName" id="fullName" class="form-control" placeholder="Enter Full Name" value="<?php echo htmlspecialchars($old_input['fullName'] ?? ''); ?>">
                                            <?php if (isset($errors['fullName'])): ?>
                                                <div class="error-message"><?php echo htmlspecialchars($errors['fullName']); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="input-group-wrapper">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            <input type="text" name="phone" id="phone" class="form-control" placeholder="Enter your Phone Number" value="<?php echo htmlspecialchars($old_input['phone'] ?? ''); ?>">
                                            <?php if (isset($errors['phone'])): ?>
                                                <div class="error-message"><?php echo htmlspecialchars($errors['phone']); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="input-group-wrapper">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            <input type="email" name="email" id="email" class="form-control" placeholder="Enter your Email" value="<?php echo htmlspecialchars($old_input['email'] ?? ''); ?>">
                                            <?php if (isset($errors['email'])): ?>
                                                <div class="error-message"><?php echo htmlspecialchars($errors['email']); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="input-group-wrapper">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            <input type="password" name="password" id="password" class="form-control" placeholder="Password"value="<?php echo htmlspecialchars($old_input['password'] ?? ''); ?>">
                                            <?php if (isset($errors['password'])): ?>
                                                <div class="error-message"><?php echo htmlspecialchars($errors['password']); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="input-group-wrapper">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                            <input type="password" name="confirmPassword" id="confirmPassword" class="form-control" placeholder="Confirm Password"value="<?php echo htmlspecialchars($old_input['confirmPassword'] ?? ''); ?>">
                                            <?php if (isset($errors['confirmPassword'])): ?>
                                                <div class="error-message"><?php echo htmlspecialchars($errors['confirmPassword']); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="form-check d-flex justify-content-start mb-4">
                                        <input class="form-check-input me-2" type="checkbox" id="terms">
                                        <label class="form-check-label" for="terms">
                                            I agree to the <a href="#">Terms of Service</a>
                                        </label>
                                        <?php if (isset($errors['terms'])): ?>
                                                <div class="error-message"><?php echo htmlspecialchars($errors['terms']); ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <button type="submit" name="register" id="register" class="btn btn-primary w-100">Register</button>
                                    <div class="mt-3 text-center">
                                    <p>Already have an account? <a href="./Login.php">Login</a></p>
                                </div>
                                </form>
                            </div>
                            <div class="col-md-6 d-flex align-items-center justify-content-center" style="background-color: #f7f7f7;">
                                <img src="../public/img/logo.jpg" alt="Registration" class="img-fluid" style="max-width: 100%; height: 100%;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.querySelector("form");
            const fullName = document.getElementById("fullName");
            const phone = document.getElementById("phone");
            const email = document.getElementById("email");
            const password = document.getElementById("password");
            const confirmPassword = document.getElementById("confirmPassword");
            const terms = document.getElementById("terms");

            form.addEventListener("submit", function(e) {
                e.preventDefault();
                clearErrors();
                let isValid = true;
                if (fullName.value.trim().length < 1) {
                    showError(fullName, "Full Name must be at least 3 characters long.");
                    isValid = false;
                }
                const phonePattern = /^[0-9]{10,14}$/;
                if (!phonePattern.test(phone.value.trim())) {
                    showError(phone, "Phone number must be between 10 and 14 digits.");
                    isValid = false;
                }
                if (email.value.trim().length < 1 || !email.validity.valid) {
                    showError(email, "Please enter a valid email address.");
                    isValid = false;
                }
                if (password.value.length < 8) {
                    showError(password, "Password must be at least 8 characters long.");
                    isValid = false;
                }
                if (password.value !== confirmPassword.value) {
                    showError(confirmPassword, "Passwords do not match.");
                    isValid = false;
                }
                if (!terms.ariaChecked) {
                    showError(terms, "Please agree to the Terms of Service.");
                    isValid = false;
                }

                if (!isValid) {
                    form.submit();
                }
            });

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