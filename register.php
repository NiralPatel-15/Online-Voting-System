<?php
session_start();
require 'includes/conn.php'; // Database connection

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Function to validate password strength
function validatePassword($password)
{
    if (strlen($password) < 8) {
        return "Password must be at least 8 characters long.";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        return "Password must contain at least one uppercase letter.";
    }
    if (!preg_match('/[a-z]/', $password)) {
        return "Password must contain at least one lowercase letter.";
    }
    if (!preg_match('/[0-9]/', $password)) {
        return "Password must contain at least one number.";
    }
    if (!preg_match('/[\W]/', $password)) {
        return "Password must contain at least one special character.";
    }
    return true;
}

/// Function to send email with styled HTML format
function sendMail($to, $name, $subject, $voterID, $password_plain)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'variyamik2@gmail.com'; // Change to your email
        $mail->Password = 'igqaklatjcuvwsmu'; // Use App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('variyamik2@gmail.com', 'Voting System');
        $mail->addAddress($to, $name);
        $mail->isHTML(true);
        $mail->Subject = $subject;

        $created_on = date("Y-m-d H:i:s");

        // HTML email template
        $mail->Body = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px; }
                .container { background: #fff; padding: 20px; border-radius: 8px; max-width: 600px; margin: auto; }
                .header { background: #007bff; color: white; padding: 10px; text-align: center; border-radius: 8px 8px 0 0; }
                .footer { background: #f1f1f1; text-align: center; padding: 10px; border-radius: 0 0 8px 8px; font-size: 14px; color: #333; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                td { padding: 10px; border-bottom: 1px solid #ddd; }
                strong { color: #333; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>New Voter Registered</h2>
                </div>
                <p>A new user has registered:</p>
                <table>
                    <tr><td><strong>Username:</strong></td><td>$name</td></tr>
                    <tr><td><strong>Email:</strong></td><td>$to</td></tr>
                    <tr><td><strong>Voter ID:</strong></td><td>$voterID</td></tr>
                    <tr><td><strong>Password:</strong></td><td>$password_plain</td></tr>
                </table>
                <p>Please verify the voter in the admin panel.</p>
                <div class='footer'>
                    <p>Voting System</p>
                </div>
            </div>
        </body>
        </html>";

        $mail->send();
    } catch (Exception $e) {
        $_SESSION['error'] = "Email not sent: " . $mail->ErrorInfo;
    }
}

// Registration Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $set = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $voters_id = substr(str_shuffle($set), 0, 15);

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $password_plain = $_POST['password'];

    // Check if email already exists
    $email_check = $conn->prepare("SELECT * FROM voters WHERE email = ?");
    $email_check->bind_param("s", $email);
    $email_check->execute();
    $result = $email_check->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "This email is already registered. Please use a different email.";
        header("Location: register.php");
        exit();
    }

    // Validate password
    $passwordValidation = validatePassword($password_plain);
    if ($passwordValidation !== true) {
        $_SESSION['error'] = $passwordValidation;
        header("Location: register.php");
        exit();
    }

    $password = password_hash($password_plain, PASSWORD_DEFAULT);
    $filename = $_FILES['photo']['name'];
    $upload_dir = 'images/';
    $allowed_types = ['jpg', 'jpeg', 'png']; // Allowed image formats

    // Validate age
    if (empty($dob) || $dob == '0000-00-00') {
        $_SESSION['error'] = "Invalid Date of Birth.";
        header("Location: register.php");
        exit();
    }

    $birthdate = new DateTime($dob);
    $today = new DateTime();
    $age = $today->diff($birthdate)->y;

    if ($age < 18) {
        $_SESSION['error'] = "You must be at least 18 years old to register.";
        header("Location: register.php");
        exit();
    }

    // Upload Photo with Validation
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    if (!empty($filename)) {
        $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (!in_array($filetype, $allowed_types)) {
            $_SESSION['error'] = "Only JPG, JPEG, and PNG files are allowed.";
            header("Location: register.php");
            exit();
        }

        move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $filename);
    } else {
        $_SESSION['error'] = "Photo is required.";
        header("Location: register.php");
        exit();
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO voters (voters_id, password, firstname, lastname, photo, email, dob) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $voters_id, $password, $firstname, $lastname, $filename, $email, $dob);

    if ($stmt->execute()) {
        sendMail($email, "$firstname $lastname", "Voter Registration Confirmation", $voters_id, $password_plain);
        $_SESSION['success'] = "Registration successful! Check your email.";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = "Registration failed! Try again.";
        header("Location: register.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        input,
        button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #0056b3;
        }

        .error {
            color: red;
            text-align: center;
        }

        .success {
            color: green;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Voter Registration</h2>
        <?php
        if (isset($_SESSION['error'])) {
            echo "<p class='error'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo "<p class='success'>" . $_SESSION['success'] . "</p>";
            unset($_SESSION['success']);
        }
        ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group"><input type="text" name="firstname" placeholder="First Name" required></div>
            <div class="form-group"><input type="text" name="lastname" placeholder="Last Name" required></div>
            <div class="form-group"><input type="email" name="email" placeholder="Email" required></div>
            <div class="form-group"><input type="date" name="dob" required></div>
            <div class="form-group"><input type="password" name="password" placeholder="Password" required></div>
            <div class="form-group"><input type="file" name="photo" accept=".jpg,.jpeg,.png" required></div>
            <button type="submit" name="register">Register</button>
        </form>
    </div>
</body>

</html>