<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);
require_once 'include/dbconnect.php';

if(isset($data["eventId"])){
    $evntId = $data["eventId"];
    if( $data["eventId"]!='' ){
    
        $query = "SELECT b.user_id, b.first_name,b.last_name ,b.contact_no,b.email FROM event_panel_list a INNER JOIN user_login b  on 	a.pl_userid = b.user_id WHERE b.isActive=1 and a.isActive=1 and a.pl_eventId ='$evntId' ";

        $result = mysqli_query($conn,$query);
        $panelset = array();
        if(mysqli_num_rows($result) > 0){
            while ($panelrow = mysqli_fetch_assoc($result)){
                $panelrow['ispanel'] = true;
                $panelset[] = $panelrow;
                
            }
        }
    
        $organiserquery = "SELECT b.user_id, b.first_name,b.last_name ,b.contact_no,b.email FROM event_organizer a INNER JOIN user_login b  on 	a.organizer_userid = b.user_id WHERE b.isActive=1 and a.isActive=1 and a.orgnizer_eventid ='$evntId' ";

        $organiserresult = mysqli_query($conn,$organiserquery);
        $organiserset = array();
        if(mysqli_num_rows($organiserresult) > 0){
            while ($organiserrow = mysqli_fetch_assoc($organiserresult)){
                $organiserrow['ispanel'] = false;
                $organiserset[] = $organiserrow;  
            } 
        }   
       
        $paneldetails=array_merge($panelset,$organiserset);
        if(!empty($paneldetails)){
            $errcode = 200;
            $status = "Success";
        }else{
            $errcode = 404;
            $status = "No Result";
            $paneldetails='';
        }
    }else{
        $errcode = 404;
        $status = "Event ID should not null";
        $paneldetails='';
   }
}

echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"arrRes" => $paneldetails));

mysqli_close($conn);
?>