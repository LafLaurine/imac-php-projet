<?php

// headers
header("Content-Type: application/text; charset=UTF-8");

session_start();
include_once "../data/MyPDO.spottimac.include.php";
if (!isset($_SESSION["id_user"])){
	echo json_encode(array('message' => 'User non connecté'));
	exit();
}

// check HTTP method
$method = strtolower($_SERVER['REQUEST_METHOD']);

if ($method !== 'post') {
	http_response_code(405);
	echo json_encode(array('message' => 'This method is not allowed.'));
	exit();
}

// response status
http_response_code(200);
$input = file_get_contents('php://input');

if(!isset($json_obj['fileName']) || !isset($json_obj['fileType']) || !isset($json_obj['fileSize']) )
{
	echo json_encode(array("error" => "Missing file"));
	exit();
}
	
else {
	$fileName = $json_obj['fileName'];
	$fileType = $json_obj['fileType'];
	$fileSize= $json_obj['fileSize'];

	$uploaddir = './uploads/';
	$uploadfile = $uploaddir . basename($fileName);
	move_uploaded_file($json_obj['formData']['file'],$uploadfile);

	$stmt2 = MyPDO::getInstance()->prepare(<<<SQL
	INSERT INTO image(id_publication, nom_image, taille_image, type_image)
	VALUES (:id_publication, :nom_image, :taille_image, :type_image, :blob_image)
SQL
);
	$stmt2->bindParam(':id_publication',$id_publi);
	$stmt2->bindParam(':nom_image',$fileName);
	$stmt2->bindParam(':taille_image',$fileSize);
	$stmt2->bindParam(':type_image',$fileType);
	$stmt2->bindParam(':blob_image',LOAD_FILE($uploadfile));
	$stmt2->execute();	
	$resp = array("id_publication" => $id_publi, "nom_image" => $tifileName, "taille_image" => $fileSize, "type_image" => $fileType);
	echo json_encode($resp);
	exit();
}	?>