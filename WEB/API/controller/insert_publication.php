<?php

// headers
header("Content-Type: application/json; charset=UTF-8");

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


if (!isset($input) || empty($input)) {
	echo json_encode(array("error" => "Missing params ".$input));
	http_response_code(422);
}
else {
	$json_obj = json_decode($input,true);

	if(!isset($json_obj['titre']))
	{
		echo json_encode(array("error" => "Missing title"));
		exit();
	}

	if(!isset($json_obj['topic']))
	{
		echo json_encode(array("error" => "Missing topic"));
		exit();
	}

	if(!isset($json_obj['content']))
	{
		echo json_encode(array("error" => "Missing content"));
		exit();
	}


	$title = $json_obj['titre'];
	$id_topic = $json_obj['topic'];
	$id_user = $_SESSION['username'];
	$username = $_SESSION['id_user'];
	$content = $json_obj['content'];
	$date = date('Y-m-d');

	$stmt = MyPDO::getInstance()->prepare(<<<SQL
	INSERT INTO publication(titre_publication, date_publication, id_topic, id_user content)
	VALUES (:titre_publication, :date_publication, :id_topic, :id_user :content)
SQL
);
	$stmt->bindParam(':titre_publication',$title);
	$stmt->bindParam(':date_publication',$date);
	$stmt->bindParam(':id_topic',$id_topic);
	$stmt->bindParam(':id_user',$id_user);
	$stmt->bindParam(':content',$content);
	$stmt->execute();
	
	$id_publi = MyPDO::getInstance()->lastInsertId();

		if(!isset($json_obj['fileName']) || !isset($json_obj['fileType']) || !isset($json_obj['fileSize']) )
	{
		echo json_encode(array("error" => "Missing File"));
		exit();
	} 

	$fileName = $json_obj['fileName'];
	$fileType = $json_obj['fileType'];
	$fileSize=$json_obj['fileSize'];

	$stmt2 = MyPDO::getInstance()->prepare(<<<SQL
	INSERT INTO image(id_publication, nom_image, taille_image, type_image)
	VALUES (:id_publication, :nom_image, :taille_image, :type_image)
SQL
);
	$stmt2->bindParam(':id_publication',$id_publi);
	$stmt2->bindParam(':nom_image',$fileName);
	$stmt2->bindParam(':taille_image',$fileSize);
	$stmt2->bindParam(':type_image',$fileType);
	$stmt2->execute();
	
	$resp = array("id_publication" => $id_publi, "titre_publication" => $title, "date_publication" => $date, "id_user" => $id_user, "username" => $username, "content" => $content);
	echo json_encode($resp);
}
?>