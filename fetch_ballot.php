<?php
include 'includes/session.php';
include 'includes/header.php';
include 'includes/conn.php';

if (isset($_GET['election_id'])) {
    $election_id = $_GET['election_id'];

    // Fetch election details
    $election_sql = "SELECT * FROM elections WHERE id = '$election_id'";
    $election_query = $conn->query($election_sql);

    if ($election_query->num_rows > 0) {
        $election = $election_query->fetch_assoc();
        ?>

        <body class="hold-transition skin-blue layout-top-nav">
            <div class="wrapper">
                <?php include 'includes/navbar.php'; ?>

                <div class="content-wrapper">
                    <div class="container">
                        <section class="content">
                            <h1 class="page-header text-center title"><b>Voting Ballot</b></h1>

                            <style>
                                /* Election Info Styling */
                                .election-info {
                                    text-align: center;
                                    background-color: #f8f9fa;
                                    padding: 15px;
                                    border-radius: 8px;
                                    margin-bottom: 20px;
                                    border: 1px solid #ddd;
                                }
                                .election-info h2 {
                                    font-weight: bold;
                                    color: #007bff;
                                }
                                .election-info p {
                                    font-size: 16px;
                                    color: #333;
                                    margin: 5px 0;
                                }

                                /* Candidate Card Styling */
                                .candidate {
                                    display: flex;
                                    align-items: center;
                                    background: #fff;
                                    padding: 10px;
                                    border-radius: 8px;
                                    border: 1px solid #ddd;
                                    margin-bottom: 10px;
                                    transition: 0.3s;
                                }
                                .candidate:hover {
                                    background: #f1f1f1;
                                    transform: scale(1.02);
                                }
                                .candidate input {
                                    margin-right: 10px;
                                }
                                .candidate img {
                                    border-radius: 50%;
                                    margin-right: 10px;
                                    border: 2px solid #007bff;
                                }
                                .candidate span {
                                    font-size: 18px;
                                    font-weight: bold;
                                    color: #333;
                                }

                                /* Submit Button */
                                .submit-btn {
                                    display: block;
                                    width: 100%;
                                    padding: 10px;
                                    font-size: 18px;
                                    border-radius: 5px;
                                    background: #007bff;
                                    color: white;
                                    border: none;
                                    transition: 0.3s;
                                }
                                .submit-btn:hover {
                                    background: #0056b3;
                                }

                                /* Voting Section */
                                .position-container {
                                    background: #fff;
                                    padding: 15px;
                                    border-radius: 10px;
                                    border: 1px solid #ddd;
                                    margin-bottom: 20px;
                                }
                                .position-container h3 {
                                    color: #007bff;
                                    font-weight: bold;
                                }
                            </style>

                            <div class="election-info">
                                <h2><?php echo htmlspecialchars($election['title']); ?></h2>
                                <p><b>Start Time:</b> <?php echo date('M d, Y h:i A', strtotime($election['start_time'])); ?></p>
                                <p><b>End Time:</b> <?php echo date('M d, Y h:i A', strtotime($election['end_time'])); ?></p>
                            </div>

                            <?php
                            // Check if the voter has already voted
                            $voter_id = $_SESSION['voter'];
                            $vote_check_sql = "SELECT * FROM votes WHERE voters_id = '$voter_id' AND election_id = '$election_id'";
                            $vquery = $conn->query($vote_check_sql);

                            if ($vquery && $vquery->num_rows > 0) {
                                echo "<h3 class='text-center text-danger'>You have already voted for this election.</h3>";
                                echo "<div class='text-center'><a href='#view' data-toggle='modal' class='btn btn-primary'>View Ballot</a></div>";
                            } else {
                                ?>
                                <form method="POST" id="ballotForm" action="submit_vote.php">
                                    <input type="hidden" name="election_id" value="<?php echo $election_id; ?>">
                                    <input type="hidden" name="voter_id" value="<?php echo $voter_id; ?>">

                                    <?php
                                    // Fetch positions related to this election
                                    $pos_sql = "SELECT * FROM positions WHERE election_id = '$election_id' ORDER BY priority ASC";
                                    $pos_query = $conn->query($pos_sql);

                                    while ($row = $pos_query->fetch_assoc()) {
                                        $pos_id = $row['id'];
                                        ?>
                                        <div class="position-container">
                                            <h3><?php echo htmlspecialchars($row['description']); ?></h3>
                                            <p>Select up to <?php echo $row['max_vote']; ?> candidate(s)</p>

                                            <?php
                                            // Fetch candidates for this election & position
                                            $cand_sql = "SELECT * FROM candidates WHERE position_id='$pos_id' AND election_id = '$election_id'";
                                            $cand_query = $conn->query($cand_sql);

                                            while ($crow = $cand_query->fetch_assoc()) {
                                                $image = (!empty($crow['photo'])) ? 'images/' . $crow['photo'] : 'images/profile.jpg';
                                                ?>
                                                <div class="candidate">
                                                    <input type="radio" name="vote_<?php echo $pos_id; ?>" value="<?php echo $crow['id']; ?>" required>
                                                    <img src="<?php echo $image; ?>" height="80px" width="80px">
                                                    <span><?php echo htmlspecialchars($crow['firstname'] . " " . $crow['lastname']); ?></span>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <div class="text-center">
                                        <button type="submit" class="submit-btn" name="vote">Submit Vote</button>
                                    </div>
                                </form>
                                <?php
                            }
                            ?>
                        </section>
                    </div>
                </div>

                <?php include 'includes/footer.php'; ?>
            </div>

            <?php include 'includes/scripts.php'; ?>
        </body>
        </html>

        <?php
    } else {
        echo "<div class='alert alert-danger'>Election not found.</div>";
    }
} else {
    echo "<div class='alert alert-danger'>No election selected.</div>";
}
?>
