<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json;");
$json = file_get_contents('php://input');
$data = json_decode($json,true);

require_once 'include/dbconnect.php'; 
$event_ID = $data['event_id'];
 $candidate_ID = $data['candidate_id'];
 // $candidate_ID = 2;
// $event_ID = 2;

/**HACKATHON DETAILS LIST :: START **/
$query  =   "Select A.EventName,A.EventID,A.Duration,A.Location,A.Date,A.Skills as SkillsId,C.Duration,group_concat(b.Skills) skill_name,D.ClientName FROM register_event as A, skills as B, duration as C,client as D WHERE A.Client=D.ClientId  AND A.EventID='$event_ID' AND A.Duration=C.DurationID and FIND_IN_SET( B.SkillId, A.Skills)";
$result =   mysqli_query($conn,$query);
$hackathon_details = [];
    
if(mysqli_num_rows($result) > 0){
    while ($hackathon_detailsrow = mysqli_fetch_assoc($result)){
        
            $hackathon_details[] = $hackathon_detailsrow;
    } 
    $errcode = 200;
    $status = "Success";
}else{
    $errcode = 404;
    $status = "Failure";
}
/**HACKATHON DETAILS LIST :: END **/


/**CANDIDATE FEEDBACK DETAILS :: START **/
$candidate_query="SELECT  A.CanidateID,A.EventID, B.EmpName,B.EmailId,B.ContactNo,B.Expereince,B.RelevantExperience,B.candidate_image FROM  candidate_event as A, candidate_registration as B WHERE  A.EventID='$event_ID' AND B.ID=$candidate_ID AND A.CanidateID=B.ID" ;
$candidate_list_result =   mysqli_query($conn,$candidate_query);
$candidate_lists = [];
    
if(mysqli_num_rows($candidate_list_result) > 0){
	
    while ($candidate_listrow = mysqli_fetch_array($candidate_list_result)){
       
           // $candidate_lists[] = $candidate_listrow;
           $candidate_list = [];
           $candidate_list['CanidateID']        = $candidate_listrow['CanidateID'];
           $candidate_list['EventID']           = $candidate_listrow['EventID']; 
           $candidate_list['EmpName']           = $candidate_listrow['EmpName']; 
           $candidate_list['EmailId']           = $candidate_listrow['EmailId']; 
           $candidate_list['ContactNo']         = $candidate_listrow['ContactNo']; 
           $candidate_list['Expereince']        = $candidate_listrow['Expereince']; 
           $candidate_list['RelevantExperience'] = $candidate_listrow['RelevantExperience'];
           $candidate_list['candidate_image'] = $candidate_listrow['candidate_image'];

           $squad_feedback_query  = "SELECT A.sid as sids,A.sprintLevel,A.feedbackTxt,A.sq_final_status,A.createdBy FROM squad_feedback as A WHERE  A.eventId='$event_ID' AND A.candidate_id = '".$candidate_listrow['CanidateID']."' ";
           $squad_feedback_list_result =   mysqli_query($conn,$squad_feedback_query);
           $candidate_list_feedback = [];
           $squad_feedback_lists=[];
            if(mysqli_num_rows($squad_feedback_list_result) > 0){
                while ($candidate_listsfbrow = mysqli_fetch_array($squad_feedback_list_result)){
                    //$squad_feedback_lists[] = $candidate_listsfbrow;
                         $squad_feedback_list=[];
                         $squad_feedback_list['label']=$candidate_listsfbrow['sprintLevel'];
                         $squad_feedback_list['comments']=$candidate_listsfbrow['feedbackTxt'];
                         $squad_feedback_list['finalStatus']=$candidate_listsfbrow['sq_final_status'];
                        
                    if($candidate_listsfbrow['createdBy'] >0){
                        $userListRes = mysqli_query($conn,"SELECT user_id,first_name,last_name,email,contact_no FROM user_login WHERE user_id='".$candidate_listsfbrow['createdBy']."'");
                            if(mysqli_num_rows($userListRes) > 0){
                               $userDetbrow = mysqli_fetch_assoc($userListRes);
                               $userSet[]= $userDetbrow;
                            
                               $squad_feedback_list['PanelDetail'] = $userSet;
                               unset($userSet);
                            }
                                 
                    }

                         $qruAss = "SELECT os.OtherAssementScaleName as AssName,sq.assessment_value as AssVal FROM squad_feedback_assesment_skill sq INNER JOIN other_assessmentscale os ON sq.other_assessment_scale_id = os.OtherAssessmentId WHERE sq.squad_fb_id ='".$candidate_listsfbrow['sids']."'";
                        $sassResult =   mysqli_query($conn,$qruAss);
                        $othskill=[];
                        $othskils=[];
                          while ($canAssbrow = mysqli_fetch_array($sassResult)){
                              $assesName =$canAssbrow['AssName'];
                              $assesValue = $canAssbrow['AssVal'];    
                               $othskils = array("ParamName"=>$assesName,"ParamValue"=>$assesValue);  
                                array_push($othskill,$othskils); 
                          }
                        
                         $squad_feedback_list['AssesmentParam']= $othskill; 
 
                    array_push($squad_feedback_lists,$squad_feedback_list);
                }
            
            }
            $candidate_list['feedback'] = $squad_feedback_lists;           
            array_push($candidate_lists,$candidate_list);
            unset($userSet);
    } 
   
    $errcode1 = 200;
    $status1 = "Success";
}else{
    $errcode1 = 404;
    $status1 = "Failure";
}
/**CANDIDATE FEEDBACK DETAILS :: END **/



if ($errcode=='200' || $errcode1=='200') {
    $final_errorcode = 200;
    $final_status = "Success";
} else {
    $final_error = 404;
    $final_status = "Failure"; 
}

echo $result = json_encode(array("errCode"=>$final_errorcode,"status"=>$final_status,"Hackathon_Details" => $hackathon_details,"candidate_details" => $candidate_lists ));

mysqli_close($conn);
?>