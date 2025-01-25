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
                            <form method="POST" action="../controllers/loginController.php">
                                <div class="input-group mb-4">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter your Email" required>
                                </div>
                                <div class="input-group mb-4">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                                </div>

                                <div class="form-check d-flex justify-content-start mb-4">
                                    <input class="form-check-input me-2" type="checkbox" id="rememberMe">
                                    <label class="form-check-label" for="rememberMe">
                                        Remember me
                                    </label>
                                </div>

                                <button type="submit" name="login" id="login" class="btn btn-primary w-100">Login</button>

                                <div class="mt-4 text-center">
                                    <p>Don't have an account? <a href="./Registration.php">Register</a></p>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6 d-flex align-items-center justify-content-center" style="background-color: #f7f7f7;">
                            <img src="../public/img/image.png"
                                 alt="Login" class="img-fluid" style="max-width: 100%; height: 100%;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
