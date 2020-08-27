<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

require_once 'include/dbconnect.php';
try {
    
    $query = "SELECT `ID`, `Question` FROM `fp_questions` WHERE `isActive`=1";
    $result = mysqli_query($conn,$query);
    $skillset = array();
    if(mysqli_num_rows($result) > 0){
        while ($skillrow = mysqli_fetch_assoc($result)){
            $skillset[] = $skillrow;
        } 
        $errcode = 200;
        $status = "Success";
    }else{
        $errcode = 404;
        $status = "Failure";
        throw new Exception(mysqli_error($conn)); 
    }
}
catch (exception $e) {
    mysql_log( $e->getMessage(),mysqli_errno($conn),$conn);
}

echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"arrRes" => $skillset));

mysqli_close($conn);
?>