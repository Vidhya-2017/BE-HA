<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once 'include/dbconnect.php';
//$query = "SELECT a.EventName as Name ,a.Date as EventDate, GROUP_CONCAT(DISTINCT b.skills) SkillName,c.ClientName as ClientName,GROUP_CONCAT(DISTINCT d.AssementScaleName) AssessmentScaleName, e.Duration as Duration,IF(ProblemSolvingSkillTested=1,'TRUE','FALSE') as PSSkillTest ,IF(TechnicalSkilslsTested=1,'TRUE','FALSE') as TechSkillTested , IF(CommunicationSkillIsTested=1,'TRUE','FALSE') as ComSkillTested,IF(LogicalSkillsIsTested=1,'TRUE','FALSE') as LogSkillTested FROM `register_event` a INNER JOIN skills b ON FIND_IN_SET(b.SkillId, a.`Skills`) INNER JOIN `client` c on a.`Skills` = c.`ClientId` INNER JOIN `assessmentscale` d ON FIND_IN_SET(d.`AssessmentId` , a.`AssessmentScale`) INNER JOIN `duration` e on a.`Duration`= e.`DurationID` where a.isClosed='0' GROUP BY a.EventID ";

$query = "SELECT EventID as EventId , EventName as Name ,Date as EventDate, Skills ,AssessmentScale, IF(ProblemSolvingSkillTested=1,'TRUE','FALSE') as PSSkillTest ,IF(TechnicalSkilslsTested=1,'TRUE','FALSE') as TechSkillTested , IF(CommunicationSkillIsTested=1,'TRUE','FALSE') as ComSkillTested,IF(LogicalSkillsIsTested=1,'TRUE','FALSE') as LogSkillTested FROM `register_event` where isClosed='0' ";

$result = mysqli_query($conn,$query);
$durationset = array();
if(mysqli_num_rows($result) > 0){
    while ($durationrow = mysqli_fetch_assoc($result)){
         $skill_id = $durationrow['Skills'];
         $ass_scale = $durationrow['AssessmentScale'];
         
        $selSkill = "SELECT GROUP_CONCAT(Skills) as skillsname  FROM `skills` where SkillId IN ($skill_id)";
        $skillresult = mysqli_query($conn,$selSkill);
        $skillrow = mysqli_fetch_assoc($skillresult);
        $durationrow['skillname'] = explode(",",$skillrow['skillsname']);
        $durationrow['Skills'] = explode(",",$skill_id);
        $durationrow['AssessmentScale'] = explode(",",$ass_scale);
      $durationset[] = $durationrow;
    } 
    $errcode = 200;
    $status = "Success";
}else{
    $errcode = 500;
    $status = "Failure";
}

echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"arrRes" => $durationset));

mysqli_close($conn);
?>