<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);

require_once 'include/dbconnect.php';

if(isset($data)){
    $eventID = $data['event_id'];

    if($eventID > 0){

        $query = "SELECT a.OtherAssessmentId as AssId ,a.OtherAssementScaleName as AssName,a.ScaleValue as AssVal FROM `other_assessmentscale` a INNER JOIN event_otherassessmentscale b ON a.OtherAssessmentId=b.eas_OtherAssessId WHERE b.eas_eventID='$eventID' AND b.isActive=1 ";

    }else{  
        $query = "SELECT OtherAssessmentId as AssId ,OtherAssementScaleName as AssName,ScaleValue as AssVal FROM `other_assessmentscale` WHERE isActive=1 ";
    }

    $result = mysqli_query($conn,$query);
    $othset = array();
    if(mysqli_num_rows($result) > 0){
        while ($assesrow = mysqli_fetch_assoc($result)){
            $othset[] = $assesrow;
        } 
        $errcode = 200;
        $status = "Success";
    }else{
        $errcode = 404;
        $status = "Failure";
        $othset='';
    }
}else{
    $errcode = 404;
    $status ="Event id not found";
    $othset='';
}
echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"arrRes" => $othset));

mysqli_close($conn);
?>