<?php
include 'includes/session.php';

if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $election_id = $_POST['election_id']; // Get election ID from the form
    $description = $_POST['description'];
    $max_vote = $_POST['max_vote'];

    // Update position with election_id
    $sql = "UPDATE positions SET 
                election_id = '$election_id', 
                description = '$description', 
                max_vote = '$max_vote' 
            WHERE id = '$id'";

    if ($conn->query($sql)) {
        $_SESSION['success'] = 'Position updated successfully';
    } else {
        $_SESSION['error'] = $conn->error;
    }
} else {
    $_SESSION['error'] = 'Fill up edit form first';
}

header('location: positions.php');
exit();
?>
