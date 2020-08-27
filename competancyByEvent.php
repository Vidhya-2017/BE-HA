<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json;");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);


require_once 'include/dbconnect.php';
 
$event_id=$data['EventID'];


$query = "SELECT c.`ID` as compentancyID, c.`CompetancyName` FROM  `competancy_rating` c INNER JOIN `competancy_rating_event` ce on ce.`CompetancyID` = c.`ID` WHERE ce.`EventID` = $event_id and c.`isActive`=1";

$result = mysqli_query($conn,$query);
$competancyData = [];
    
    if(mysqli_num_rows($result) > 0){
        while ($competancyrow = mysqli_fetch_assoc($result)){
          
                $competancyData[] = $competancyrow;
            
        } 
        $errcode = 200;
        $status = "Success";
    }else{
        $errcode = 404;
        $status = "Failure";
    }

    echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"arrRes" => $competancyData));

mysqli_close($conn);
?>