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
       // $data = array(array(' '=>'Aahana@mail.com','QnID'=>1,'Answer'=>'asasd'),array('EmailID'=>'Aahana@mail.com','QnID'=>3,'Answer'=>'asdasd'));
       //print_r($data[0]['EmailID']);die;
      
        $emailId=$data[0]['EmailID'];

        $Query = "SELECT `user_id` FROM `user_login` WHERE `email` ='". $emailId."'";
       
        $result1= mysqli_query($conn,$Query);
        $result = mysqli_fetch_assoc($result1);

        $QueryQn = "SELECT u.`QuestionID`, u.`answer`, q.`Question` FROM `fp_userdetails` u,`fp_questions` q WHERE 1 and u.`QuestionID` = q.`ID` and u.`UserID`=".$result['user_id']." order by QuestionID ASC";
    
        $questionresult = mysqli_query($conn,$QueryQn);
        $questionData=[];
        while ( $questionrow = mysqli_fetch_assoc($questionresult)){
             $questionData [] =  $questionrow;
        }

       // $check=array();
        foreach($questionData as $value){
           // print_r($value);
            foreach($data as $valuedata){
                //print_r($valuedata);die;
                if(($value['QuestionID'] == $valuedata['QnID']) && ($value['answer'] == $valuedata['Answer'])){
                    $check[]='true';
                }
            }
        }

        if(count($check) == 2)
        {
            $errcode = 200;
            $status = "Success";            
            $message = "User Security Question Verified Successfully";
        } else {
             
            $errcode = 404;
            $status = "Failure";            
            $message = "User Entered Wrong Security Question Answer";
            throw new Exception('User Entered Wrong Security Question Answer');
        }

        
    }else{ 
        $errcode = 404;
        $status = "Oops something went wrong!!!";            
        $message = "Some error occured in sending data";
        throw new Exception('Some error occured in sending data');

    }
}
catch (exception $e) {
    mysql_log( $e->getMessage(),mysqli_errno($conn),$conn);
}
echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"message" => $message));
mysqli_close($conn);

?>