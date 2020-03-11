<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json;");
$json = file_get_contents('php://input');
$data = json_decode($json,true);


require_once 'include/dbconnect.php';
 $event_ID = $data['event_id'];

$query = "select cr.EmpID,cr.EmpName,sk.Skills,cr.Expereince,cr.RelevantExperience,cr.ContactNo,DATE(re.Date) as date from candidate_event ce left join candidate_registration cr on cr.ID = ce.CanidateID left join register_event re on re.EventID = ce.EventID left join skills sk on sk.SkillId = cr.Skills where ce.EventID=$event_ID";
$result = mysqli_query($conn,$query);
$candidates = [];
    
    if(mysqli_num_rows($result) > 0){
        while ($candidaterow = mysqli_fetch_assoc($result)){
            $check_query = "select ce.ID from candidate_event ce left join candidate_registration cr on cr.ID = ce.CanidateID where cr.EmpID=".$candidaterow['EmpID']." and ce.isSelected=1 and ce.EventID != $event_ID";
            $cc_result = mysqli_query($conn,$check_query);
            if(mysqli_num_rows($cc_result) == 0){
                $candidates[] = $candidaterow;
            }
        } 
        $errcode = 200;
        $status = "Success";
    }else{
        $errcode = 500;
        $status = "Failure";
    }

    echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"arrRes" => $candidates));

mysqli_close($conn);
?>