<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);

require_once 'include/dbconnect.php';
if(isset($data)){
		$eventid = $data['eventID'];
		$userid = $data['userID'];
		$query = "SELECT pl_userid FROM event_panel_list WHERE pl_userid ='$userid' and isActive=1 and pl_eventId ='$eventid' ";
		$result = mysqli_query($conn,$query);
		
		if(mysqli_num_rows($result) > 0){
            $ispanel =true;
            $errcode = 200;
            $status = "Success";
        }else{
            $query = "SELECT `organizer_userid` FROM `event_organizer` WHERE `organizer_userid` = '$userid' and `orgnizer_eventid` = '$eventid'";
            $result = mysqli_query($conn,$query);
            
            if(mysqli_num_rows($result) > 0){
                $ispanel =false;
                $errcode = 200;
                $status = "Success";
            } else {
                $ispanel ='';
                $errcode = 200;
                $status = "Success";
            }
        }
}else{
    	$errcode = 404;
        $status = "Invalid Request";
        $ispanel ='';

}
 

echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"ispanel" => $ispanel));
mysqli_close($conn);

?>