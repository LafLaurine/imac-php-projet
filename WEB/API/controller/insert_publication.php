<?php

// headers
header("Content-Type: application/json; charset=UTF-8");

//session start car l'user doit être connecté pour publier une publication
session_start();

//insert data
include_once "../data/MyPDO.spottimac.include.php";

//si l'user n'est pas connecté on renvoie un message d'erreur
if (!isset($_SESSION["id_user"])){
	echo json_encode(array('message' => 'User non connecté'));
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

//recupère variables posté
$input = file_get_contents('php://input');

//si l'utilisateur n'a rien envoyé ou si le serveur n'arrive pas à récupérer les paramètres, alors on envoie un message d'erreur
if (!isset($input) || empty($input)) {
	echo json_encode(array("error" => "Missing params ".$input));
	http_response_code(422);
}
else {	
	//on récupère l'objet envoyé et on le décode
	$json_obj = json_decode($input,true);

	//si l'utilisateur n'a pas mis de titre à sa publication on envoie une erreur
	if(!isset($json_obj['titre']))
	{
		echo json_encode(array("error" => "Missing title"));
		exit();
	}

	//si l'utilisateur n'a pas mis de topic à sa publication on envoie une erreur
	if(!isset($json_obj['topic']))
	{
		echo json_encode(array("error" => "Missing topic"));
		exit();
	}

	//si l'utilisateur n'a pas mis de contenu à sa publication on envoie une erreur
	if(!isset($json_obj['content']))
	{
		echo json_encode(array("error" => "Missing content"));
		exit();
	}

	//variables
	$title = $json_obj['titre'];
	$id_topic = $json_obj['topic'];
	$id_user = $_SESSION['id_user'];
	$username = $_SESSION['username'];
	$content = $json_obj['content'];
	$file = $json_obj['up_file'];
	$date = date('Y-m-d');

	//requête SQL pour insérer dans la table publication les param que l'utilisateur envoie + la date et l'id récupéré de la publication
	$stmt = MyPDO::getInstance()->prepare(<<<SQL
	INSERT INTO publication(titre_publication, date_publication, id_topic, id_user, content, lien_fichier)
	VALUES (:titre_publication, :date_publication, :id_topic, :id_user, :content, :lien_fichier)
SQL
);
	$stmt->bindParam(':titre_publication',$title);
	$stmt->bindParam(':date_publication',$date);
	$stmt->bindParam(':id_topic',$id_topic);
	$stmt->bindParam(':id_user',$id_user);
	$stmt->bindParam(':content',$content);
	$stmt->bindParam(':lien_fichier',$file);
	$stmt->execute();
	
	//on récupère l'id de la dernière publi insérée
	$id_publi = MyPDO::getInstance()->lastInsertId();

	$resp = array("id_publication" => $id_publi, "titre_publication" => $title, "date_publication" => $date, "id_user" => $id_user, "username" => $username, "content" => $content, "lien_fichier" => $file);
	//on renvoie ce tableau en JSON pour que le client puisse récupérer les informations et les afficher
	echo json_encode($resp);
	exit();
}

?>