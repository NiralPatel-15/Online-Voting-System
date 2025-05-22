<?php
include 'includes/session.php';

if (isset($_POST['delete'])) {
    $id = $_POST['id'];

    // Check if there are candidates linked to this position
    $check_sql = "SELECT COUNT(*) AS count FROM candidates WHERE position_id = '$id'";
    $check_query = $conn->query($check_sql);
    $check_result = $check_query->fetch_assoc();

    if ($check_result['count'] > 0) {
        $_SESSION['error'] = 'Cannot delete position. Candidates are assigned to it.';
    } else {
        // Proceed with deletion
        $sql = "DELETE FROM positions WHERE id = '$id'";
        if ($conn->query($sql)) {
            $_SESSION['success'] = 'Position deleted successfully';
        } else {
            $_SESSION['error'] = $conn->error;
        }
    }
} else {
    $_SESSION['error'] = 'Select a position to delete first';
}

header('location: positions.php');
exit();
?>
