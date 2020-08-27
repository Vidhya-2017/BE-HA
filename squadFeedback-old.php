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
   

    $techSkill = $data["techSkill"];
    $logcSkill = $data["logcSkill"];
    $commSkill = $data["commSkill"];

    $feedback =$data["feedback"]; 
    $imageStr =$data["imageStr"]; 
    $finalStatus = $data["finalStatus"]; 
   
    $selfb = mysqli_num_rows(mysqli_query($conn,"SELECT * from squad_feedback where  eventId='$eventId' AND  squad_id='$squadID' AND candidate_id='$candidate_id' and sprintLevel='$sprintlevel'")); 
    if($selfb > 0){
        $errcode = 200;
        $status = "Already Feedback submitted";
    }else{
         $query = "INSERT INTO squad_feedback (squad_id,candidate_id, sprintLevel, panel_list_id , eventId, imagestr, feedbackTxt ,sq_final_status , isActive ) VALUES ('$squadID', '$candidate_id','$sprintlevel' ,'$panelId' , '$eventId' , '$imageStr',  '$feedback', '$finalStatus', '1'); ";
    
         $result = mysqli_query($conn,$query);
    
        if(mysqli_insert_id($conn)>0){
            $squadfdID = mysqli_insert_id($conn);

            $InsTab = "INSERT INTO squad_feedback_skill (squad_fb_id,technical_skill ,logical_skill ,communication_skill, isActive) VALUES ('$squadfdID', '$techSkill', '$logcSkill', '$commSkill','1')  ";
            $resultSkill = mysqli_query($conn,$InsTab);
                if(mysqli_insert_id($conn)>0){
                    $errcode = 200;
                    $status = "Success";
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