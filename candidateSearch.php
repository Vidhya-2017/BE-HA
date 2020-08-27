<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);
require_once 'include/dbconnect.php';

if(isset($data)){
    $searchData = $data['searchData'];
    $eventID = $data['EventID'];

    $query = "SELECT c.`ID`, c.`EmpID`, c.`EmpName`, c.`Gender`, c.`Skills`, c.`AdditionalSkills`, c.`EmailId`, c.`StartDate`, c.`ContactNo`, c.`Expereince`, c.`RelevantExperience`, c.`DNAPassScore`,c.`isExternal`,  c.`candidate_image` FROM `candidate_registration` c INNER JOIN `candidate_event` e ON c.`ID` = e.`CanidateID` where (c.EmpID LIKE '%$searchData%' OR c.EmailId LIKE '%$searchData%' OR c.EmpName LIKE '%$searchData%' OR c.ContactNo LIKE '%$searchData%') and e.EventID=$eventID ";
    $result = mysqli_query($conn,$query);
    $data = array();
    if(mysqli_num_rows($result) > 0){
        while ($returneddata = mysqli_fetch_assoc($result)){
            $returneddata['Skills']=explode(",",@$returneddata['Skills']);
            $data[] = $returneddata;
        } 

        $errcode = 200;
        $status = "Success";
    }else{
        $errcode = 404;
        $status = "No Result";
    }
    
}else{
    $errcode = 404;
    $status = "Oops went wrong!!!";
}

echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"data" => $data));

mysqli_close($conn);
?>