<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

require_once 'include/dbconnect.php';

$query = "SELECT EventID as EventId , EventName as Name  FROM `register_event` where isClosed='0' ";


$result = mysqli_query($conn,$query);
$eventlist = array();
if(mysqli_num_rows($result) > 0){
    while ($events = mysqli_fetch_assoc($result)){
       
      $eventlist[] = $events;
    } 
    $errcode = 200;
    $status = "Success";
}else{
    $errcode = 404;
    $status = "Failure";
}

echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"arrRes" => $eventlist));

mysqli_close($conn);
?>