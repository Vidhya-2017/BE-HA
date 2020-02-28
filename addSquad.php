<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once 'include/dbconnect.php';

	$squad_name	= $_POST['SquadName'];
	$event_id	= $_POST['EventID'];
	$created_date = $_POST['CreatedDate'];
	$created_by	= $_POST['CreatedBy'];

	
	$query = "INSERT INTO squad (SquadName, EventID, CreatedDate, CreatedBy) VALUES ('$squad_name', $event_id, '$created_date', $created_by)";
	
	$result = mysqli_query($conn,$query);

	if($result == 1){
		$errcode = 200;
		$status = "Success";
	}else{
		$errcode = 500;
		$status = "Failure";
	}
		
	
	echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status));

mysqli_close($conn);
?>