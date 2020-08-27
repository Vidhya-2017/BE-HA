<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json;");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);


require_once 'include/dbconnect.php';
 

$query = "SELECT `ID`, `CompetancyName` FROM `competancy_rating` WHERE `isActive`=1";

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