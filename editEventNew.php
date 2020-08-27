<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$json = file_get_contents('php://input');
$data = json_decode($json,true);


require_once 'include/dbconnect.php';

$eventID        = $data['EventId'];
$event_name		= $data['EventName'];
$event_date     =  $data['eventdate'];
$event_location = $data['Location'];
$duration		= $data['Duration'];
//$skills 		= $data['Skills'];
$skills      = implode(",",$data['Skills']);
$client = $data['Client'];
// $assessment_scale = $data['AssessmentScale'];
$assessment_scale = implode(",",$data['AssessmentScale']);
// $problem_solving_skills_tested = ($data['ProblemSolvingSkillTested'] == true ? 1 : 0);
// $technical_skills_tested = ($data['TechnicalSkilslsTested'] == true ? 1 : 0);
// $communication_skills_tested = ($data['CommunicationSkillIsTested'] == true ? 1 : 0);
// $logical_skills_tested 	= ($data['LogicalSkillsIsTested']== true ? 1 : 0);
$Created_Date				= date('Y-m-d h:i:s');
$Created_By	 			    = $data['CreatedBy'];
$UpdatedDate = date('Y-m-d h:i:s');
$Updated_By	 = $data['UpdatedBy'];

$organizerDetails = $data['OrganizerData'];
$competancyLevelDetails = $data['CompetancyLevelData'];
$otherAssessmentDetails = $data['OtherAssessmentData'];


$selectorg = mysqli_query($conn,"select * from event_organizer where orgnizer_eventid='$eventID'");
 if(mysqli_num_rows($selectorg) > 0){
     $Delet =  mysqli_query($conn,"DELETE FROM event_organizer WHERE orgnizer_eventid='$eventID'");
 }


 $selectCompetancy = mysqli_query($conn,"SELECT `ID` FROM `competancy_rating_event` WHERE `EventID`=$eventID");
 if(mysqli_num_rows($selectCompetancy) > 0){
     
     $DeleteCompetancy =  mysqli_query($conn,"DELETE FROM competancy_rating_event WHERE EventID='$eventID'");
 }

 $selectOtherAssessment = mysqli_query($conn,"SELECT `eas_id` FROM `event_otherassessmentscale` WHERE `eas_eventID` = $eventID");
 if(mysqli_num_rows($selectOtherAssessment) > 0){
     
     $DeleteOtherAssessment =  mysqli_query($conn,"DELETE FROM event_otherassessmentscale WHERE eas_eventID='$eventID'");
 }


$query = "UPDATE register_event SET EventName='$event_name', Location='$event_location', Date='$event_date', Duration='$duration', Skills='$skills', Client='$client', AssessmentScale ='$assessment_scale', UpdatedDate='$UpdatedDate' ,UpdatedBy='$UpdatedDate' WHERE EventID='$eventID' "; 
$result = mysqli_query($conn,$query);

// ProblemSolvingSkillTested ='$problem_solving_skills_tested', TechnicalSkilslsTested='$technical_skills_tested' , CommunicationSkillIsTested='$communication_skills_tested' , LogicalSkillsIsTested='$logical_skills_tested',

    if(count($competancyLevelDetails) > 0){
        for($i = 0; $i < count($competancyLevelDetails); $i++) {
       
            $competancylevelid = $competancyLevelDetails[$i];
            
    
           $insQry ="INSERT INTO `competancy_rating_event`(`EventID`, `CompetancyID`, `CreatedBy`, `CreatedDate`, `UpdatedBy`, `UpdatedDate`) VALUES ('$eventID','$competancylevelid','$Created_By','$Created_Date','$Updated_By','$UpdatedDate')";
            $insRes = mysqli_query($conn,$insQry);
            $competancyEventId = mysqli_insert_id($conn);
    
       }

    }

    for($i = 0; $i < count($otherAssessmentDetails); $i++) {
       
        $otherassessmentid = $otherAssessmentDetails[$i];

        $insAss="INSERT INTO `event_otherassessmentscale`(`eas_eventID`, `eas_OtherAssessId`, `createdBy`, `createdDate`, `updatedBy`, `updatedDate`) VALUES ('$eventID','$otherassessmentid','$Created_By','$Created_Date','$Updated_By','$UpdatedDate')";
        $insRes = mysqli_query($conn,$insAss);
        $competancyEventId = mysqli_insert_id($conn);
    }


    if(count($organizerDetails) > 0 ){

        for($i = 0; $i < count($organizerDetails); $i++) {
            
            $orgUserid = $organizerDetails[$i];
            $insQry ="INSERT INTO event_organizer (orgnizer_eventid ,organizer_userid ,isActive ,CreatedDate ,CreatedBy ,
            UpdateDate ,UpdatedBy ) VALUES ('$eventID','$orgUserid','1','$Created_Date','$Created_By','$UpdatedDate','$Updated_By')";
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