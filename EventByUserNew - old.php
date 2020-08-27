<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);

require_once 'include/dbconnect.php';
//$query = "SELECT a.EventName as Name ,a.Date as EventDate, GROUP_CONCAT(DISTINCT b.skills) SkillName,c.ClientName as ClientName,GROUP_CONCAT(DISTINCT d.AssementScaleName) AssessmentScaleName, e.Duration as Duration,IF(ProblemSolvingSkillTested=1,'TRUE','FALSE') as PSSkillTest ,IF(TechnicalSkilslsTested=1,'TRUE','FALSE') as TechSkillTested , IF(CommunicationSkillIsTested=1,'TRUE','FALSE') as ComSkillTested,IF(LogicalSkillsIsTested=1,'TRUE','FALSE') as LogSkillTested FROM `register_event` a INNER JOIN skills b ON FIND_IN_SET(b.SkillId, a.`Skills`) INNER JOIN `client` c on a.`Skills` = c.`ClientId` INNER JOIN `assessmentscale` d ON FIND_IN_SET(d.`AssessmentId` , a.`AssessmentScale`) INNER JOIN `duration` e on a.`Duration`= e.`DurationID` where a.isClosed='0' GROUP BY a.EventID ";

// $user_ID=2;
$user_ID=$data['UserID'];
$result='';

$queryUser = "SELECT EventID as EventId , EventName as Name ,Location ,Duration ,Client ,Date as EventDate, Skills ,AssessmentScale FROM `register_event` where isClosed='0' and CreatedBy = ".$user_ID." ";

$resultUser = mysqli_query($conn,$queryUser);
$result=$resultUser;


if(mysqli_num_rows(@$result) == 0){

  $queryPanel = "SELECT e.EventID as EventId , e.EventName as Name ,Location ,Duration ,Client ,e.Date as EventDate, e.Skills ,e.AssessmentScale FROM `register_event` e INNER JOIN event_panel_list p ON p.pl_eventId = e.EventID where e.isClosed='0' and p.pl_userid = ".$user_ID." ";

//, IF(e.ProblemSolvingSkillTested=1,'TRUE','FALSE') as PSSkillTest ,IF(e.TechnicalSkilslsTested=1,'TRUE','FALSE') as TechSkillTested , IF(e.CommunicationSkillIsTested=1,'TRUE','FALSE') as ComSkillTested,IF(e.LogicalSkillsIsTested=1,'TRUE','FALSE') as LogSkillTested

  $resultPanel = mysqli_query($conn,$queryPanel);
  $result=$resultPanel;
}

if(mysqli_num_rows(@$result) == 0){

  $queryOrganiser = "SELECT e.EventID as EventId , e.EventName as Name ,Location ,Duration ,Client ,e.Date as EventDate, e.Skills ,e.AssessmentScale FROM `register_event` e INNER JOIN event_organizer o ON o.orgnizer_eventid = e.EventID where e.isClosed='0' and o.organizer_userid = ".$user_ID." ";

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

        $sqlCompetancyLevel = "SELECT c.`ID` as compentancyID, c.`CompetancyName` FROM  `competancy_rating` c INNER JOIN `competancy_rating_event` ce on ce.`CompetancyID` = c.`ID` WHERE ce.`EventID` = $event_id and c.`isActive`=1";
       
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
        $durationrow['skillname'] = explode(",",$skillrow['skillsname']);
        $durationrow['Skills'] = explode(",",$skill_id);
        $durationrow['AssessmentScale'] = explode(",",$ass_scale);
        $durationrow['Organisers'] = $OrganisersData;
        $durationrow['CompetancyData'] = $competancyData;
        $durationrow['OtherAssessmentData'] = $otherAssessmentData;

        

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