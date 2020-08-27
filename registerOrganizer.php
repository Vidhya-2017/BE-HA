<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);

require_once 'include/dbconnect.php';
if(isset($data)){
    $sap_id	= $data['SapID'];
    $orgName= $data['OrgName'];
    $evntID= $data['EventID'];
    $isAct= $data['isActive']=='true'? 1 : 0 ;    
    $Cdate= $data['createdDt'];
    $Cby= $data['createdBy'];
    $Udate= $data['UpdatedDt'];
    $Uby= $data['UpdatedBy'];


    $insQry ="INSERT INTO event_organizer (organizer_sapid ,orgnizer_name ,orgnizer_eventid ,isActive ,CreatedDate ,CreatedBy ,
    UpdateDate ,UpdatedBy ) VALUES ('$sap_id','$orgName','$evntID','$isAct','$Cdate','$Cby','$Udate','$Uby')";
    $insRes = mysqli_query($conn,$insQry);
    $organizerId = mysqli_insert_id($conn);

    if($organizerId > 0){
        $errcode = 200;
        $status = "Success";
        $OrgId = $organizerId;
    }else{
        $errcode = 500;
        $status = "Failure";
        $OrgId = "";
    }


}
echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"OrgId"=>$OrgId));
mysqli_close($conn);	
 ?>