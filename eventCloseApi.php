<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);

require_once 'include/dbconnect.php';
    if(isset($data['event_id'])){
        $event_id       =   $data['event_id'];
        $sql_query = "SELECT isClosed FROM register_event WHERE isclosed = 1 AND EventID = '$event_id'";
        $response =   mysqli_query($conn,$sql_query);
        
        if(mysqli_num_rows($response) > 0){
            $errcode = 404;
            $status = "Event is already closed";
        } else {
            $query          =   "UPDATE register_event SET    `isClosed` = '1'  WHERE EventID = '$event_id' "; 
            $result         =   mysqli_query($conn,$query);
            if($result){
                $errcode    =   200;
                $status     =   "Success";
            }                
            else{
                $errcode    =   404;
                $status     =   "Failure";
            }                                                           
        }
    } else {
        $errcode    =   404;
        $status     =   "There is no Event Id";
    }
    echo $result    =   json_encode(array("errCode"=>$errcode,"status"=>$status));
mysqli_close($conn);
?>