<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);


require_once 'include/dbconnect.php';

$event_name			 		= $data['EventName'];
$event_date                 =  $data['eventdate'];
$event_location				= $data['Location'];
$duration			 		= $data['Duration'];
//$skills 			 		= $data['Skills'];
$skills                     = implode(",",$data['Skills']);
$client						 = $data['Client'];
// $assessment_scale	 			= $data['AssessmentScale'];
$assessment_scale	 			= implode(",",$data['AssessmentScale']);
$problem_solving_skills_tested = ($data['ProblemSolvingSkillTested'] == true ? 1 : 0);
$technical_skills_tested	 = ($data['TechnicalSkilslsTested'] == true ? 1 : 0);
$communication_skills_tested = ($data['CommunicationSkillIsTested'] == true ? 1 : 0);
$logical_skills_tested 	= ($data['LogicalSkillsIsTested']== true ? 1 : 0);
$Created_Date				= date('Y-m-d h:i:s');
$Created_By	 			    = $data['CreatedBy'];
$UpdatedDate 			    = date('Y-m-d h:i:s');
$Updated_By				    = $data['UpdatedBy'];

$organizerDetails = $data['OrganizerData'];
$competancyLevelDetails = $data['CompetancyLevelData'];


 $query = "INSERT INTO register_event ( EventName, Location, Date, Duration, Skills, Client, AssessmentScale, ProblemSolvingSkillTested, TechnicalSkilslsTested, CommunicationSkillIsTested, LogicalSkillsIsTested,CreatedDate,CreatedBy,UpdatedDate,UpdatedBy) VALUES ( '$event_name', '$event_location', '$event_date', '$duration', '$skills ', '$client', '$assessment_scale', '$problem_solving_skills_tested', '$technical_skills_tested', '$communication_skills_tested', '$logical_skills_tested', '$Created_Date', '$Created_By', '$UpdatedDate', '$Updated_By'); ";

$result = mysqli_query($conn,$query);
$EventId = mysqli_insert_id($conn);
if($EventId > 0 ){

    for($i = 0; $i < count($competancyLevelDetails); $i++) {
       
        $competancylevelid = $competancyLevelDetails[$i];
        

       $insQry ="INSERT INTO `competancy_rating_event`(`EventID`, `CompetancyID`, `CreatedBy`, `CreatedDate`, `UpdatedBy`, `UpdatedDate`) VALUES ('$EventId','$competancylevelid','$Created_By','$Created_Date','$Updated_By','$UpdatedDate')";
        $insRes = mysqli_query($conn,$insQry);
        $competancyEventId = mysqli_insert_id($conn);

   }

    for($i = 0; $i < count($organizerDetails); $i++) {
        
         //$orgName = $organizerDetails[$i]["EmpName"];
        // $orgSapid = $organizerDetails[$i]["SapId"];
         //$orguserid = $organizerDetails[$i]["UserId"];
         $orguserid = $organizerDetails[$i];
         

        $insQry ="INSERT INTO event_organizer (orgnizer_eventid, organizer_userid ,organizer_clientid,isActive ,CreatedDate ,CreatedBy ,UpdateDate ,UpdatedBy ) VALUES ('$EventId', '$client' , '$orguserid', '1','$Created_Date','$Created_By','$UpdatedDate','$Updated_By')";
         $insRes = mysqli_query($conn,$insQry);
         $organizerId = mysqli_insert_id($conn);

    }

    $errcode = 200;
    $status = "Success";
}else{
    $errcode = 404;
    $status = "Failure";
}

echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status));

mysqli_close($conn);
?>