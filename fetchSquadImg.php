<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);

require_once 'include/dbconnect.php';

	$squad_id	= $data['SquadID'];
    $event_id	= $data['EventID'];
    
    if($squad_id==''){
        $selQry = "SELECT ID,SquadName,squad_team_img FROM squad WHERE EventID ='$event_id' ";
    }else{
        $selQry = "SELECT ID,SquadName,squad_team_img FROM squad WHERE EventID ='$event_id' and ID ='$squad_id'";
    }
	
	$squadData = array();
	$result = mysqli_query($conn,$selQry);
	if(mysqli_num_rows($result)>0){
        while ($squadRow = mysqli_fetch_assoc($result)){
            $squadData[] = $squadRow;
        } 
        $errcode = 200;
		$status = "Success";
        
	}else{
		$errcode = 404;
		$status = "No Result";
		$squadData='';
	}	
	
	echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"squadData"=>$squadData));

mysqli_close($conn);
?>