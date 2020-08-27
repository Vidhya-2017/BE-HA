<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);

require_once 'include/dbconnect.php';


if (isset($data['squad_id']) ) {
	$squadID 		=	$data['squad_id'];                        
	$eventID 		=	$data['event_id'];                        



    /**HACKATHON DETAILS LIST :: START **/

$query  =   "Select A.EventName,A.EventID,A.Duration,A.Location as LocationID ,(select el.loc_name from event_location el where el.loc_id=A.Location ) as Location ,A.Date,A.Skills as SkillsId,A.AssessmentScale As AssessScale ,C.Duration,group_concat(b.Skills) skill_name,D.ClientName FROM register_event as A, skills as B, duration as C,client as D WHERE A.Client=D.ClientId  AND A.EventID='$eventID' AND A.Duration=C.DurationID and FIND_IN_SET( B.SkillId, A.Skills)";
$result =   mysqli_query($conn,$query);
$hackathon_details = [];
    
if(mysqli_num_rows($result) > 0){
    while ($hackathon_detailsrow = mysqli_fetch_assoc($result)){
        
            $hackasses = mysqli_query($conn,"SELECT eo.eas_OtherAssessId as ScaleId,os.OtherAssementScaleName as ScaleName,os.ScaleValue as ScaleValue FROM event_otherassessmentscale eo INNER JOIN other_assessmentscale os ON eo.
            eas_OtherAssessId = os.OtherAssessmentId WHERE eo.eas_eventID ='$eventID'");
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
    $errcode1 = 200;
    $status1 = "Success";
}else{
    $errcode1 = 404;
    $status1 = "Failure";
}
/**HACKATHON DETAILS LIST :: END **/

/**ORGANIZER LIST :: START **/
$org_query="SELECT A.orgnizer_eventid,A.organizer_userid,B.first_name,B.last_name,B.email,B.contact_no FROM  event_organizer as A , user_login as B WHERE A.organizer_userid=B.user_id AND A.orgnizer_eventid='$eventID' ";
$organizers_list_result =   mysqli_query($conn,$org_query);
    $organizers_list = [];
    if ($organizers_list_result) {
    if(mysqli_num_rows($organizers_list_result) > 0){
        while ($organizers_listrow = mysqli_fetch_assoc($organizers_list_result)){
            
                $organizers_list[] = $organizers_listrow;
        } 
        $errcode2 = 200;
        $status2 = "Success";
    }else{
        $errcode2 = 404;
        $status2 = "Failure";
    }
}
/**ORGANIZER LIST :: END **/


/**PANEL LIST :: START **/
$panel_query="SELECT a.pl_userid as panel_userid ,a.pl_eventId as panel_eventid, b.first_name,b.last_name ,b.contact_no,b.email FROM event_panel_list a INNER JOIN user_login b  on 	a.pl_userid = b.user_id WHERE b.isActive=1 and a.isActive=1 and a.pl_eventId ='$eventID' ";
$panels_list_result =   mysqli_query($conn,$panel_query);
    $panels_list = [];
    if ($panels_list_result) {
    if(mysqli_num_rows($panels_list_result) > 0){
        while ($panels_listrow = mysqli_fetch_assoc($panels_list_result)){
            
                $panels_list[] = $panels_listrow;
        } 
        $errcode3 = 200;
        $status3 = "Success";
    }else{
        $errcode3 = 404;
        $status3 = "Failure";
    }
}
/**PANEL LIST :: END **/

   /**CANDIDATE LIST :: START **/

    $squDetail =mysqli_fetch_assoc(mysqli_query($conn,"SELECT SquadName FROM squad WHERE ID='$squadID' AND EventID='$eventID'"));

    $squadName = $squDetail['SquadName'];
//,B.candidate_image
    $candidate_query="SELECT A.candidate_id,A.eventId, A.squad_id,B.EmpName,B.EmailId,B.ContactNo,B.Expereince,B.RelevantExperience FROM  squad_feedback as A, candidate_registration as B WHERE A.candidate_id=B.ID AND A.squad_id='$squadID' AND A.eventId='$eventID' AND A.isActive=1 AND B.isActive=1 GROUP BY A.candidate_id " ;

$candidate_list_result =   mysqli_query($conn,$candidate_query);
$candidate_lists = [];
if(mysqli_num_rows($candidate_list_result) > 0){
    while ($candidate_listrow = mysqli_fetch_assoc($candidate_list_result)){
      
            /**FEEDBACK DETAIL **/
         $squad_group  = "SELECT sq1.sid as sids,sq1.sprintLevel as spLevel FROM squad_feedback as sq1  WHERE  sq1.eventId='$eventID' AND sq1.squad_id='$squadID' AND sq1.candidate_id = '".$candidate_listrow['candidate_id']."' group by sq1.sprintLevel";
        $squad_group_res =   mysqli_query($conn,$squad_group);
        $sprintLevel =[];
        if(mysqli_num_rows($squad_group_res) > 0){
            while ($squad_group_row = mysqli_fetch_assoc($squad_group_res)){
                $squad_feedback_query  = "SELECT sq.sid as sids,sq.sprintLevel,sq.feedbackTxt,sq.sq_final_status,sq.role,sq.competancy_rating,ul.first_name,ul.last_name,ul.email,ul.contact_no FROM squad_feedback as sq , user_login as ul WHERE sq.squad_id='$squadID' AND sq.eventId='$eventID' AND sq.candidate_id = '".$candidate_listrow['candidate_id']."' AND  sq.createdBy=ul.user_id AND sq.sprintLevel='".$squad_group_row['spLevel']."'";
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
            $candidate_listrow['SquadName'] = $squadName ;   
          $candidate_listrow['feedback'] = $sprintLevel;   
          $candidate_lists[]=$candidate_listrow;
            
        }
       
          
        }
            
       
    }
  
    if(count($candidate_lists)>0){
        $errcode = 200;
        $status = "Success";
    }else{
        $errcode = 404;
         $status = "Failure";
    }
  /**CANDIDATE LIST :: END **/
}else {
		$errcode 	=	404;
		$status 	=	"Squad id Not Found";
}



if ($errcode=='200' || $errcode1=='200' ||  $errcode2=='200' || $errcode3=='200') {
    $final_errorcode = 200;
    $final_status = "Success";
} else {
    $final_error = 404;
    $final_status = "Failure"; 
}

echo $result =	json_encode(array("errCode"=>$final_errorcode,"status"=>$final_status,"Hackathon_Details" => $hackathon_details,"Organizers_list" => $organizers_list,"Panels_list"=>$panels_list,"candidate_details" => $candidate_lists));   
mysqli_close($conn);
?>