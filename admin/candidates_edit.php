<?php
include 'includes/session.php';
include 'includes/conn.php'; // Ensure database connection is included

if(isset($_POST['edit'])){
    $id = $_POST['id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $position = $_POST['position'];
    $platform = $_POST['platform'];

    // File upload handling
    if (!empty($_FILES['photo']['name'])) {
        $filename = $_FILES['photo']['name'];
        $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif']; // Allow only image formats
        $target_dir = "../images/";

        if (in_array($file_ext, $allowed_ext)) {
            $new_filename = uniqid() . "." . $file_ext; // Rename file to avoid overwriting
            move_uploaded_file($_FILES['photo']['tmp_name'], $target_dir . $new_filename);

            // Update query with photo
            $stmt = $conn->prepare("UPDATE candidates SET firstname = ?, lastname = ?, position_id = ?, platform = ?, photo = ? WHERE id = ?");
            $stmt->bind_param("ssiisi", $firstname, $lastname, $position, $platform, $new_filename, $id);
        } else {
            $_SESSION['error'] = "Invalid file type. Only JPG, JPEG, PNG, and GIF allowed.";
            header('location: candidates.php');
            exit();
        }
    } else {
        // Update query without photo
        $stmt = $conn->prepare("UPDATE candidates SET firstname = ?, lastname = ?, position_id = ?, platform = ? WHERE id = ?");
        $stmt->bind_param("ssiis", $firstname, $lastname, $position, $platform, $id);
    }

    // Execute Query
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Candidate updated successfully';
    } else {
        $_SESSION['error'] = "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    $_SESSION['error'] = 'Fill up edit form first';
}

header('location: candidates.php');
exit();
?>
