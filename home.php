<?php 
include 'includes/session.php'; 
include 'includes/header.php'; 
include 'includes/conn.php'; // Ensure database connection is included

// Set timezone to match the election timezone
date_default_timezone_set('Asia/Kolkata'); // Replace with your actual timezone

// Prevent caching issues
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

?>

<body class="hold-transition skin-blue layout-top-nav">
    <div class="wrapper">
        <?php include 'includes/navbar.php'; ?>

        <div class="content-wrapper">
            <div class="container">
                <section class="content">
                    <h1 class="page-header text-center title"><b>User Dashboard</b></h1>

                    <?php
                    // Fetch election end time
                    $sql = "SELECT end_time FROM elections WHERE id = 8"; // Modify election ID accordingly
                    $query = $conn->query($sql);
                    
                    if ($query->num_rows > 0) {
                        $row = $query->fetch_assoc();
                        $end_time = strtotime($row['end_time']); // Convert to timestamp
                    } else {
                        $end_time = 0; // Default value if no election is found
                    }

                    $current_time = time();
                    ?>

                    <div class="row">
                        <!-- Voting Section -->
                        <div class="col-md-4">
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                    <h3>Vote Now</h3>
                                    <p>Cast your vote in the ongoing election</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-pencil-square-o"></i>
                                </div>
                                <a href="vote.php" class="small-box-footer">Go to Voting <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <!-- Results Section -->
                        <?php if ($current_time >= $end_time && $end_time != 0): ?>
                            <div class="col-md-4">
                                <div class="small-box bg-green">
                                    <div class="inner">
                                        <h3>Results</h3>
                                        <p>View election results</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-bar-chart"></i>
                                    </div>
                                    <a href="results.php" class="small-box-footer">View Results <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="col-md-4">
                                <div class="small-box bg-gray">
                                    <div class="inner">
                                        <h3>Results</h3>
                                        <p>Results will be available after the 04:00 p.m</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                    <a href="#" class="small-box-footer disabled">Results Not Available</a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Profile Section -->
                        <div class="col-md-4">
                            <div class="small-box bg-yellow">
                                <div class="inner">
                                    <h3>Profile</h3>
                                    <p>Update your account details</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <a href="profile.php" class="small-box-footer">Update Profile <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Voter Turnout Chart -->
                    <?php
                    // Fetch total voters
                    $sqlTotalVoters = "SELECT COUNT(*) AS total FROM voters";
                    $queryTotalVoters = $conn->query($sqlTotalVoters);
                    $rowTotalVoters = $queryTotalVoters->fetch_assoc();
                    $totalVoters = $rowTotalVoters['total'];

                    // Fetch voters who voted
                    $sqlVoted = "SELECT COUNT(*) AS voted FROM votes"; // Adjust 'votes' table as per DB structure
                    $queryVoted = $conn->query($sqlVoted);
                    $rowVoted = $queryVoted->fetch_assoc();
                    $voted = $rowVoted['voted'];

                    // Calculate non-voters
                    $notVoted = $totalVoters - $voted;
                    ?>

                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Voter Turnout</h3>
                                </div>
                                <div class="box-body">
                                    <canvas id="voterChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <a href="logout.php" class="btn btn-danger btn-lg"><i class="fa fa-sign-out"></i> Logout</a>
                    </div>

                </section>
            </div>
        </div>

        <?php include 'includes/footer.php'; ?>
    </div>
    <?php include 'includes/scripts.php'; ?>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById("voterChart").getContext("2d");
            var voterChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ["Voted", "Not Voted"],
                    datasets: [{
                        data: [<?php echo $voted; ?>, <?php echo $notVoted; ?>],
                        backgroundColor: ["#28a745", "#dc3545"]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        });
    </script>
</body>
</html>
