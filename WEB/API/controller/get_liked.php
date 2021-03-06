<?php
//start session car like associé à user
session_start();
// headers
header("Content-Type: application/json; charset=UTF-8");

// check HTTP method
$method = strtolower($_SERVER['REQUEST_METHOD']);
if ($method !== 'get') {
	http_response_code(405);
	echo json_encode(array('message' => 'This method is not allowed.'));
	exit();
}

// include data
include_once "../data/MyPDO.spottimac.include.php";

// response status
http_response_code(200);

//on récupère l'id de l'user
//pour pouvoir récupèrer les publications que cette user a liké
if(!empty($_SESSION['id_user']))
{
	$id_user = $_SESSION['id_user'];
	$stmt = MyPDO::getInstance()->prepare(<<<SQL
		SELECT *
		FROM user_liked
		WHERE id_user = :id_user;
SQL
);
	$stmt->bindParam(':id_user',$id_user);
	$stmt->execute();
	$user_liked = [];

	//on met le résultat de la requête dans un tableau 
	//et on la renvoie
	while (($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
		array_push($user_liked,$row); 
	}
	if(empty($user_liked)) {
		echo json_encode(array("error" => "Missing publications"));
		http_response_code(422);
		exit();
	}

	//on renvoie les réponses de la requête en JSON pour que le client puisse récupérer les informations et les afficher
	echo json_encode($user_liked,JSON_UNESCAPED_UNICODE);
}

else {
	echo json_encode(array("error" => "Missing id_user"));
	http_response_code(422);
	exit();
}
exit();
