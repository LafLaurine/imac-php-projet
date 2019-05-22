<?php

// headers
header("Content-Type: application/text; charset=UTF-8");
//session start car user a besoin d'être connecté pour publier un commentaire
session_start();

//si utilisateur pas connecté, message d'erreur
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

// include data
include_once "../data/MyPDO.spottimac.include.php";	

// response status
http_response_code(200);

//recupère les éléments posté
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

	//si l'utilisateur n'a pas écrit de commentaire on envoie un message d'erreur
	if(!isset($json_obj['publi_com']))
	{
		echo json_encode(array("error" => "Missing comm"));
		http_response_code(422);
		exit();
	}
	//si le serveur n'arrive pas à récupérer l'id de la publi on envoie message d'erreur
	if(!isset($json_obj['id_publication']))
	{
		echo json_encode(array("error" => "Missing id publication"));
		http_response_code(422);
		exit();
	}
	//variables publication
	$commentaire = $json_obj['publi_com'];
	$id_publication = $json_obj['id_publication'];
	$id_user = $_SESSION['id_user'];
	$username = $_SESSION['username']; 
	$date_comm = date('Y-m-d');
	
	//requête SQL pour insérer dans la table commentaire les param que l'utilisateur envoie + la date et l'id récupéré
	$stmt = MyPDO::getInstance()->prepare(<<<SQL
	INSERT INTO commentaire(date_commentaire, id_publication, id_user, content_com)
	VALUES (:date_commentaire, :id_publication, :id_user, :content_com)
SQL
);
	$stmt->bindParam(':date_commentaire',$date_comm);
	$stmt->bindParam(':id_publication',$id_publication);
	$stmt->bindParam(':content_com',$commentaire);
	$stmt->bindParam(':id_user',$id_user);
	$stmt->execute();
	
	//on récupère l'id du commentaire inséré
	$id_commentaire = MyPDO::getInstance()->lastInsertId(); 
	$resp = array("id_commentaire" => $id_commentaire, "date_commentaire" => $date_comm, "id_publication" => $id_publication, "id_user" => $id_user, "username", $username, "content_com" => $commentaire);
	//on renvoie ce tableau en JSON pour que le client puisse récupérer les informations et les afficher
	echo json_encode($resp);
}
exit();
