<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$json = file_get_contents('php://input');
$data = json_decode($json,true);
require_once 'include/dbconnect.php';

 if(isset($data)){

    $emailId = 'tes4t@gmail.com';
    // $emailId = $data['EmailId'];

    $Query = "SELECT `pl_email` FROM `event_panel_list` WHERE `pl_email` ='". $emailId."'";
    $result = mysqli_query($conn,$Query);
    if(mysqli_num_rows($result) == 0){
       
        $eventId  = $data['EventId'];
        $clientId  = $data['ClientId'];
        $panelName = $data['PanelName'];
        $panelPassword = $data['PanelPassword'];
        $panelMobile = $data['PanelMobile'];
        $panelEmail = $data['PanelEmail'];
        $created_date = $data['CreatedDate'];
        $created_by	= $data['CreatedBy'];
        $updated_by	= $data['UpdatedBy'];
        $updated_date = $data['UpdatedDate'];

        $Query = "INSERT INTO `event_panel_list`(`pl_eventId`, `pl_clientId`, `pl_name`, `pl_contactno`, `pl_email`, `createdBy`, `createdDate`, `updatedBy`, `updatedDate`) VALUES ($eventId,$clientId,'$panelName','$panelMobile','$panelEmail', $created_by, '$created_date', $updated_by, '$updated_date')";

        $result = mysqli_query($conn,$Query);
        if(mysqli_insert_id($conn)>0){
            $QueryUser = "INSERT INTO `user_login`(`username`, `password`, `createdBy`, `createdDate`, `updatedBy`, `updatedDate`) VALUES ('$panelEmail','$panelPassword', $created_by, '$created_date', $updated_by, '$updated_date')";
            $resultUser = mysqli_query($conn,$QueryUser);

            if(mysqli_insert_id($conn)>0){
                $errcode = 200;
                $status = "Success";            
                $message = "Panel Created Successfully";
            } else {
                $errcode = 500;
                $status = "Failure";            
                $message = "Error in Creating Panel Login";
            }
            

        }else{
            $errcode = 500;
            $status = "Failure";            
            $message = "Error in Creating Panel";
        }
    
        
    }else{
        $errcode = 500;
        $status = "Failure";
        $message = "EmailId Already Exist";
    }
    
 }else{
     $errcode = 500;
     $status = "Oops went wrong!!!";
}
echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,'message'=>$message));
mysqli_close($conn);

?>