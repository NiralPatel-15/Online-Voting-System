<?php
session_start();
include 'includes/conn.php'; // Database connection

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // PHPMailer

// Function to validate password strength
function validatePassword($password)
{
    if (strlen($password) < 8) return "Password must be at least 8 characters long.";
    if (!preg_match('/[A-Z]/', $password)) return "Password must contain at least one uppercase letter.";
    if (!preg_match('/[a-z]/', $password)) return "Password must contain at least one lowercase letter.";
    if (!preg_match('/[0-9]/', $password)) return "Password must contain at least one number.";
    if (!preg_match('/[\W]/', $password)) return "Password must contain at least one special character.";
    return true;
}

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password_plain = trim($_POST['password']);
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $created_on = trim($_POST['created_on']);

    // Validate password
    $password_validation = validatePassword($password_plain);
    if ($password_validation !== true) {
        $_SESSION['error'] = $password_validation;
        header('location: register.php');
        exit();
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "This email is already registered.";
        header('location: register.php');
        exit();
    }

    // Hash the password
    $password = password_hash($password_plain, PASSWORD_DEFAULT);

    // File Upload Handling
    $photo = "";
    if (!empty($_FILES['photo']['name'])) {
        $filename = $_FILES['photo']['name'];
        $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png'];

        if (in_array($filetype, $allowed_types)) {
            $target_dir = "uploads/";
            $photo = uniqid() . "." . $filetype;
            $target_file = $target_dir . $photo;
            move_uploaded_file($_FILES['photo']['tmp_name'], $target_file);
        } else {
            $_SESSION['error'] = "Only JPG, JPEG, and PNG files are allowed.";
            header('location: register.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "Profile photo is required.";
        header('location: register.php');
        exit();
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO admin (username, password, firstname, lastname, email, photo, created_on) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $username, $password, $firstname, $lastname, $email, $photo, $created_on);

    if ($stmt->execute()) {
        // Initialize PHPMailer
        $mail = new PHPMailer(true);
        try {
            // SMTP Configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'variyamik2@gmail.com'; // Change to your email
            $mail->Password = 'igqaklatjcuvwsmu'; // Use App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Sender & Recipient
            $mail->setFrom('no-reply@yourdomain.com', 'Voting System');
            $mail->addAddress($email, "$firstname $lastname");

            // Email Content
            $mail->isHTML(true);
            $mail->Subject = 'New User Registration';
            $mail->Body = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px; }
                        .container { background: #fff; padding: 20px; border-radius: 8px; }
                        .header { background: #007bff; color: white; padding: 10px; text-align: center; border-radius: 8px 8px 0 0; }
                        .footer { background: #f1f1f1; text-align: center; padding: 10px; border-radius: 0 0 8px 8px; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h2>New User Registered</h2>
                        </div>
                        <p>A new user has registered:</p>
                        <table>
                            <tr><td><strong>Username:</strong></td><td>$username</td></tr>
                            <tr><td><strong>Email:</strong></td><td>$email</td></tr>
                            <tr><td><strong>Password:</strong></td><td>$password_plain</td></tr>
                            <tr><td><strong>Registered On:</strong></td><td>$created_on</td></tr>
                        </table>
                        <p>Please verify the user in the admin panel.</p>
                        <div class='footer'>
                            <p>Voting System</p>
                        </div>
                    </div>
                </body>
                </html>";

            // Send email
            $mail->send();
            $_SESSION['success'] = "User registered successfully! Admin has been notified.";
            header('location: login.php');
            exit();
        } catch (Exception $e) {
            $_SESSION['error'] = "User registered but email failed: " . $mail->ErrorInfo;
            header('location: register.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "Something went wrong.";
        header('location: register.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        /* Centering the form */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
            margin: 0;
        }

        /* Registration form styling */
        .register-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 400px;
        }

        h3 {
            margin-bottom: 20px;
            color: #007bff;
            text-align: center;
        }

        /* Left align labels */
        .form-label {
            font-weight: 600;
            display: block;
            text-align: left;
        }

        .btn-primary {
            background: #007bff;
            border: none;
            padding: 10px;
            font-size: 16px;
            width: 100%;
        }

        .btn-primary:hover {
            background: #0056b3;
        }
    </style>
</head>

<body>

    <div class="register-container">
        <h3>Register New User</h3>

        <!-- Display Messages -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error'];
                                            unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success'];
                                                unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <form action="register.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <div class="mb-3">
                <label class="form-label">First Name</label>
                <input type="text" class="form-control" name="firstname" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Last Name</label>
                <input type="text" class="form-control" name="lastname" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Date of Registration</label>
                <input type="date" class="form-control" name="created_on" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Profile Photo</label>
                <input type="file" class="form-control" name="photo" accept=".jpg,.jpeg,.png" required>
            </div>

            <button type="submit" name="register" class="btn btn-primary">Register</button>
        </form>

        <p class="mt-3 text-center">
            Already have an account? <a href="login.php">Login</a>
        </p>
    </div>

</body>

</html>