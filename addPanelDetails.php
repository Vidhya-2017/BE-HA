<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);
require_once 'include/dbconnect.php';

 if(isset($data)){  
        $eventId  = $data['EventId'];
        $clientId  = $data['ClientId'];
        $puser_id = $data['Userdetail'];

        //{"uid":"11","ispanel":true},
       /* $panelName = $data['PanelName'];
        $panelMobile = $data['PanelMobile'];
        $panelEmail = $data['PanelEmail']; */
        $created_date = date("Y-m-d H:i:s");
        $created_by	= $data['CreatedBy'];
        $updated_by	= $data['UpdatedBy'];
        $updated_date =  date("Y-m-d H:i:s");
      
   
        $selpnlcnt = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM event_panel_list WHERE  pl_eventId='$eventId'"));
        if($selpnlcnt > 0){
            $delpnl =mysqli_query($conn,"DELETE FROM event_panel_list  WHERE  pl_eventId='$eventId' ");
         //  $update = mysqli_query($conn,"UPDATE event_panel_list SET isActive='0' WHERE pl_eventId='$eventId'");
        }

        $selorganisercnt = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `event_organizer` WHERE `orgnizer_eventid`='$eventId'"));
        if($selorganisercnt > 0){
            $delorganiser =mysqli_query($conn,"DELETE FROM event_organizer  WHERE  orgnizer_eventid='$eventId' ");
        }

        $cnt = 0;
        for($i = 0; $i < count($puser_id); $i++) {
            $user_id = $puser_id[$i]["uid"];
            $user_ispanel = $puser_id[$i]["ispanel"];
            if($user_ispanel == true){
             $Query = "INSERT INTO `event_panel_list`(`pl_eventId`, `pl_clientId`,`pl_userid`, `createdBy`, `createdDate`, `updatedBy`, `updatedDate`,`isActive`) VALUES ($eventId,$clientId,'$user_id', $created_by, '$created_date', $updated_by, '$updated_date','1')";
            }else {
             $Query = "INSERT INTO `event_organizer`(`orgnizer_eventid`, `organizer_clientid`,`organizer_userid`, `CreatedBy`, `CreatedDate`, `UpdatedBy`, `UpdateDate`,`isActive`) VALUES ('$eventId','$clientId','$user_id', $created_by, '$created_date', $updated_by, '$updated_date','1')";
            }
          
            $result = mysqli_query($conn,$Query);
            if(mysqli_insert_id($conn)>0){
                $cnt ++;
            } 
        }

        if($cnt > 0 ){
            
                $errcode = 200;
                $status = "Success";            
                $message = "Panel Created Successfully";
          

            }else{
                $errcode = 404;
                $status = "Failure";            
                $message = "Error in Creating Panel";
            }
  
 }else{
     $errcode = 404;
     $status = "Oops went wrong!!!";
     $message = "No data Found!!!";
}
echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,'message'=>$message));
mysqli_close($conn);

?>