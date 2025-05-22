<?php
include 'includes/session.php';
include 'includes/header.php';

if (!isset($_SESSION['voter']) || $_SESSION['voter'] === null) {
    header("Location: login.php"); // Redirect if not logged in
    exit();
}

$voter_id = $_SESSION['voter'];
$conn = new mysqli("localhost", "root", "", "votingsystem");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch voter details safely
$query = "SELECT * FROM voters WHERE id='$voter_id'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    $row = [
        'firstname' => '',
        'lastname' => '',
        'email' => '',
        'password' => '',
        'photo' => 'default.png'
    ];
}
?>

<body class="hold-transition skin-blue layout-top-nav">
    <div class="wrapper">
        <?php include 'includes/navbar.php'; ?>
        <div class="content-wrapper">
            <div class="container">
                <section class="content">
                    <div class="card shadow p-4">
                        <h1 class="page-header text-center title"><b>Voter Profile</b></h1>
                        <div class="row justify-content-center align-items-center">
                            <div class="col-md-6">
                                <form method="POST" action="update_profile.php" enctype="multipart/form-data" class="p-3 border rounded bg-light">
                                    <div class="form-group">
                                        <label>First Name:</label>
                                        <input type="text" class="form-control" name="firstname" value="<?php echo htmlspecialchars($row['firstname']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Last Name:</label>
                                        <input type="text" class="form-control" name="lastname" value="<?php echo htmlspecialchars($row['lastname']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Email:</label>
                                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Password:</label>
                                        <input type="password" class="form-control" name="password" placeholder="Enter new password (optional)">
                                    </div>
                                    <div class="form-group">
                                        <label>Change Photo:</label>
                                        <input type="file" class="form-control" name="photo">
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary px-4" name="update">Update Profile</button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-4 text-center">
                                <img src="<?php echo (!empty($row['photo']) && file_exists('images/' . $row['photo'])) ? 'images/' . $row['photo'] : 'images/default.png'; ?>"
                                    alt="Profile Photo" class="img-thumbnail rounded-circle mb-3" width="200" height="200">
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <?php include 'includes/footer.php'; ?>
    </div>
    <?php include 'includes/scripts.php'; ?>
</body>

</html>