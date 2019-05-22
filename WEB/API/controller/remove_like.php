<?php 
//session start car user a besoin d'être connecté pour enlever son like
session_start();

//headers
header("Content-Type: application/json; charset=UTF-8");

//include data
include_once "../data/MyPDO.spottimac.include.php";

//si l'user n'est pas connecté on envoie un message d'erreur
if (!isset($_SESSION['id_user'])){
	echo json_encode(array('message' => 'User non connecté'));
	http_response_code(422);
	exit();
}

// check HTTP method
$method = strtolower($_SERVER['REQUEST_METHOD']);
if ($method !== 'post') {
	http_response_code(405);
	echo json_encode(array('message' => 'This method is not allowed.'));
	exit();
}

// response status
http_response_code(200);

//recupère variables envoyées (ici id_publication qui est envoyé)
$input = file_get_contents('php://input');

//si l'utilisateur n'a rien envoyé ou si le serveur n'arrive pas à récupérer les paramètres, alors on envoie un message d'erreur
if (!isset($input) || empty($input)) {
	echo json_encode(array("error" => "Missing params ".$input));
	http_response_code(422);
	exit();
}
else {
	//on récupère l'objet envoyé et on le décode
	$json_obj = json_decode($input,true);

	//si le client n'envoie pas l'id de la publicatio, on envoie une erreur
	if(!isset($json_obj['id_publication']))
	{
		echo json_encode(array("error" => "Missing id_publication"));
		http_response_code(422);
		exit();
	}

	//variables
	$id_user = $_SESSION['id_user'];
	$id_publication = $json_obj['id_publication'];

	//requêtes SQL pour supprimer le like de la table user_liked
	$stmt = MyPDO::getInstance()->prepare(<<<SQL
		DELETE FROM user_liked
		WHERE user_liked.id_publication = $id_publication
SQL
);
	$stmt->execute();
	$resp = array("Like deleted where id_publication :" => $id_publication, "id_user" => $id_user);
	//on renvoie ce tableau en JSON pour que le client puisse récupérer les informations et les afficher
	echo json_encode($resp);
}

exit();

?>