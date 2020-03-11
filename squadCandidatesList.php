<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$json = file_get_contents('php://input');
$data = json_decode($json,true);

require_once 'include/dbconnect.php';

				if(isset($data['squad_name'])){
						$squad_name = $data['squad_name'];

						if($squad_name){
							$squad_condtion = "where s.SquadName = '$squad_name'";
						}else{
							$squad_condtion ='';
						}
				}else{
							$squad_condtion ='';
				}
			
				 $query = "select s.SquadName,cr.EmpName,cr.Skills,cr.Expereince from squad s
				left outer join squad_candidates cs on s.ID = cs.SquadID
				left outer join candidate_registration cr on cs.CandidateID = cs.CandidateID $squad_condtion ";
				
				
				$result = mysqli_query($conn,$query);
				$durationset = array();
			
			if(mysqli_num_rows($result) > 0){
				while ($durationrow = mysqli_fetch_assoc($result)){
					$durationset[] = $durationrow;
				} 
				$errcode = 200;
				$status = "Success";
			}else{
				$errcode = 500;
				$status = "Failure";
			}

			echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"arrRes" => $durationset));
			
   
   
mysqli_close($conn);
?>