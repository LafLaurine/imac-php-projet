<?php
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

//requête qui sélectionne les publications et les ordonnes par nombre de like (+ de like = 1ère publi)
$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT *
	FROM publication
	ORDER BY id_publication = (SELECT MAX(id_publication)
							FROM user_liked) DESC
SQL
);

$stmt->execute();
$like = [];
//tant qu'il y a des publications on ajoute au tableau
while(($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
	array_push($like,$row);
}
//si tableau vide, message d'erreur
if(empty($like)) {
	echo json_encode(array("error" => "Missing like"));
	http_response_code(422);
	exit();
}
//on renvoie les réponses de la requête en JSON pour que le client puisse récupérer les informations et les afficher
echo json_encode($like,JSON_UNESCAPED_UNICODE);
exit();
