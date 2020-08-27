<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);
require_once 'include/dbconnect.php';

if(isset($data["eventID"])){
    $event_id = $data['eventID'];
     if( $data["eventID"]!='' ){
        
        $query  =   "Select A.EventName,A.EventID,A.Duration,A.Location as LocationID ,(select el.loc_name from event_location el where el.loc_id=A.Location ) as Location ,A.Date,A.Skills as SkillsId,A.AssessmentScale As AssessScale ,C.Duration,group_concat(b.Skills) skill_name,D.ClientName FROM register_event as A, skills as B, duration as C,client as D WHERE A.Client=D.ClientId  AND A.EventID='$event_id' AND A.Duration=C.DurationID and FIND_IN_SET( B.SkillId, A.Skills)";
        $result =   mysqli_query($conn,$query);
        $hackathon_details = [];
            
        if(mysqli_num_rows($result) > 0){
            while ($hackathon_detailsrow = mysqli_fetch_assoc($result)){
                    $hackasses = mysqli_query($conn,"SELECT eo.eas_OtherAssessId as ScaleId,os.OtherAssementScaleName as ScaleName,os.ScaleValue as ScaleValue FROM event_otherassessmentscale eo INNER JOIN other_assessmentscale os ON eo.
                    eas_OtherAssessId = os.OtherAssessmentId WHERE eo.eas_eventID ='$event_id'");
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
        
        }


        $fbquery = "SELECT ef.cf_clientID,cl.ClientName,ul.first_name,ul.last_name,ul.email,ul.contact_no,ef.cf_rating,ef.cf_comment,ef.createdDate from event_feedback ef INNER JOIN client cl ON ef.cf_clientID=cl.ClientId INNER JOIN user_login ul ON ul.user_id=ef.createdBy WHERE ef.cf_eventID='$event_id' ";
        $fbresult = mysqli_query($conn,$fbquery);
        $panelset = array();
        if(mysqli_num_rows($fbresult) > 0){
            while ($panelrow = mysqli_fetch_assoc($fbresult)){
                $panelset[] = $panelrow;
            } 
            $errcode = 200;
            $status = "Success";
        }else{
            $errcode = 404;
            $status = "No Result";
            $panelset='';
        }

     }else{
          $errcode = 404;
          $status = "Event ID should not null";
          $panelset='';
     }
 }


echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"eventDetail"=>$hackathon_details,"feedBack" => $panelset));

mysqli_close($conn);
?>