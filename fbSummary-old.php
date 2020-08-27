<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json;");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");


$json = file_get_contents('php://input');
$data = json_decode($json,true);

require_once 'include/dbconnect.php'; 
$event_ID = $data['event_id'];
//$event_ID = '8';
if($event_ID !='')
{
    $selcandi = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS totalcandidate FROM candidate_event WHERE EventID = '$event_ID' AND isActive=1"));
    $countTotalCandit = $selcandi['totalcandidate'];

    $selFAQry = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) finalCompleted FROM squad_feedback a INNER JOIN squad_feedback_skill b ON a.sid =b.squad_fb_id  WHERE  a.eventId ='$event_ID' AND sprintLevel ='Final Assessment' AND a.isActive=1" ));
    $countFACandit = $selFAQry['finalCompleted'];
    
    $InprocessCandidate = $countTotalCandit - $countFACandit ;
    
    $seledCandidate = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) selectedCandidate FROM squad_feedback a INNER JOIN squad_feedback_skill b ON a.sid =b.squad_fb_id  WHERE  a.eventId ='$event_ID' AND sprintLevel ='Final Assessment' AND sq_final_status='selected' AND a.isActive=1" ));
    $seledCandidateCnt = $seledCandidate['selectedCandidate'];
    
    $srejCandidate = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) rejectedCandidate FROM squad_feedback a INNER JOIN squad_feedback_skill b ON a.sid =b.squad_fb_id  WHERE  a.eventId ='$event_ID' AND sprintLevel ='Final Assessment' AND sq_final_status='rejected' AND a.isActive=1" ));
    $rejCandidateCnt = $srejCandidate['rejectedCandidate'];
    
    $holdCandidate = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) holdCandidate FROM squad_feedback a INNER JOIN squad_feedback_skill b ON a.sid =b.squad_fb_id  WHERE  a.eventId ='$event_ID' AND sprintLevel ='Final Assessment' AND sq_final_status='onhold' AND a.isActive=1" ));
    $holdCandidateCnt = $holdCandidate['holdCandidate'];

    $resultarray = array ("TotalEmp"=>$countTotalCandit,"FinalAssdEmp"=> $countFACandit,                   "InprocessEmp"=>$InprocessCandidate,"SelectedEmp"=>$seledCandidateCnt,                   "RejectedEmp"=>$rejCandidateCnt,"HoldEmp"=>$holdCandidateCnt);

    $errcode = 200;
    $status = "Success";

}else{
    $errcode = 404;
    $status = "Event id should not empty";
    $resultarray="";
}
echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"resultArr"=>$resultarray));
mysqli_close($conn);

?>