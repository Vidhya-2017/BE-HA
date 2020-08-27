<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);
require_once 'include/dbconnect.php';

 if(isset($data)){


    
    $emailId = $data['UserEmail'];
    

    $Query = "SELECT `email` FROM `user_login` WHERE `email` ='". $emailId."'";
    $result = mysqli_query($conn,$Query);
    if(mysqli_num_rows($result) == 0){
       
        $userFirstName  = $data['UserFirstName'];
        $userLastName  = $data['UserLastName'];
        $userMobile = $data['UserMobile'];
        $userPassword = $data['UserPassword'];
        $userSAPID =  isset( $data['UserSAPID']) ? $data['UserSAPID'] : '';
        $userEmail = $data['UserEmail'];
        $created_date = $data['CreatedDate'];
        $created_by	= $data['CreatedBy'];
        $updated_by	= $data['UpdatedBy'];
        $updated_date = $data['UpdatedDate'];
        $isAdmin = $data['isAdmin'] ==true ? 1 : 0 ;

        $fp_details = isset($data['FPDetails'])? $data['FPDetails'] : "" ;


         $Query = "INSERT INTO `user_login`(`first_name`, `last_name`, `contact_no`, `email`, `password`, `sapID`, `createdBy`, `createdDate`, `updatedBy`, `updatedDate`, `isAdmin`) VALUES ('$userFirstName','$userLastName','$userMobile','$userEmail','$userPassword','$userSAPID','$created_by','$created_date','$updated_by','$updated_date',$isAdmin)";

        $result = mysqli_query($conn,$Query);
        $userID = mysqli_insert_id($conn);

        if($fp_details !=""){
            foreach($fp_details as $arrdata){
                
                $quesid = $arrdata["qn_id"];
                $answer = $arrdata["answer"];

                $Query123 = "INSERT INTO `fp_userdetails`(`UserID`, `QuestionID`, `Answer`, `CreatedDate`, `CreatedBy`, `UpdatedDate`, `UpdatedBy`) VALUES ('$userID', '$quesid','$answer','$created_date','$created_by','$updated_date','$updated_by')";

                $result = mysqli_query($conn,$Query123);
            }
        }

        if(mysqli_insert_id($conn)>0){
                $errcode = 200;
                $status = "Success";            
                $message = "User Created Successfully";
           

        }else{
            $errcode = 404;
            $status = "Failure";            
            $message = "Error in Creating User";
        }
    
        
    }else{
        $errcode = 404;
        $status = "Failure";
        $message = "EmailId Already Exist";
    }
    
 }else{
     $errcode = 404;
     $status = "Oops went wrong!!!";
     $message = "No data Found!!!";

}
echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,'message'=>$message));
mysqli_close($conn);

?>