<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);

require_once 'include/dbconnect.php';
//$organizerautoid = 1;
				if(isset($data['organizer_autoid'])){
                    $organizerautoid = $data['organizer_autoid'];
                   
                        $query = "update event_organizer set isActive=0  where organizer_autoid = '$organizerautoid' ";
                        $result = mysqli_query($conn,$query);
                    if($result){
                        $errcode = 200;
				        $status = "Success";
                    }                
                else{
                    $errcode = 404;
                    $status = "Failure";
                }
            }

            else{
				$errcode = 404;
				$status = "There is no auto organizer id";
			}

			echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status));
			
   
   
mysqli_close($conn);
?>