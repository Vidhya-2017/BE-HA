<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);

require_once 'include/dbconnect.php';

/* $data['EventID']=4; */
$event_ID=$data['EventID'];
$result='';

$queryUser = "SELECT EventID as EventId , EventName as Name ,Location ,Duration ,Client ,Date as EventDate, Skills ,AssessmentScale FROM `register_event` where isClosed='0' and EventID = ".$event_ID." ";

$resultUser = mysqli_query($conn,$queryUser);
$result=$resultUser;


if(mysqli_num_rows(@$result) == 0){

  $queryPanel = "SELECT e.EventID as EventId , e.EventName as Name ,Location ,Duration ,CreatedBy,Client ,e.Date as EventDate, e.Skills ,e.AssessmentScale FROM `register_event` e INNER JOIN event_panel_list p ON p.pl_eventId = e.EventID where e.isClosed='0' and p.pl_userid = ".$user_ID." ";

//, IF(e.ProblemSolvingSkillTested=1,'TRUE','FALSE') as PSSkillTest ,IF(e.TechnicalSkilslsTested=1,'TRUE','FALSE') as TechSkillTested , IF(e.CommunicationSkillIsTested=1,'TRUE','FALSE') as ComSkillTested,IF(e.LogicalSkillsIsTested=1,'TRUE','FALSE') as LogSkillTested

  $resultPanel = mysqli_query($conn,$queryPanel);
  $result=$resultPanel;
}

if(mysqli_num_rows(@$result) == 0){

  $queryOrganiser = "SELECT e.EventID as EventId , e.EventName as Name ,Location ,Duration,CreatedBy ,Client ,e.Date as EventDate, e.Skills ,e.AssessmentScale FROM `register_event` e INNER JOIN event_organizer o ON o.orgnizer_eventid = e.EventID where e.isClosed='0' and o.organizer_userid = ".$user_ID." ";

  $resultOrganiser = mysqli_query($conn,$queryOrganiser);
  $result=$resultOrganiser;
}


$durationset = array();
if(mysqli_num_rows($result) > 0){
    while ($durationrow = mysqli_fetch_assoc($result)){

        $event_id = $durationrow['EventId'];

        $sqlOrganisers = "SELECT o.`organizer_userid` as user_id , u.`first_name`, u.`last_name` FROM `event_organizer` o inner join `user_login` u on u.user_id = o.organizer_userid WHERE o.`orgnizer_eventid` = $event_id";
        
        $organiserresult = mysqli_query($conn,$sqlOrganisers);
        $OrganisersData=[];
        while ( $organiserrow = mysqli_fetch_assoc($organiserresult)){
          $OrganisersData [] =  $organiserrow;
        }

       // $sqlCompetancyLevel = "SELECT c.`ID` as compentancyID, c.`CompetancyName` FROM  `competancy_rating` c INNER JOIN `competancy_rating_event` ce on ce.`CompetancyID` = c.`ID` WHERE ce.`EventID` = $event_id and c.`isActive`=1";
       
        $sqlCompetancyLevel = "SELECT `ID` as compentancyID, `CompetancyName` FROM `competancy_rating` WHERE `isActive`=1";

        $competancyresult = mysqli_query($conn,$sqlCompetancyLevel);
        $competancyData=[];
        while ( $competancyrow = mysqli_fetch_assoc($competancyresult)){
          $competancyData [] =  $competancyrow;
        }

        $sqlOtherAssessment = "SELECT o.`OtherAssessmentId`, o.`OtherAssementScaleName`, o.`ScaleValue` FROM `other_assessmentscale` o INNER JOIN `event_otherassessmentscale` oe ON oe.`eas_OtherAssessId` = o.`OtherAssessmentId` WHERE oe.`eas_eventID` = $event_id and o.`isActive` = 1";
       
        $otherassessmentresult = mysqli_query($conn,$sqlOtherAssessment);
        $otherAssessmentData=[];
        while ( $otherassessmentrow = mysqli_fetch_assoc($otherassessmentresult)){
          $otherAssessmentData [] =  $otherassessmentrow;
        }


         $skill_id = $durationrow['Skills'];
         $ass_scale = $durationrow['AssessmentScale'];
         
        $selSkill = "SELECT GROUP_CONCAT(Skills) as skillsname  FROM `skills` where SkillId IN ($skill_id)";
        $skillresult = mysqli_query($conn,$selSkill);
        $skillrow = mysqli_fetch_assoc($skillresult);


        $selPanel = "SELECT `pl_userid` as userID FROM `event_panel_list` WHERE `pl_eventId` = ".$event_ID." and `isActive` = 1";
        
        $panelresult = mysqli_query($conn,$selPanel);
       
       
        $panelData=[];
        while ( $panelrow = mysqli_fetch_assoc($panelresult)){
          $panelData [] =  $panelrow;
        }
       
        $selOrganiser = "SELECT `organizer_userid` as userID FROM `event_organizer` WHERE `orgnizer_eventid`= ".$event_ID." and `isActive` =1";
       
        $organiserresult = mysqli_query($conn,$selOrganiser);
        
        $organiserData=[];
        while ( $organiserrow = mysqli_fetch_assoc($organiserresult)){
          $organiserData [] =  $organiserrow;
        }
       
        $durationrow['skillname'] = explode(",",$skillrow['skillsname']);
        $durationrow['Skills'] = explode(",",$skill_id);
        $durationrow['AssessmentScale'] = explode(",",$ass_scale);
        $durationrow['Organisers'] = $OrganisersData;
        $durationrow['CompetancyData'] = $competancyData;
        $durationrow['OtherAssessmentData'] = $otherAssessmentData;
        $durationrow['PanelData'] = $panelData;
        $durationrow['OrganisersId'] = $organiserData;



        

      $durationset[] = $durationrow;
    } 
    $errcode = 200;
    $status = "Success";
}else{
    $errcode = 404;
    $status = "No Data Found";
}

echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"arrRes" => $durationset));

mysqli_close($conn);
?>