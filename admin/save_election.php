<?php
include 'includes/conn.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $sql = "INSERT INTO elections (title, start_time, end_time) VALUES ('$title', '$start_time', '$end_time')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Election added successfully!'); window.location='add_election.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
