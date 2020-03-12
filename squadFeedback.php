<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once 'include/dbconnect.php';

$json = file_get_contents('php://input');
$data = json_decode($json,true);

if (!file_exists('./images')) {
    mkdir('./images', 0777, true);
}

if( $data !=""){
    $squadID = $data["squadID"];
    $imageStr =$data["imageStr"];
    $techSkill = $data["techSkill"];
    $logcSkill = $data["logcSkill"];
    $commSkill = $data["commSkill"];
    $feedback =$data["feedback"];

    $query = "INSERT INTO squad_feedback (squad_id, imagestr, technical_skill , logical_skill , communication_skill , feedbackTxt , 
isActive ) VALUES ('$squadID', '$imageStr', '$techSkill', '$logcSkill', '$commSkill', '$feedback', '1'); ";
	$result = mysqli_query($conn,$query);
    if(mysqli_insert_id($conn)>0){
       $imgdata = base64_decode($imageStr);
        $im = imagecreatefromstring($imgdata);
        if ($im !== false) {
            header('Content-Type: image/jpg');
            //imagejpeg($im,"http://40.121.205.179/hackeranchor/images/".$squadID.".jpg");
            imagejpeg($im, "./images/".$squadID.".jpg");
            imagedestroy($im);
             $errcode = 200;
             $status = "Success";
        }
        else {
            echo 'An error occurred.';
             $errcode = 500;
             $status = "Failure";
        }

      
    }else{
        $errcode = 500;
        $status = "Failure";
    }

}else{

    $errcode = 500;
    $status = "Failure data not received";
}

echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status));
mysqli_close($conn);



?>