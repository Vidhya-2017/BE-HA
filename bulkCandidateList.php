<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json;");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);


require_once 'include/dbconnect.php';

if(isset($data['event_id'])){
    $event_ID = $data['event_id'];
 
      $query = "select cr.ID,cr.EmpID,cr.EmpName,cr.EmailId, cr.ContactNo, GROUP_CONCAT(DISTINCT sk.skills) SkillName,cr.Expereince,cr.candidate_image,ce.EventID from candidate_event ce inner join candidate_registration cr on cr.ID = ce.CanidateID INNER join skills sk ON FIND_IN_SET(sk.SkillId, cr.`Skills`) where ce.EventID=$event_ID group by cr.ID";

    $result = mysqli_query($conn,$query);
    $candidates = [];
    
    if(mysqli_num_rows($result) > 0){
       
        while ($candidaterow = mysqli_fetch_assoc($result)){
          
                $candidates[] = $candidaterow;
          
        } 
        $errcode = 200;
        $status = "Success";
    }

    $noEvtCandid = "select cr.ID,cr.EmpID,cr.EmpName,cr.EmailId, cr.ContactNo,cr.Expereince,cr.candidate_image,ce.EventID,ce.CanidateID,cr.Skills, (select GROUP_CONCAT(DISTINCT sk.skills) from skills sk where FIND_IN_SET(sk.SkillId, cr.`Skills`) ) as SkillName FROM candidate_registration cr LEFT OUTER JOIN candidate_event ce on cr.ID = ce.CanidateID WHERE ce.CanidateID is NULL AND ce.EventID is NULL";
    $res_noEvtCandid = mysqli_query($conn,$noEvtCandid);
    $noEvntcandidates = [];
    if(mysqli_num_rows($res_noEvtCandid) > 0){
        while ($nocandidate_row = mysqli_fetch_assoc($res_noEvtCandid)){
          
                $noEvntcandidates[] = $nocandidate_row;
          
        } 
        $errcode = 200;
        $status = "Success";
    }

    if(empty($candidates) && empty($noEvntcandidates) ){
            $errcode = 404;
            $status = "No records found";
            $candidates="";
            $noEvntcandidates="";

        }
}else{
    $errcode = 404;
    $status = "Please mention event id";
}

echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"assignedCandid" => $candidates,"nonAssignedCan"=> $noEvntcandidates));

mysqli_close($conn);
?>