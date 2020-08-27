<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);
require_once 'include/dbconnect.php';
try {

    if(isset($data)){
        $emailId = $data['UserEmail'];
        // $emailId = 'Aahana@mail.com';

        $Query = "SELECT `user_id` FROM `user_login` WHERE `email` ='". $emailId."'";
       
        $result = mysqli_query($conn,$Query);

        if(mysqli_num_rows($result) > 0){
            while ($durationrow = mysqli_fetch_assoc($result)){

                $QueryQn = "SELECT u.`QuestionID`, q.`Question` FROM `fp_userdetails` u,`fp_questions` q WHERE 1 and u.`QuestionID` = q.`ID` and u.`UserID`=".$durationrow['user_id']." order by rand() limit 2";
                
                $questionresult = mysqli_query($conn,$QueryQn);
                $questionData=[];
                while ( $questionrow = mysqli_fetch_assoc($questionresult)){
                     $questionData [] =  $questionrow;
                }
            }
            $errcode = 200;
            $status = "success";
        }else{
            $errcode = 404;
            $status = "No User found";
            throw new Exception('No User data found'); 
        }
        
    }else{
        $errcode = 404;
        $status = "Oops something went wrong!!!";
        throw new Exception('Some error occured in sending data'); 

    }
}
catch (exception $e) {
    mysql_log( $e->getMessage(),mysqli_errno($conn),$conn);
}
echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"arrRes" => $questionData));
mysqli_close($conn);

?>