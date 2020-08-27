<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json;");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);


require_once 'include/dbconnect.php';
 
 
 if(isset($data['emp_name'])){
    $emp_name = $data['emp_name'];

    if($emp_name){
        $condition = "and (first_name LIKE '%$emp_name%' or last_name LIKE '%$emp_name%')";
    }else{
        $condition ='';
    }
}else{
        $condition ='';
}

$query = "SELECT `user_id`, `first_name`, `last_name`, `contact_no`, `email`, `password`, `sapID` FROM `user_login` WHERE isActive='1' and isAdmin = 1 $condition";

// , IF(isAdmin=1,'TRUE','FALSE') as isAdmin

$result = mysqli_query($conn,$query);
$employees = [];
    
    if(mysqli_num_rows($result) > 0){
        while ($employeerow = mysqli_fetch_assoc($result)){
          
                $employees[] = $employeerow;
            
        } 
        $errcode = 200;
        $status = "Success";
    }else{
        $errcode = 404;
        $status = "Failure";
    }

    echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"arrRes" => $employees));

mysqli_close($conn);
?>