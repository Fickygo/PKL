<?php
require 'function.php';

// Perbaikan logika login
if (isset($_SESSION['login'])) {
    // Sudah login, redirect ke index
    header('location:index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="PDP Printing Login Page" />
    <meta name="author" content="PKL 202251014" />
    <title>Login - PDP Printing</title>
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            background: linear-gradient(135deg, #4f9cf9, #0056b3);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .card-header {
            background-color: #ffffff;
            border-bottom: none;
            padding: 25px 0 15px;
        }

        .card-header h3 {
            color: #333;
            font-weight: 600;
        }

        .card-body {
            padding: 30px;
        }

        .form-floating .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .btn-login {
            background: linear-gradient(135deg, #4f9cf9, #0056b3);
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 86, 179, 0.3);
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            max-width: 150px;
            margin-bottom: 10px;
        }

        footer {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: white !important;
        }

        .text-muted {
            color: rgba(255, 255, 255, 0.8) !important;
        }
    </style>
</head>

<body>
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <div class="logo-container">
                                        <i class="fas fa-print fa-3x text-primary"></i>
                                        <h3 class="text-center my-3"><img src="./Index/img/logo.png" alt="" style="margin: 0;"></h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form method="post">
                                        <div class="form-floating mb-4">
                                            <input class="form-control" id="inputEmail" name="username" type="text" placeholder="Enter username" required />
                                            <label for="inputEmail"><i class="fas fa-user me-2"></i>Username</label>
                                        </div>
                                        <div class="form-floating mb-4">
                                            <input class="form-control" id="inputPassword" name="password" type="password" placeholder="Password" required />
                                            <label for="inputPassword"><i class="fas fa-lock me-2"></i>Password</label>
                                        </div>
                                        <div class="mt-4 mb-0">
                                            <button type="submit" name="login" class="btn btn-primary btn-login">
                                                <i class="fas fa-sign-in-alt me-2"></i>Login
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <footer class="py-3 mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-center small">
                        <div class="text-muted">Copyright &copy; PDP Printing - PKL 202251014</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>

</html>