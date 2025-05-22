<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>

<body class="hold-transition skin-blue layout-top-nav">
    <div class="wrapper">

        <?php include 'includes/navbar.php'; ?>

        <div class="content-wrapper">
            <div class="container">
                <section class="content">
                    <h1 class="page-header text-center title"><b>Voting Ballot</b></h1>

                    <!-- Marquee for Active Elections -->
                    <marquee behavior="scroll" direction="left" class="marquee">
                        <?php
                        include 'includes/conn.php';
                        $marquee_sql = "SELECT * FROM elections WHERE status='active'";
                        $marquee_query = $conn->query($marquee_sql);
                        while ($marquee_row = $marquee_query->fetch_assoc()) {
                            echo "<span class='marquee-text'>" . htmlspecialchars($marquee_row['title']) . " is Active! </span> | ";
                        }
                        ?>
                    </marquee>

                    <!-- Dropdown to Select Election -->
                    <div class="text-center">
                        <form id="electionForm" method="GET" action="fetch_ballot.php">
                            <select name="election_id" class="form-control" required>
                                <option value="">Select Election Type</option>
                                <?php
                                $etype_sql = "SELECT * FROM elections WHERE status='active'";
                                $etype_query = $conn->query($etype_sql);
                                while ($etype_row = $etype_query->fetch_assoc()) {
                                    echo "<option value='" . $etype_row['id'] . "'>" . htmlspecialchars($etype_row['title']) . "</option>";
                                }
                                ?>
                            </select>
                            <br>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>

                </section>
            </div>
        </div>

        <?php include 'includes/footer.php'; ?>
    </div>

    <?php include 'includes/scripts.php'; ?>

</body>

</html>
