<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);



require_once 'include/dbconnect.php';
				if(isset($data['squad_id'])){
						 $squad_id = $data['squad_id'];

						if($squad_id){
							$squad_condtion = "and s.ID = '$squad_id'";
						}else{
							$squad_condtion ='';
						}
				}else{
							$squad_condtion ='';
				}
			
				 $query = "select cr.ID,cr.EmpID,cr.EmpName,s.SquadName, GROUP_CONCAT(DISTINCT sk.skills) SkillName,cr.Expereince from squad s
				left outer join squad_candidates cs on s.ID = cs.SquadID
				left outer join candidate_registration cr on cs.CandidateID = cr.ID 
				left outer join skills sk on FIND_IN_SET(sk.SkillId, cr.Skills) 
				where cr.ID is NOT NULL $squad_condtion  group by cr.ID";
				
				
				$result = mysqli_query($conn,$query);
				$durationset = array();
			
			if(mysqli_num_rows($result) > 0){
				while ($durationrow = mysqli_fetch_assoc($result)){
					$durationset[] = $durationrow;
				} 
				$errcode = 200;
				$status = "Success";
			}else{
				$errcode = 404;
				$status = "Failure";
			}

			echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"arrRes" => $durationset));
			
   
   
mysqli_close($conn);
?>