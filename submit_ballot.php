<?php
include 'includes/session.php';
include 'includes/conn.php';

if (isset($_POST['vote'])) {
    if (count($_POST) == 1) {
        $_SESSION['error'][] = 'Please vote for at least one candidate.';
    } else {
        $_SESSION['post'] = $_POST;
        $voter_id = $_SESSION['voter'];  // Get voter ID
        $election_id = $_POST['election_id']; // Get election ID

        // Check if voter has already voted in this election
        $check_vote_sql = "SELECT * FROM votes WHERE voters_id='$voter_id' AND election_id='$election_id'";
        $check_vote_query = $conn->query($check_vote_sql);

        if ($check_vote_query->num_rows > 0) {
            $_SESSION['error'][] = 'You have already voted in this election!';
            header('location: home.php');
            exit();
        }

        $sql = "SELECT * FROM positions WHERE election_id='$election_id'";
        $query = $conn->query($sql);
        $error = false;
        $sql_array = array();

        while ($row = $query->fetch_assoc()) {
            $pos_id = $row['id'];
            if (isset($_POST['vote_'.$pos_id])) {
                $selected_candidates = $_POST['vote_'.$pos_id];

                if (!is_array($selected_candidates)) {
                    $selected_candidates = [$selected_candidates]; // Ensure it's an array
                }

                if (count($selected_candidates) > $row['max_vote']) {
                    $error = true;
                    $_SESSION['error'][] = 'You can only choose ' . $row['max_vote'] . ' candidates for ' . $row['description'];
                } else {
                    foreach ($selected_candidates as $candidate_id) {
                        $sql_array[] = "INSERT INTO votes (voters_id, candidate_id, position_id, election_id) 
                                        VALUES ('$voter_id', '$candidate_id', '$pos_id', '$election_id')";
                    }
                }
            }
        }

        if (!$error) {
            $conn->begin_transaction();
            try {
                foreach ($sql_array as $sql_row) {
                    $conn->query($sql_row);
                }
                $conn->commit();
                unset($_SESSION['post']);
                $_SESSION['success'] = 'Ballot Submitted Successfully!';
                header('location: submit_vote.php'); // ✅ Redirect to submit_vote.php after voting
                exit();
            } catch (Exception $e) {
                $conn->rollback();
                $_SESSION['error'][] = 'Vote submission failed! ' . $e->getMessage();
                header('location: home.php');
                exit();
            }
        }
    }
} else {
    $_SESSION['error'][] = 'Select candidates to vote first!';
}

header('location: home.php');
exit();
?>
