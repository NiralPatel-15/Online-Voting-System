<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'includes/session.php';

// Load Composer's autoloader if using Composer
require 'vendor/autoload.php'; // Make sure this path is correct if you use Composer

if(isset($_POST['edit'])){
    $id = $_POST['id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);


    // Fetch the old details from the database
    $sql = "SELECT * FROM voters WHERE id = $id";
    $query = $conn->query($sql);
    $row = $query->fetch_assoc();


    // If the password is different, hash it, otherwise keep the old one

    // Update the voter information in the database
    $sql = "UPDATE voters SET firstname = '$firstname', lastname = '$lastname', password = '$password' WHERE id = '$id'";
    if($conn->query($sql)){
        $_SESSION['success'] = 'Voter updated successfully';

        // Email sending using PHPMailer
        $email = $row['email']; // assuming there's an 'email' column in your database for the voter
        $subject = "Your Voter Details Have Been Updated";
        
        // Format the message with old and updated details
        $message = "
            <html>
            <head>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f4f9;
                        margin: 0;
                        padding: 0;
                    }
                    .container {
                        max-width: 600px;
                        margin: 0 auto;
                        padding: 20px;
                        background-color: #ffffff;
                        border-radius: 8px;
                        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                    }
                    .header {
                        background-color: #007bff;
                        color: white;
                        padding: 15px;
                        text-align: center;
                        border-radius: 8px 8px 0 0;
                    }
                    .header h1 {
                        margin: 0;
                    }
                    .content {
                        padding: 20px;
                    }
                    .content p {
                        font-size: 16px;
                        line-height: 1.6;
                    }
                    .content table {
                        width: 100%;
                        margin-top: 20px;
                        border-collapse: collapse;
                    }
                    .content td, .content th {
                        padding: 8px;
                        border: 1px solid #ddd;
                        text-align: left;
                    }
                    
                    .footer {
                        background-color: #f8f9fa;
                        text-align: center;
                        padding: 10px;
                        border-radius: 0 0 8px 8px;
                    }
                    .footer p {
                        font-size: 14px;
                        color: #888;
                    }
                    .button {
                        display: inline-block;
                        background-color: #007bff;
                        color: white;
                        padding: 10px 20px;
                        text-decoration: none;
                        border-radius: 5px;
                        margin-top: 20px;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>Voter Details Update</h1>
                    </div>
                    <div class='content'>
                        <p>Hello $firstname $lastname,</p>
                        <p>Your voter details have been successfully updated. Below are your old and new details:</p>
                        <h3>Updated Details:</h3>
                        <table>
                            <tr>
                                <td><strong>First Name:</strong></td>
                                <td>$firstname</td>
                            </tr>
                            <tr>
                                <td><strong>Last Name:</strong></td>
                                <td>$lastname</td>
                            </tr>
                            <tr>
                                <td><strong>Password:</strong></td>
                                <td>Your password has been updated to {$_POST['password']} successfully.</td>
                            </tr>
                        </table>
                        <p>If you did not request this change, please contact us immediately.</p>
                    </div>
                    <div class='footer'>
                        <p>Regards,<br>Voter Registration Team</p>
                    </div>
                </div>
            </body>
            </html>
        ";

        // Instantiate PHPMailer
        $mail = new PHPMailer(true);

        try {
            // SMTP settings
			$mail->isSMTP();  // Set mailer to use SMTP
			$mail->Host = 'smtp.gmail.com'; // Use Gmail SMTP server (change it if you use another SMTP server)
			$mail->SMTPAuth = true;
			$mail->Username = 'variyamik2@gmail.com'; // Your email address
			$mail->Password = 'igqaklatjcuvwsmu'; // Your email password or app-specific password for Gmail
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use TLS encryption
			$mail->Port = 587; // SMTP port for TLS

            // Recipients
            $mail->setFrom('no-reply@yourdomain.com', 'Your Company');
            $mail->addAddress($email, "$firstname $lastname"); // Add recipient

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $message;

            // Send the email
            if($mail->send()) {
                $_SESSION['success'] .= ' An email notification has been sent to the voter.';
            } else {
                $_SESSION['error'] = 'Failed to send email notification.';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Mailer Error: ' . $mail->ErrorInfo;
        }

    } else {
        $_SESSION['error'] = $conn->error;
    }
} else {
    $_SESSION['error'] = 'Fill up edit form first';
}

header('location: voters.php');
?>
