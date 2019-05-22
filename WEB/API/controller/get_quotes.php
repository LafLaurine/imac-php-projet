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
//requête pour selectionner tout ce qui se trouve dans la table quotes
$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT *
	FROM quotes
	ORDER BY RAND()
	LIMIT 1;
SQL
);

$stmt->execute();
$quotes = [];
//tant qu'il y a une quote on ajoute au tableau
while (($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
	array_push($quotes,$row); 
}
//si vide, message d'erreur retourné
if(empty($quotes)) {
	echo json_encode(array("error" => "Missing quotes"));
	http_response_code(422);
	exit();
}
//on renvoie les réponses de la requête en JSON pour que le client puisse récupérer les informations et les afficher
echo json_encode($quotes,JSON_UNESCAPED_UNICODE);
exit();
