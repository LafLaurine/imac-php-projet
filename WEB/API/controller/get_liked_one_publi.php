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
$id_user = $_SESSION['id_user'];

//vérifie que l'on a l'id de la publication (dans l'url qu'on récupère en get)
if(!empty($_GET['id'])){
    $id_publi = $_GET['id'];
}

//si on ne le trouve pas, on renvoie un message d'erreur
else {
    echo json_encode(array("error" => "Missing id publi"));
	http_response_code(422);
}

$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT *
	FROM user_liked
	WHERE id_user = :id_user
    AND id_publication = :id_publication
SQL
);
$stmt->bindParam(':id_user',$id_user);
$stmt->bindParam(':id_publication',$id_publi);
$stmt->execute();
$user_liked = [];

//on met le résultat de la requête dans un tableau 
//et on la renvoie
while (($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
	array_push($user_liked,$row); 
}
if(empty($user_liked)) {
	echo json_encode(array("error" => "Missing publication"));
	http_response_code(422);
}

//on renvoie les réponses de la requête en JSON pour que le client puisse récupérer les informations et les afficher
echo json_encode($user_liked,JSON_UNESCAPED_UNICODE);
exit();
