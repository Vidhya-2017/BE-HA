<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);
require_once 'include/dbconnect.php';

if(isset($data)){

    $contactNo  = $data['ContactNo'];
    $eventID = $data['EventID'];

	$data=[];
	
    $Query = " SELECT c.`ID`, c.`EmpID`, c.`EmpName`, c.`Gender`, c.`Skills`, c.`AdditionalSkills`, c.`EmailId`, c.`StartDate`, c.`ContactNo`, c.`Expereince`, c.`RelevantExperience`, c.`isExternal`,  c.`candidate_image` FROM `candidate_registration` c INNER JOIN `candidate_event` e ON c.`ID` = e.`CanidateID` where c.ContactNo ='". $contactNo."' and e.EventID=$eventID ";
    $result = mysqli_query($conn,$Query);
	
	$details = mysqli_fetch_assoc($result);

    
    $data=$details;
    $data['Skills']=explode(",",@$details['Skills']);

   
    if(mysqli_num_rows($result) != 0){
        $errcode = 200;
        $status = "Success";
		$datas = $data;
    }else{
         $errcode = 200;
        $status = "Success";
		$datas = 0;
    }
    
}else{
    $errcode = 404;
    $status = "Oops went wrong!!!";
}
echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"data"=>$datas));
mysqli_close($conn);

?>