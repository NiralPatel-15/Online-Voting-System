<?php
	include 'includes/session.php';

	if(isset($_POST['deleteElection'])){
		$id = $_POST['id'];
		$sql = "DELETE FROM elections WHERE id = '$id'";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Election deleted successfully';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}
	}
	else{
		$_SESSION['error'] = 'Select item to delete first';
	}

	header('location: election.php');
	
?>