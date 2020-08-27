<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);

require_once 'include/dbconnect.php';
    if(isset($data['panel_id']) && isset($data['panel_event_id']) ){
        $panel_id         =   $data['panel_id'];
        $panel_event_id   =   $data['panel_event_id'];
        $query            =   "UPDATE event_panel_list SET isActive=0  WHERE pl_id = '$panel_id' AND pl_eventId = '$panel_event_id' ";
        $result           =   mysqli_query($conn,$query);
        if($result){
            $errcode    =   200;
            $status     =   "Success";
        }                
        else{
            $errcode    =   404;
            $status     =   "Failure";
        }
    } else {
        $errcode    =   404;
        $status     =   "There is no panel or event ID";
    }
    echo $result    =   json_encode(array("errCode"=>$errcode,"status"=>$status));
mysqli_close($conn);
?>