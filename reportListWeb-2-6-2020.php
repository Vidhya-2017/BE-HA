<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json;");
$json = file_get_contents('php://input');
$data = json_decode($json,true);

require_once 'include/dbconnect.php'; 
$event_ID = $data['event_id'];
/**HACKATHON DETAILS LIST :: START **/

$query  =   "Select A.EventName,A.EventID,A.Duration,A.Location as LocationID ,(select el.loc_name from event_location el where el.loc_id=A.Location ) as Location ,A.Date,A.Skills as SkillsId,A.AssessmentScale As AssessScale ,C.Duration,group_concat(b.Skills) skill_name,D.ClientName FROM register_event as A, skills as B, duration as C,client as D WHERE A.Client=D.ClientId  AND A.EventID='$event_ID' AND A.Duration=C.DurationID and FIND_IN_SET( B.SkillId, A.Skills)";
$result =   mysqli_query($conn,$query);
$hackathon_details = [];
    
if(mysqli_num_rows($result) > 0){
    while ($hackathon_detailsrow = mysqli_fetch_assoc($result)){
        
           

            $hackasses = mysqli_query($conn,"SELECT eo.eas_OtherAssessId as ScaleId,os.OtherAssementScaleName as ScaleName,os.ScaleValue as ScaleValue FROM event_otherassessmentscale eo INNER JOIN other_assessmentscale os ON eo.
            eas_OtherAssessId = os.OtherAssessmentId WHERE eo.eas_eventID ='$event_ID'");
            if(mysqli_num_rows($result) > 0){
                $ass_Deatail=[];
                while ($assScalerow = mysqli_fetch_assoc($hackasses)){
                    $ass_Deatail[]=$assScalerow;
                }
                $hackathon_detailsrow["OtherAssessScale"]=$ass_Deatail;
                $hackathon_details[] = $hackathon_detailsrow;
            }
         //   array_push($hackathon_details,$hackathon_detailsrow); 

    } 
    $errcode = 200;
    $status = "Success";
}else{
    $errcode = 404;
    $status = "Failure";
}
/**HACKATHON DETAILS LIST :: END **/

/**ORGANIZER LIST :: START **/
$org_query="SELECT A.orgnizer_eventid,A.organizer_userid,B.first_name,B.last_name,B.email,B.contact_no FROM  event_organizer as A , user_login as B WHERE A.organizer_userid=B.user_id AND A.orgnizer_eventid='$event_ID' ";
$organizers_list_result =   mysqli_query($conn,$org_query);
    $organizers_list = [];
    if ($organizers_list_result) {
    if(mysqli_num_rows($organizers_list_result) > 0){
        while ($organizers_listrow = mysqli_fetch_assoc($organizers_list_result)){
            
                $organizers_list[] = $organizers_listrow;
        } 
        $errcode1 = 200;
        $status1 = "Success";
    }else{
        $errcode1 = 404;
        $status1 = "Failure";
    }
}
/**ORGANIZER LIST :: END **/

/**CANDIDATE LIST :: START **/
//,B.candidate_image
 $candidate_query="SELECT  A.CanidateID,A.EventID, B.EmpName,B.EmailId,B.ContactNo,B.Expereince,B.RelevantExperience FROM  candidate_event as A, candidate_registration as B WHERE  A.EventID='$event_ID' AND A.CanidateID=B.ID AND A.isActive=1 AND B.isActive=1" ;

$candidate_list_result =   mysqli_query($conn,$candidate_query);
$candidate_lists = [];
if(mysqli_num_rows($candidate_list_result) > 0){
    while ($candidate_listrow = mysqli_fetch_assoc($candidate_list_result)){
       
        /** SQUAD DETAIL */
        //echo "SELECT D.SquadName,C.squad_id FROM squad_feedback C INNER JOIN squad D ON C.squad_id = D.ID WHERE C.candidate_id='".$candidate_listrow['CanidateID']."'  AND C.eventId='$event_ID' group by C.candidate_id ";
        $squad =   mysqli_query($conn,"SELECT D.SquadName,C.squad_id FROM squad_feedback C INNER JOIN squad D ON C.squad_id = D.ID WHERE C.candidate_id='".$candidate_listrow['CanidateID']."'  AND C.eventId='$event_ID' group by C.candidate_id ");
        if(mysqli_num_rows($squad) > 0){
            while ($squadRow = mysqli_fetch_assoc($squad)){
                $candidate_listrow["SquadID"]= $squadRow['squad_id'];     
                $candidate_listrow["SquadName"]= $squadRow['SquadName'];
               
            }
        }else{
            $candidate_listrow["SquadID"]= null;     
            $candidate_listrow["SquadName"]= null;
        }
     
        /**FEEDBACK DETAIL **/
        $squad_group  = "SELECT sq1.sid as sids,sq1.sprintLevel as spLevel FROM squad_feedback as sq1  WHERE  sq1.eventId='$event_ID' AND sq1.candidate_id = '".$candidate_listrow['CanidateID']."' group by sq1.sprintLevel";
        $squad_group_res =   mysqli_query($conn,$squad_group);
        $sprintLevel =[];
        if(mysqli_num_rows($squad_group_res) > 0){
            while ($squad_group_row = mysqli_fetch_assoc($squad_group_res)){
                $squad_feedback_query  = "SELECT sq.sid as sids,sq.sprintLevel,sq.feedbackTxt,sq.sq_final_status,ul.first_name,ul.last_name,ul.email,ul.contact_no FROM squad_feedback as sq , user_login as ul WHERE  sq.eventId='$event_ID' AND sq.candidate_id = '".$candidate_listrow['CanidateID']."' AND  sq.createdBy=ul.user_id AND sq.sprintLevel='".$squad_group_row['spLevel']."'";
                $squad_feedback_list_result =   mysqli_query($conn,$squad_feedback_query);
                $candidate_list_feedback = [];
                $squad_feedback_lists=[];
                if(mysqli_num_rows($squad_feedback_list_result) > 0){
                    while ($candidate_listsfbrow = mysqli_fetch_assoc($squad_feedback_list_result)){


                        $qruAss = "SELECT os.OtherAssementScaleName as AssName,sq.assessment_value as AssVal FROM squad_feedback_assesment_skill sq INNER JOIN other_assessmentscale os ON sq.other_assessment_scale_id = os.OtherAssessmentId WHERE sq.squad_fb_id ='".$candidate_listsfbrow['sids']."'";
                        $sassResult =   mysqli_query($conn,$qruAss);
                        while ($canAssbrow = mysqli_fetch_assoc($sassResult)){
                            $key = $canAssbrow['AssName'];
                            $candidate_listsfbrow[$key] = $canAssbrow['AssVal'];    
                             
                        }


                        $squad_feedback_lists[] = $candidate_listsfbrow;
                         
                    }

                    $sprintLevel[]= $squad_feedback_lists;
                }else{
                    $candidate_listsfbrow=[];
                    $squad_feedback_lists[] = $candidate_listsfbrow;
                }

               
            }

          $candidate_listrow['feedback'] = $sprintLevel;   
          $candidate_lists[]=$candidate_listrow;
        }
            
       
    }
    if(count($candidate_lists)>0){
        $errcode2 = 200;
        $status2 = "Success";
    }else{
        $errcode2 = 404;
         $status2 = "Failure";
    }
    
}else{
    $errcode2 = 404;
    $status2 = "Failure";
}

/**CANDIDATE LIST :: END **/


if ($errcode=='200' || $errcode1=='200' ||  $errcode2=='200' || $errcode3=='200' || $errcode4=='200' || $errcode5=='200' ) {
    $final_errorcode = 200;
    $final_status = "Success";
} else {
    $final_error = 404;
    $final_status = "Failure"; 
}

echo $result = json_encode(array("errCode"=>$final_errorcode,"status"=>$final_status,"Hackathon_Details" => $hackathon_details,"Organizers_list" => $organizers_list,"candidate_list"=> $candidate_lists));
//echo $result = json_encode(array("errCode"=> $errcode, "status"=> $status, "candidate_squad_lists"=> $candidate_squad_lists ));

mysqli_close($conn);
?>