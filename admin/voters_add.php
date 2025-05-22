<?php
include 'includes/session.php';

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure PHPMailer is properly loaded

if (isset($_POST['add'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $dob = $_POST['dob']; // Get date of birth from form
    $password_plain = $_POST['password'];
    $password = password_hash($password_plain, PASSWORD_DEFAULT);
    $filename = $_FILES['photo']['name'];

    if (!empty($filename)) {
        move_uploaded_file($_FILES['photo']['tmp_name'], '../images/' . $filename);
    }

    // Validate DOB and Age
    $birthdate = new DateTime($dob);
    $today = new DateTime();
    $age = $today->diff($birthdate)->y;

    if ($age < 18) {
        $_SESSION['error'] = "You must be at least 18 years old to register.";
        header('location: voters.php');
        exit();
    }

    // Generate voter ID
    $set = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $voter = substr(str_shuffle($set), 0, 15);

    // Insert voter details into the database
    $sql = "INSERT INTO voters (voters_id, password, firstname, lastname, dob, photo, email) VALUES ('$voter', '$password', '$firstname', '$lastname', '$dob', '$filename', '$email')";

    if ($conn->query($sql)) {
        // Initialize PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'variyamik2@gmail.com';
            $mail->Password = 'igqaklatjcuvwsmu';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('no-reply@voting.com', 'Voter Registration');
            $mail->addAddress($email, "$firstname $lastname");

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Voter Registration Confirmation';
            $mail->Body = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; padding: 0; }
                        .container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); }
                        .header { background-color: #007bff; color: white; padding: 15px; text-align: center; border-radius: 8px 8px 0 0; }
                        .content { padding: 20px; }
                        .footer { background-color: #f8f9fa; text-align: center; padding: 10px; border-radius: 0 0 8px 8px; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h1>Voter Registration</h1>
                        </div>
                        <div class='content'>
                            <p>Hello $firstname $lastname,</p>
                            <p>Thank you for registering as a voter. Here are your registration details:</p>
                            <table width='100%' cellpadding='5' cellspacing='0'>
                                <tr>
                                    <td><strong>Voter ID:</strong></td>
                                    <td>$voter</td>
                                </tr>
                                <tr>
                                    <td><strong>Password:</strong></td>
                                    <td>$password_plain</td>
                                </tr>
                            </table>
                            <p>Please keep your credentials safe.</p>
                        </div>
                        <div class='footer'>
                            <p>Regards,<br>Voter Registration Team</p>
                        </div>
                    </div>
                </body>
                </html>
            ";

            // Send email
            $mail->send();
            $_SESSION['success'] = 'Voter added successfully and email sent.';
        } catch (Exception $e) {
            $_SESSION['error'] = "Voter added successfully, but email failed: {$mail->ErrorInfo}";
        }
    } else {
        $_SESSION['error'] = $conn->error;
    }
} else {
    $_SESSION['error'] = 'Fill up the add form first';
}

header('location: voters.php');
?>
