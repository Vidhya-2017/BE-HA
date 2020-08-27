<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json;");
$json = file_get_contents('php://input');
$data = json_decode($json,true);

require_once 'include/dbconnect.php'; 
/**HACKATHON DETAILS LIST :: START **/

$query  =   "Select A.EventName,A.EventID,A.Duration,A.Location as LocationID 
,(select el.loc_name from event_location el where el.loc_id=A.Location ) as Location ,A.Date,
A.Skills as SkillsId,A.AssessmentScale As AssessScale ,C.Duration,group_concat(b.Skills) as skill_name,
D.ClientName FROM register_event as A, skills as B, duration as C,client as D
WHERE A.Client=D.ClientId AND A.Duration=C.DurationID 
and FIND_IN_SET( B.SkillId, A.Skills) AND isClosed=0  GROUP By A.EventID";
$result =   mysqli_query($conn,$query);
$hackathon_details = [];
    
if(mysqli_num_rows($result) > 0){
    while ($hackathon_detailsrow = mysqli_fetch_assoc($result)){
        
          $event_ID = $hackathon_detailsrow['EventID'];

            $hackasses = mysqli_query($conn,"SELECT group_concat(os.OtherAssementScaleName) as ScaleName FROM event_otherassessmentscale eo INNER JOIN other_assessmentscale os ON eo.eas_OtherAssessId = os.OtherAssessmentId WHERE eo.eas_eventID ='$event_ID'");
            if(mysqli_num_rows($hackasses) > 0){
                
                while ($assScalerow = mysqli_fetch_assoc($hackasses)){
                    $hackathon_detailsrow["OtherAssessScale"]= $assScalerow['ScaleName'];
                }
                       
            }else{
                $hackathon_detailsrow["OtherAssessScale"]="";
            }

            $hackCompe =  mysqli_query($conn,"SELECT group_concat(cr. CompetancyName) as CompetancyRating FROM `competancy_rating_event` ce INNER JOIN `competancy_rating` cr ON ce.CompetancyID = cr.ID WHERE ce.EventID='$event_ID'");
            if(mysqli_num_rows($hackCompe) > 0){
                while ($hackComperow = mysqli_fetch_assoc($hackCompe)){
                    $hackathon_detailsrow["CompetancyRating"]= $hackComperow['CompetancyRating'];
                }
            }else{
                $hackathon_detailsrow["CompetancyRating"]="";
            }
            $hackathon_details[] = $hackathon_detailsrow;
    }
    
    if(count($hackathon_details)){
        $errcode = 200;
        $status = "Success";
    }else{
        $errcode = 404;
        $status = "Failure";
        $hackathon_details=''; 
    }
    
}


echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"arrRes" => $hackathon_details));

mysqli_close($conn);
?>