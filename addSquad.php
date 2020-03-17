<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$json = file_get_contents('php://input');
$data = json_decode($json,true);

require_once 'include/dbconnect.php';

	$squad_name	= $data['SquadName'];
	$event_id	= $data['EventID'];
	$created_date = $data['CreatedDate'];
	$created_by	= $data['CreatedBy'];

	
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