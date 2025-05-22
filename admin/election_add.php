<?php
	include 'includes/session.php';

	if(isset($_POST['addElection'])){
		$title = $_POST['title'];
		$start_time = $_POST['start_time'];
		$end_time = $_POST['end_time'];
		
		$sql = "INSERT INTO elections (title, start_time, end_time) VALUES ('$title', '$start_time', '$end_time')";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Election added successfully';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}

	}
	else{
		$_SESSION['error'] = 'Fill up add form first';
	}

	header('location: election.php');
?>