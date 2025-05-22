<?php 
	include 'includes/session.php';

	if(isset($_POST['id'])){
		$id = $_POST['id'];
		$sql = "SELECT *, candidates.id AS canid, candidates.election_id AS election_id 
		        FROM candidates 
		        LEFT JOIN positions ON positions.id = candidates.position_id 
		        LEFT JOIN elections ON elections.id = candidates.election_id
		        WHERE candidates.id = '$id'";
		$query = $conn->query($sql);
		$row = $query->fetch_assoc();

		echo json_encode($row);
	}
?>
