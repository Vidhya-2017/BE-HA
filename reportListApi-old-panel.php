<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json;");
$json = file_get_contents('php://input');
$data = json_decode($json,true);

require_once 'include/dbconnect.php'; 
$event_ID = $data['event_id'];
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

/**ORGANIZER LIST :: START **/
$org_query="SELECT A.orgnizer_eventid,A.organizer_userid,B.first_name,B.last_name FROM  event_organizer as A , user_login as B WHERE A.organizer_userid=B.user_id AND A.orgnizer_eventid='$event_ID' ";
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
$candidate_query="SELECT  A.CanidateID,A.EventID, B.EmpName,B.EmailId,B.ContactNo,B.Expereince,B.RelevantExperience,B.candidate_image FROM  candidate_event as A, candidate_registration as B WHERE  A.EventID='$event_ID' AND A.CanidateID=B.ID" ;
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

           $squad_feedback_query  = "SELECT A.sid as sids,A.sprintLevel,A.feedbackTxt,A.sq_final_status FROM squad_feedback as A WHERE  A.eventId='$event_ID' AND A.candidate_id = '".$candidate_listrow['CanidateID']."' ";
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

        //    $candidate_list['feedback'] = array(0 => "Shaolin Monk",
        //        1 => "Drunken Master",
        //        2 => "American Ninja",
        //        3 => "Once upon a time in China",
        //        4 =>"Replacement Killers" );
        //        array_push($candidate_lists,$candidate_list);
               
    } 
   
    $errcode2 = 200;
    $status2 = "Success";
}else{
    $errcode2 = 404;
    $status2 = "Failure";
}
/**CANDIDATE LIST :: END **/

//exit;
/**SQUAD LIST :: START **/
$squad_query="SELECT SquadName FROM  squad WHERE EventID='$event_ID'";
$squad_list_result =   mysqli_query($conn,$squad_query);
$squad_list = [];
    
if(mysqli_num_rows($squad_list_result) > 0){
    while ($squad_listrow = mysqli_fetch_assoc($squad_list_result)){
        
            $squad_list[] = $squad_listrow;
    } 
    $errcode3 = 200;
    $status3 = "Success";
}else{
    $errcode3 = 404;
    $status3 = "Failure";
}
/**SQUAD LIST :: END **/


/**CANDIDATE SQUAD LIST :: START **/
$candidate_squad_query  =   "SELECT A.SquadName, C.EmpName, C.EmailId  FROM squad as A, squad_candidates as B, candidate_registration as C, skills as D WHERE A.ID=B.SquadID AND B.CandidateID=C.ID AND A.EventID = '$event_ID' GROUP BY B.CandidateID" ;
$candidate_squad_result =   mysqli_query($conn,$candidate_squad_query);
$candidate_squad_lists  =   [];
    
if(mysqli_num_rows($candidate_squad_result) > 0){
    while ($candidate_squadrow = mysqli_fetch_assoc($candidate_squad_result)){

            $candidate_squad_lists[] = $candidate_squadrow;
    } 
    $errcode4 = 200;
    $status4 = "Success";
}else{
    $errcode4 = 404;
    $status4 = "Failure";
}
/**CANDIDATE SQUAD LIST :: END **/


/** SQUADFEEDBACK LIST :: START **/
$squad_feedback_query="SELECT A.sid as sids, A.sprintLevel,A.feedbackTxt,A.sq_final_status FROM squad_feedback as A WHERE A.eventId='$event_ID'";
$squad_feedback_list_result =   mysqli_query($conn,$squad_feedback_query);
    $squad_feedback_list = [];
    if ($squad_feedback_list_result) {
    if(mysqli_num_rows($squad_feedback_list_result) > 0){
        while ($squad_feedbackrow = mysqli_fetch_assoc($squad_feedback_list_result)){
                
                $sqaAss = "SELECT os.OtherAssementScaleName as AssName,sq.assessment_value as AssVal FROM squad_feedback_assesment_skill sq INNER JOIN other_assessmentscale os ON sq.other_assessment_scale_id = os.OtherAssessmentId WHERE sq.squad_fb_id ='".$squad_feedbackrow['sids']."'";
                $sassResult =   mysqli_query($conn,$sqaAss);
                 $othskillsqa=[];
                 $othskillsfb=[];
                 while ($sqaAssbrow = mysqli_fetch_array($sassResult)){
                     $assesNamesqa =$sqaAssbrow['AssName'];
                     $assesValuesqa = $sqaAssbrow['AssVal'];                              
                   
                      $othskillsfb = array("ParamNames"=>$assesNamesqa,"ParamValues"=>$assesValuesqa);  
                     array_push($othskillsqa,$othskillsfb); 

                 }
                 $squad_feedbackrow['AssesmentParams']= $othskillsqa; 
             $squad_feedback_list[] = $squad_feedbackrow;
        } 
        $errcode5 = 200;
        $status5 = "Success";
    }else{
        $errcode5 = 404;
        $status5 = "Failure";
    }
}
/** SQUADFEEDBACK LIST :: END **/



if ($errcode=='200' || $errcode1=='200' ||  $errcode2=='200' || $errcode3=='200' || $errcode4=='200' || $errcode5=='200' ) {
    $final_errorcode = 200;
    $final_status = "Success";
} else {
    $final_error = 404;
    $final_status = "Failure"; 
}

echo $result = json_encode(array("errCode"=>$final_errorcode,"status"=>$final_status,"Hackathon_Details" => $hackathon_details,"Organizers_list" => $organizers_list, "candidate_lists" => $candidate_lists, "Squad_lists" => $squad_list,"Squad_Feedback_Lists" => $squad_feedback_list,"candidate_squad_lists"=> $candidate_squad_lists ));
//echo $result = json_encode(array("errCode"=> $errcode, "status"=> $status, "candidate_squad_lists"=> $candidate_squad_lists ));

mysqli_close($conn);
?>