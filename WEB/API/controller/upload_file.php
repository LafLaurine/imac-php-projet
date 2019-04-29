<?php
// headers
header("Content-Type: application/json; charset=UTF-8");

// check HTTP method
$method = strtolower($_SERVER['REQUEST_METHOD']);

if ($method !== 'post') {
	http_response_code(405);
	echo json_encode(array('message' => 'This method is not allowed.'));
	exit();
}

// include data
include_once "../data/MyPDO.spottimac.include.php";

// response status
http_response_code(200);

// File upload path
$targetDir = "../uploads/";
$fileName = basename($_FILES["up_file"]["name"]);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

if(isset($_POST["validerfichier"]) && !empty($_FILES["up_file"]["name"])){
    // Allow certain file formats
    $allowTypes = array('jpg','png','jpeg','gif','pdf');
    if(in_array($fileType, $allowTypes)){
        // Upload file to server
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
            // Insert image file name into database
            $stmt = MyPDO::getInstance()->prepare(<<<SQL
            INSERT INTO image(nom_image, date_upload)
            VALUES (:nom_image, :date_upload)
SQL
);
        $date = date('Y-m-d');
        $stmt->bindParam(':nom_image',$fileName);
        $stmt->bindParam(':date_upload',$date);
        $stmt->execute();
        
        if($stmt){
            echo json_encode(array("message" => "The file has been upload successfully"));
        } else{
            echo json_encode(array("message" => "Upload fail"));
        } 
    }else{
        echo json_encode(array("message" => "Upload fail"));
        }
    }else{
        echo json_encode(array("message" => "Wrong extension file"));
    }
} else{
    echo json_encode(array("message" => "Select a file to upload"));
}

exit();
?>