<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json;");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);


require_once 'include/dbconnect.php';
 $event_ID = $data['event_id'];
 
 if(isset($data['emp_name'])){
    $emp_name = $data['emp_name'];

    if($emp_name){
        $condtion = "and cr.EmpName LIKE '%$emp_name%'";
    }else{
        $condtion ='';
    }
}else{
        $condtion ='';
}

/* $query = "select cr.ID,cr.EmpID,cr.EmpName, GROUP_CONCAT(DISTINCT sk.skills) SkillName,cr.Expereince from
  candidate_event ce inner join candidate_registration cr on cr.ID = ce.CanidateID INNER join skills sk 
  ON FIND_IN_SET(sk.SkillId, cr.`Skills`) where ce.EventID=$event_ID $condtion group by cr.ID";

$query = "select cr.ID,cr.EmpID,cr.EmpName, GROUP_CONCAT(DISTINCT sk.skills) SkillName,cr.Expereince from candidate_event ce
inner join candidate_registration cr on cr.ID = ce.CanidateID
 INNER join skills sk  ON FIND_IN_SET(sk.SkillId, cr.`Skills`)
 left outer join squad_candidates sqc on  sqc.CandidateID =  ce.CanidateID
 where ce.EventID=$event_ID $condtion and sqc.CandidateID IS NULL group by cr.ID";*/

 
$query = "select cr.ID,cr.EmpID,cr.EmpName,sq.SquadName, GROUP_CONCAT(DISTINCT sk.skills) SkillName,cr.Expereince from
candidate_event ce 
inner join candidate_registration cr on cr.ID = ce.CanidateID 
INNER join skills sk 
ON FIND_IN_SET(sk.SkillId, cr.`Skills`) 
left outer join squad_candidates sqc on  sqc.CandidateID =  ce.CanidateID
left outer join squad sq on  sq.ID =  sqc.SquadID
where ce.EventID=$event_ID $condtion group by cr.ID";

$result = mysqli_query($conn,$query);
$candidates = [];
    
    if(mysqli_num_rows($result) > 0){
        while ($candidaterow = mysqli_fetch_assoc($result)){
            // $check_query = "select ce.ID from candidate_event ce left join candidate_registration cr on cr.ID = ce.CanidateID where cr.EmpID=".$candidaterow['EmpID']." and ce.isSelected=1 and ce.EventID != $event_ID";
            // $cc_result = mysqli_query($conn,$check_query);
            // if(mysqli_num_rows($cc_result) == 0){
                $candidates[] = $candidaterow;
            // }
        } 
        $errcode = 200;
        $status = "Success";
    }else{
        $errcode = 404;
        $status = "Failure";
    }

    echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"arrRes" => $candidates));

mysqli_close($conn);
?>