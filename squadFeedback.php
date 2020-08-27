<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

require_once 'include/dbconnect.php';

$json = file_get_contents('php://input');
$data = json_decode($json,true);

if (!file_exists('./images')) {
    mkdir('./images', 0777, true);
}

if( $data !=""){
    $squadID = $data["squadID"];
    $candidate_id =$data["candidate_id"];
    $sprintlevel = $data["sprintLevel"];
    $panelId = isset($data['panelId']) ? $data['panelId'] : '';
    $eventId = isset($data['eventID']) ? $data['eventID'] : '';
    $userId = isset($data['userID']) ? $data['userID'] : '';
    $Created_Date = date('Y-m-d h:i:s');
    $otherAssDetails = $data["otherAssessment"];
   
  
    $feedback =$data["feedback"]; 
    $imageStr =$data["imageStr"]; 
    $finalStatus = $data["finalStatus"]; 
    $competancyrating = $data["competancy_rating"]; 

    
    $role = $data["role"]; 

   
    $selfb = mysqli_num_rows(mysqli_query($conn,"SELECT * from squad_feedback where  eventId='$eventId' AND  squad_id='$squadID' AND candidate_id='$candidate_id' and sprintLevel='$sprintlevel' and createdBy='$userId'")); 
    if($selfb > 0){
        $errcode = 200;
        $status = "Already Feedback submitted";
    }else{
         $query = "INSERT INTO squad_feedback (squad_id,candidate_id, sprintLevel, panel_list_id , eventId, imagestr, feedbackTxt ,sq_final_status, competancy_rating, role, isActive , createdDate , createdBy) VALUES ('$squadID', '$candidate_id','$sprintlevel' ,'$panelId' , '$eventId' , '$imageStr',  '$feedback', '$finalStatus', '$competancyrating', '$role', '1','$Created_Date','$userId'); ";
    
         $result = mysqli_query($conn,$query);
    
            if(mysqli_insert_id($conn)>0){
                $squadfdID = mysqli_insert_id($conn);
                $j=0;
               for($i = 0; $i < count($otherAssDetails); $i++) {
               
                $AssScaleid = $otherAssDetails[$i]['scaleID'];
                $AssScaleval = $otherAssDetails[$i]['scaleVAL'];

                 $InsTab = "INSERT INTO squad_feedback_assesment_skill (squad_fb_id,other_assessment_scale_id ,assessment_value,isActive) VALUES ('$squadfdID', '$AssScaleid', '$AssScaleval','1') ";
                $resultSkill = mysqli_query($conn,$InsTab);
                if(mysqli_insert_id($conn)>0){
                    $j=$j+1;
                }

              }
                 if($j>0){
                      $errcode = 200;
                      $status = "Success";
                  }else{
                      $errcode = 404;
                      $status = "Failure";
                  }
         }else{
                $errcode = 404;
                $status = "Failure";
            }

    }

}else{

    $errcode = 404;
    $status = "Failure data not received";
}

echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status));
mysqli_close($conn);



?>