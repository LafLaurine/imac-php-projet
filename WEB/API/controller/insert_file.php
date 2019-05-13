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

if(!empty($_GET['id_publication'])){
    $id_publication = $_GET['id_publication'];
}

else {
    echo json_encode(array("error" => "Missing id publication"));
	http_response_code(422);
}

$nom_image = "";

$stmt_img = MyPDO::getInstance()->prepare(<<<SQL
	SELECT *
	FROM image
	WHERE id_publication =: $id_publication
SQL
);
$stmt_img->bindParam(':id_publication',$id_publication);
$stmt2->execute();
if(($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
	$nom_image = $row['nom_image'];
}

$uploaddir = './uploads/';
$count;
$uploadfile = $uploaddir . basename($nom_image);
move_uploaded_file($json_obj['formData']['file'],$uploadfile);

$stmt2 = MyPDO::getInstance()->prepare(<<<SQL
	UPDATE image 
	SET blob_image=:blob_image
	WHERE id_publication =: $id_publication
SQL
);
	$stmt2->bindParam(':id_publication',$id_publication);
	$stmt2->bindParam(':blob_image',LOAD_FILE($uploadfile));
	$stmt2->execute();	
	echo json_encode($resp);

	exit();
?>