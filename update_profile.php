<?php
include 'includes/session.php';

$conn = new mysqli("localhost", "root", "", "votingsystem");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['update'])) {
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; // Will hash only if not empty
    $voter_id = $_SESSION['voter'];

    // Fetch the current photo from the database
    $query = "SELECT photo FROM voters WHERE id='$voter_id'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $current_photo = $row['photo'];

    // Handle image upload
    if (!empty($_FILES['photo']['name'])) {
        $target_dir = "images/";
        $new_photo = time() . "_" . basename($_FILES["photo"]["name"]);
        $target_file = $target_dir . $new_photo;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Allowed file types
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                // Delete old photo if not default
                if ($current_photo !== "default.png" && file_exists("images/" . $current_photo)) {
                    unlink("images/" . $current_photo);
                }
            } else {
                $new_photo = $current_photo; // Keep existing if upload fails
            }
        } else {
            echo "<script>alert('Invalid file type. Only JPG, JPEG, PNG, and GIF allowed.'); window.location='profile.php';</script>";
            exit();
        }
    } else {
        $new_photo = $current_photo; // Keep existing if no new file
    }

    // Update voter details
    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE voters SET firstname='$firstname', lastname='$lastname', email='$email', password='$password', photo='$new_photo' WHERE id='$voter_id'";
    } else {
        $query = "UPDATE voters SET firstname='$firstname', lastname='$lastname', email='$email', photo='$new_photo' WHERE id='$voter_id'";
    }

    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Profile updated successfully!'); window.location='profile.php';</script>";
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}

$conn->close();
