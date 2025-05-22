<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Load PHPMailer
require 'includes/conn.php'; // Database connection

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to send OTP email
function sendOTP($to, $name, $otp)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'variyamik2@gmail.com'; // Replace with your email
        $mail->Password = 'igqaklatjcuvwsmu';  // Use an App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('your-email@gmail.com', 'Voting System');
        $mail->addAddress($to, $name);
        $mail->isHTML(true);
        $mail->Subject = "Your OTP for Login";
        $mail->Body = "
            <html>
            <body>
                <p>Hello $name,</p>
                <p>Your OTP for login is: <b>$otp</b></p>
                <p>This OTP is valid for 60 seconds. Do not share it.</p>
            </body>
            </html>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        $_SESSION['error'] = "Email could not be sent: " . $mail->ErrorInfo;
        return false;
    }
}

// Handle OTP Request
if (isset($_POST['send_otp'])) {
    $email = $_POST['email'];
    $firstname = $_POST['firstname'];

    // Check if user exists
    $query = $conn->prepare("SELECT * FROM admin WHERE email = ? AND firstname = ?");
    $query->bind_param("ss", $email, $firstname);
    $query->execute();
    $result = $query->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $otp = rand(100000, 999999);
        $otp_expiry = date("Y-m-d H:i:s", time() + 60); // OTP valid for 60 seconds

        $updateQuery = $conn->prepare("UPDATE admin SET otp = ?, otp_expiry = ? WHERE email = ?");
        $updateQuery->bind_param("sss", $otp, $otp_expiry, $email);
        $updateQuery->execute();

        $_SESSION['otp_email'] = $email;
        $_SESSION['otp_firstname'] = $firstname;

        if (sendOTP($email, $user['firstname'], $otp)) {
            $_SESSION['success'] = "OTP sent! It will expire in 60 seconds.";
        } else {
            $_SESSION['error'] = "Failed to send OTP.";
        }
    } else {
        $_SESSION['error'] = "No account found with this email and name!";
    }
}

// Handle OTP Verification
if (isset($_POST['verify_otp'])) {
    $entered_otp = $_POST['otp'];

    if (!isset($_SESSION['otp_email']) || !isset($_SESSION['otp_firstname'])) {
        $_SESSION['error'] = "Session expired. Please request a new OTP.";
        header("Location: index.php");
        exit();
    }

    $email = $_SESSION['otp_email'];
    $firstname = $_SESSION['otp_firstname'];

    $query = $conn->prepare("SELECT id, firstname, lastname, otp_expiry FROM admin WHERE email = ? AND firstname = ? AND otp = ?");
    $query->bind_param("sss", $email, $firstname, $entered_otp);
    $query->execute();
    $result = $query->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if (strtotime($user['otp_expiry']) < time()) {
            $_SESSION['error'] = "OTP expired! Please request a new OTP.";
            header("Location: index.php");
            exit();
        }

        $clearOtpQuery = $conn->prepare("UPDATE admin SET otp = NULL, otp_expiry = NULL WHERE email = ?");
        $clearOtpQuery->bind_param("s", $email);
        $clearOtpQuery->execute();

        $_SESSION['admin'] = $user['id'];
        $_SESSION['user_name'] = $user['firstname'] . " " . $user['lastname'];
        $_SESSION['success'] = "Login successful! Welcome, " . $_SESSION['user_name'];

        unset($_SESSION['otp_email'], $_SESSION['otp_firstname']);

        header("Location: home.php");
        exit();
    } else {
        $_SESSION['error'] = "Invalid or expired OTP! Try again.";
        header("Location: otp_verification.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login with OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 50px;
        }

        .container {
            max-width: 400px;
            background: white;
            padding: 20px;
            margin: auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #218838;
        }

        .message {
            margin-top: 15px;
            color: red;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Login with OTP</h2>

        <?php
        if (isset($_SESSION['error'])) {
            echo "<p class='message'>{$_SESSION['error']}</p>";
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo "<p style='color: green;'>{$_SESSION['success']}</p>";
            unset($_SESSION['success']);
        }
        ?>

        <form method="POST">
            <input type="text" name="firstname" placeholder="Enter your First Name" required>
            <input type="email" name="email" placeholder="Enter your Email" required>
            <button type="submit" name="send_otp">Send OTP</button>
        </form>

        <?php if (isset($_SESSION['otp_email'])): ?>
            <h2>Enter OTP</h2>
            <form method="POST">
                <input type="text" name="otp" placeholder="Enter OTP" required>
                <button type="submit" name="verify_otp">Verify OTP</button>
            </form>

            <p id="timer"></p>
            <form method="POST">
                <button type="submit" name="send_otp" id="resendOtp" disabled>Resend OTP</button>
            </form>

            <script>
                let countdown = 60;
                let timerDisplay = document.getElementById("timer");
                let resendBtn = document.getElementById("resendOtp");

                function updateTimer() {
                    if (countdown > 0) {
                        timerDisplay.innerHTML = "Resend OTP in " + countdown + " seconds.";
                        countdown--;
                        setTimeout(updateTimer, 1000);
                    } else {
                        timerDisplay.innerHTML = "You can now resend OTP.";
                        resendBtn.disabled = false;
                    }
                }
                updateTimer();
            </script>
        <?php endif; ?>
    </div>
</body>

</html>