<?php
    session_start();
    if(isset($_SESSION['admin'])){
        header('location: admin/home.php');
    }

    if(isset($_SESSION['voter'])){
        header('location: home.php');
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
                <input type="text" class="form-control" name="voter" placeholder="Voter's ID" required>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat" name="login">
                        <i class="fa fa-sign-in"></i> Sign In
                    </button>
                </div>
                <div class="col-xs-4">
                    <a href="register.php" class="btn btn-success btn-block btn-flat">
                        <i class="fa fa-user-plus"></i> Register
                    </a>
                </div>
                <div class="col-xs-4">
                    <a href="otp_verification.php" class="btn btn-warning btn-block btn-flat">
                        <i class="fa fa-key"></i> Otp ?
                    </a>
                </div>
            </div>
        </form>
    </div>

    <?php
        if(isset($_SESSION['error'])){
            echo "
                <div class='callout callout-danger text-center mt20'>
                    <p>".$_SESSION['error']."</p> 
                </div>
            ";
            unset($_SESSION['error']);
        }
    ?>
</div>

<?php include 'includes/scripts.php' ?>
</body>
</html>
