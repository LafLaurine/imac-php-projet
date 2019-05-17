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

//requête sql pour récupérer tout ce qu'il y a dans la table catégorie
$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT *
	FROM categories
	ORDER BY nom_categorie;
SQL
);

$stmt->execute();
$cat = [];

//tant qu'il y a des catégories, on les ajoute au tableau
while(($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
	array_push($cat,$row); 
}

//si le tableau est vide, c'est qu'il n'y a pas de catégories, il y a donc un problème
if(empty($cat)) {
	echo json_encode(array("error" => "Missing categories"));
	http_response_code(422);
}

//on renvoie les réponses de la requête en JSON pour que le client puisse récupérer les informations et les afficher
echo json_encode($cat,JSON_UNESCAPED_UNICODE);
exit();
