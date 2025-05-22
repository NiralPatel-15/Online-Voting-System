<?php
	include 'includes/session.php';

	if(isset($_POST['editElection'])){
		$id = $_POST['id'];
		$title = $_POST['title'];
		$start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];

		$sql = "UPDATE elections SET title = '$title', start_time = '$start_time', end_time = '$end_time' WHERE id = '$id'";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Election updated successfully';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}
	}
	else{
		$_SESSION['error'] = 'Fill up edit form first';
	}

	header('location: election.php');

?>