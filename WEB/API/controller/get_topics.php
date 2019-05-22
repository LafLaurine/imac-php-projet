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

$topics;
//si on clique sur catégorie, alors on cherche topic correspondant à cette catégorie, sinon on renvoie tous les topics
if(!empty($_GET['publicategorie'])) {
	$topics= tri_by_cat($_GET['publicategorie']);
} else {
	$topics= allTopic();
}

//génère topics correspondants à la catégorie choisie
function tri_by_cat($id)
{
	// response status
	http_response_code(200);
	//sélectionne tous les topics d'une catégorie 
	$stmt = MyPDO::getInstance()->prepare(<<<SQL
		SELECT *
		FROM topic
		LEFT JOIN categories
		ON topic.id_categorie = categories.id_categorie
		WHERE categories.id_categorie = :publicategorie
		ORDER BY nom_topic
SQL
	);

	$stmt->execute(array(':publicategorie'=>$id));
	return $stmt;
}

//génère tous les topics
function allTopic() {
	//requête pour sélectionner tout ce qu'il se trouve dans la table topic
	$stmt = MyPDO::getInstance()->prepare(<<<SQL
		SELECT *
		FROM topic
		ORDER BY nom_topic
SQL
);
	$stmt->execute();
	return $stmt;
}

$topic = [];
//tant qu'il y a des topics on ajoute au tableau
while(($row = $topics->fetch(PDO::FETCH_ASSOC))) {
	array_push($topic,$row); 
}
//si tableau vide on envoie un message d'erreur
if(empty($topic)) {
	echo json_encode(array("error" => "Missing topic"));
	http_response_code(422);
	exit();
}
//on renvoie les réponses de la requête en JSON pour que le client puisse récupérer les informations et les afficher
echo json_encode($topic,JSON_UNESCAPED_UNICODE);

exit();
?>