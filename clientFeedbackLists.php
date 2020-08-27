<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);

require_once 'include/dbconnect.php';

	if (isset($data['event_id'])  && isset($data['candidate_id'])) {
			$eventid 		=	$data['event_id'];                        
			$candidate_id 	=	$data['candidate_id'];
			$user_id  = $data['userID'];
	} else {
			$eventid 		=	'';
			$candidate_id 	=	'';
	}
	//$query = "SELECT A.feedbackTxt,A.sprintLevel,B.technical_skill,B.logical_skill,communication_skill FROM squad_feedback AS A,  squad_feedback_skill as B WHERE A.sid=B.squad_fb_id AND  A.eventId = '$eventid'  AND A.candidate_id = '$candidate_id' ";

	 $query = "SELECT sid As sidz ,feedbackTxt,sprintLevel,sq_final_status,competancy_rating,role FROM squad_feedback WHERE eventId = '$eventid'  AND candidate_id = '$candidate_id' and createdBy='$user_id' and isActive='1'";
	$result = mysqli_query($conn,$query);
	$candidatefeedbackset = array();
	if(mysqli_num_rows($result) > 0){
		while ($candidatefeedbackrow 	=	mysqli_fetch_assoc($result)){
			
			$qruAss = "SELECT os.OtherAssementScaleName as AssName,sq.assessment_value as AssVal FROM squad_feedback_assesment_skill sq INNER JOIN other_assessmentscale os ON sq.other_assessment_scale_id = os.OtherAssessmentId WHERE sq.squad_fb_id ='".$candidatefeedbackrow['sidz']."'";
			$sassResult =   mysqli_query($conn,$qruAss);
			$othskill=[];
			$othskils=[];
			  while ($canAssbrow = mysqli_fetch_array($sassResult)){
				  $assesName =$canAssbrow['AssName'];
				  $assesValue = $canAssbrow['AssVal'];    
				   $othskils = array("ParamName"=>$assesName,"ParamValue"=>$assesValue);  
					array_push($othskill,$othskils); 
			  }
			  $candidatefeedbackrow['AssesmentParams']=$othskill;
			$candidatefeedbackset[] 	=	$candidatefeedbackrow;
		} 
		$errcode 	=	200;
		$status 	=	"Success";
	} else {
		$errcode 	=	404;
		$status 	=	"No Records Found";
	}
echo $result =	json_encode(array("errCode"=>$errcode,"status"=>$status,"arrRes" => $candidatefeedbackset));   
mysqli_close($conn);
?>