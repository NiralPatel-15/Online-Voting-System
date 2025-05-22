<?php
include 'includes/session.php';
include 'includes/conn.php';

if(isset($_POST['id'])){
    $id = $_POST['id'];

    // Secure deletion using prepared statements
    $stmt = $conn->prepare("DELETE FROM voters WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Voter deleted successfully!";
    } else {
        $_SESSION['error'] = "Something went wrong while deleting the voter.";
    }

    $stmt->close();
    $conn->close();
} else {
    $_SESSION['error'] = "Select a voter to delete first.";
}

header("Location: voters.php");
exit();
?>
