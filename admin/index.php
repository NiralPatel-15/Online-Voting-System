<?php
session_start();
if(isset($_SESSION['admin'])){
    header('location:home.php');
}
?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <b>Voting System</b>
    </div>

    <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <form action="login.php" method="POST">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="username" placeholder="Username" required>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block" name="login">
                    <i class="fa fa-sign-in"></i> Sign In
                </button>
            </div>
        </form>

        <!-- Buttons aligned in a straight line with equal size -->
        <div class="form-group">
            <a href="register.php" class="btn btn-success btn-block">Register</a>
        </div>
        <div class="form-group">
            <a href="otp_verification.php" class="btn btn-warning btn-block">Otp?</a>
        </div>
    </div>

    <?php
    if(isset($_SESSION['error'])){
        echo "<div class='callout callout-danger text-center mt20'>
                <p>".$_SESSION['error']."</p> 
              </div>";
        unset($_SESSION['error']);
    }
    ?>
</div>
<?php include 'includes/scripts.php' ?>
</body>
</html>
