<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$json = file_get_contents('php://input');
$data = json_decode($json,true);

require_once 'include/dbconnect.php';

$event_id   =   $data['EventID'];
$query      =   "UPDATE register_event SET isClosed='1' ";
$result     =   mysqli_query($conn,$query);

 $sql       =   "SELECT B.ID,B.isActive,A.ID,A.CanidateID,A.EventID,A.isSelected FROM `candidate_event` as A, `candidate_registration` as B, `register_event` as C WHERE A.EventID = C.EventID AND A.CanidateID  = B.ID AND A.isSelected = '0' AND B.isSelected = '0' ";
 $listings  =   mysqli_query($conn,$sql);
 $not_Selected_lists = array();
 if (mysqli_num_rows($listings) > 0) {
     while ($rows = mysqli_fetch_assoc($listings)) {
          $not_Selected_lists = $rows['ID'];
          $not_Selected_lists1 = $rows['ID'];
          $event_query       =   "UPDATE candidate_event SET isActive='0' WHERE EventID = '".$data['EventID']."' AND ID='".$not_Selected_lists."'  ";
          $response          =   mysqli_query($conn,$event_query);

          $candidate_query   =   "UPDATE candidate_registration SET isActive='0' WHERE isSelected = '0' AND ID='".$not_Selected_lists1."'  ";
          $response1         =   mysqli_query($conn,$candidate_query);
     }
     $errcode = 200;
     $status = "Success";
 } else {
     $errcode = 500;
     $status = "Failure";
 }
echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status));
mysqli_close($conn);
 
