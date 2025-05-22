<?php
include 'includes/session.php';
include 'includes/conn.php'; // Ensure database connection is included

if (isset($_POST['add'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $position = $_POST['position'];

    // Ensure election_id is set correctly
    if (isset($_POST['election']) && !empty($_POST['election'])) {
        $election_id = $_POST['election'];
    } else {
        $_SESSION['error'] = "Election ID is missing!";
        header('location: candidates.php');
        exit();
    }

    $platform = $_POST['platform'];

    // File upload handling
    $filename = $_FILES['photo']['name'];
    $target_dir = "../images/";

    if (!empty($filename)) {
        $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_ext, $allowed_ext)) {
            $new_filename = uniqid() . "." . $file_ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], $target_dir . $new_filename);
        } else {
            $_SESSION['error'] = "Invalid file type. Only JPG, JPEG, PNG, and GIF allowed.";
            header('location: candidates.php');
            exit();
        }
    } else {
        $new_filename = ""; 
    }

    // Use Prepared Statement
    $stmt = $conn->prepare("INSERT INTO candidates (election_id, position_id, firstname, lastname, photo, platform) 
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissss", $election_id, $position, $firstname, $lastname, $new_filename, $platform);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Candidate added successfully';
    } else {
        $_SESSION['error'] = "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    $_SESSION['error'] = 'Fill up add form first';
}

header('location: candidates.php');
exit();
?>