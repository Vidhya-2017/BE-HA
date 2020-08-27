<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);

require_once 'include/dbconnect.php';

				if(isset($data['event_id'])){
						$eventid = $data['event_id'];
                }
                else{
							$eventid ='';
				}
			
				$query = "select * from event_organizer where orgnizer_eventid = '$eventid' AND isActive=1 ";
				
				
				$result = mysqli_query($conn,$query);
				$eventorganizerset = array();
			
			if(mysqli_num_rows($result) > 0){
				while ($eventorganizerrow = mysqli_fetch_assoc($result)){
					$eventorganizerset[] = $eventorganizerrow;
				} 
				$errcode = 200;
				$status = "Success";
			}else{
				$errcode = 404;
				$status = "No Result";
			}

			echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"arrRes" => $eventorganizerset));
			
   
   
mysqli_close($conn);
?>