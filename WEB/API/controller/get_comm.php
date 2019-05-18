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

//vérifie que l'on a l'id_publi (dans l'url qu'on récupère en get)
if(!empty($_GET['id_publi'])){
    $id_publi = $_GET['id_publi'];
}

//si on ne le récupère pas, on envoie un message d'erreur
else {
    echo json_encode(array("error" => "Missing id publi"));
	http_response_code(422);
}

//requête SQL pour récupérer un commentaire, jointure sur la table user afin de pouvoir afficher qui a publié le commentaire
$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT id_commentaire, date_commentaire, id_publication, content_com, username
    FROM commentaire
	INNER JOIN user ON user.id_user = commentaire.id_user
    WHERE id_publication = :id_publi;
SQL
);
$stmt->bindParam(':id_publi',$id_publi);
$stmt->execute();
$comm = [];

//tant qu'il y a des commentaires, on ajoute dans le tableau
while(($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
	array_push($comm,$row); 
}

//on renvoie les réponses de la requête en JSON pour que le client puisse récupérer les informations et les afficher
echo json_encode($comm,JSON_UNESCAPED_UNICODE);
exit();
