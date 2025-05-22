<?php
include 'includes/session.php';
include 'includes/conn.php'; // Ensure database connection is included

if (isset($_POST['add'])) {
    $election_id = $_POST['election_id']; // Get election ID from the form
    $description = $_POST['description'];
    $max_vote = $_POST['max_vote'];

    // Get the highest priority
    $sql = "SELECT * FROM positions ORDER BY priority DESC LIMIT 1";
    $query = $conn->query($sql);
    $row = $query->fetch_assoc();
    $priority = isset($row['priority']) ? $row['priority'] + 1 : 1;

    // Insert new position with election_id
    $sql = "INSERT INTO positions (election_id, description, max_vote, priority) 
            VALUES ('$election_id', '$description', '$max_vote', '$priority')";
            
    if ($conn->query($sql)) {
        $_SESSION['success'] = 'Position added successfully';
    } else {
        $_SESSION['error'] = $conn->error;
    }
}

// Add Election Function
if (isset($_POST['addElection'])) {
    $title = $_POST['title'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Insert new election
    $sql = "INSERT INTO elections (title, start_time, end_time) VALUES ('$title', '$start_time', '$end_time')";
    
    if ($conn->query($sql)) {
        $_SESSION['success'] = 'Election added successfully';
    } else {
        $_SESSION['error'] = $conn->error;
    }
}

// Redirect back to positions page
header('location: positions.php');
exit();
?>
