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

//vérifie que l'on a l'id de la publication (dans l'url qu'on récupère en get)
if(!empty($_GET['id'])){
    $id_categorie = $_GET['id'];
}
//si c'est vide on envoie un message d'erreur
else {
    echo json_encode(array("error" => "Missing id categorie"));
	http_response_code(422);
}

//par défaut, "tout" possède l'id 100, on imagine bien qu'on aura pas 100 catégories...
//si ce n'est pas l'id 100, cela veut dire qu'on cible une catégorie particulière
if($id_categorie !=100) {
	//requête SQL pour récupérer ce qu'il y a dans la table publication en fonction de l'id de la categorie
	$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT *
	FROM categories
	INNER JOIN topic ON categories.id_categorie = topic.id_categorie
	INNER JOIN publication ON publication.id_topic = topic.id_topic
	WHERE categories.id_categorie = :id_categorie;
SQL
);
	$stmt->bindParam(':id_categorie',$id_categorie);
	$stmt->execute();
	$cat = [];
	//tant qu'il y a des publications, on ajoute au tableau
	while(($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
		array_push($cat,$row);
	}
	if(!empty($cat)) {
		//on renvoie les réponses de la requête en JSON pour que le client puisse récupérer les informations et les afficher
		echo json_encode($cat,JSON_UNESCAPED_UNICODE);
	}

	else {
		echo json_encode(array("error" => "Missing publications"));
		http_response_code(422);
	}

}

//si l'id n'est pas différent de 100, cela veut dire qu'on a besoin de toutes les publications. 
else {
	include_once "./get_publications.php";
}

exit();
