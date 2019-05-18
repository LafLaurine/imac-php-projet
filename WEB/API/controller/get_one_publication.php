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
    $id_publi = $_GET['id'];
}

//si on ne le trouve pas, on renvoie un message d'erreur
else {
    echo json_encode(array("error" => "Missing id publi"));
	http_response_code(422);
}

//requête SQL pour récupérer les données de la table publication en fonction de son id
$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT *
    FROM publication
    WHERE id_publication = :id_publi;
SQL
);
$stmt->bindParam(':id_publi',$id_publi);
$stmt->execute();
$publi = [];

//si la publication correspond bien à son id alors on l'ajoute à un tableau
if(($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
	array_push($publi,$row);
}

//on renvoie les réponses de la requête en JSON pour que le client puisse récupérer les informations et les afficher
echo json_encode($publi,JSON_UNESCAPED_UNICODE);
exit();
