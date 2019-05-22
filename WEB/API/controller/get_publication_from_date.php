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

//requête pour selectionner les publications et les ordonner par date (+ récent à + ancien)
$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT *
	FROM publication
	ORDER BY publication.date_publication DESC;
SQL
);
$stmt->execute();
$pub = [];

//tant qu'il y a des publications on ajoute au tableau
while(($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
	array_push($pub,$row);
}

//si tableau vide, envoie un message d'erreur
if(empty($pub)) {
	echo json_encode(array("error" => "Missing publications"));
	http_response_code(422);
	exit();
}
//on renvoie les réponses de la requête en JSON pour que le client puisse récupérer les informations et les afficher
echo json_encode($pub,JSON_UNESCAPED_UNICODE);
exit();
