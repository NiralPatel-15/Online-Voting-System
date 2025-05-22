<?php
include 'includes/session.php';
include 'includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['vote'])) {
    $election_id = $_POST['election_id'];
    $voter_id = $_POST['voter_id'];

    // Check if the user has already voted
    $vote_check_sql = "SELECT * FROM votes WHERE voters_id = '$voter_id' AND election_id = '$election_id'";
    $vote_check_query = $conn->query($vote_check_sql);

    if ($vote_check_query->num_rows > 0) {
        $_SESSION['message'] = "You have already voted for this election.";
        $_SESSION['message_type'] = "danger";
        header("Location: home.php");
        exit();
    }

    // Insert the vote into the database
    $stmt = $conn->prepare("INSERT INTO votes (voters_id, election_id, candidate_id) VALUES (?, ?, ?)");

    foreach ($_POST as $key => $value) {
        if (strpos($key, 'vote_') === 0) { // Only process candidate selections
            $candidate_id = intval($value);
            $stmt->bind_param("iii", $voter_id, $election_id, $candidate_id);
            $stmt->execute();
        }
    }

    $stmt->close();

    // Redirect back to home with success message
    $_SESSION['message'] = "Your vote has been successfully recorded.";
    $_SESSION['message_type'] = "success";
    header("Location: home.php");
    exit();
} else {
    $_SESSION['message'] = "Invalid request.";
    $_SESSION['message_type'] = "danger";
    header("Location: home.php");
    exit();
}
?>
