<?php 

session_start();

//headers
header("Content-Type: application/json; charset=UTF-8");

//inclure singleton de connexion
include_once "../data/MyPDO.spottimac.include.php";

//check que l'user est connecté
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

//recupère le contenu posté : ici, on verif que like a été activé : on envoie l'id de la publication
$input = file_get_contents('php://input');

//si on ne trouve pas de param, renvoyer un message d'erreur
if (!isset($input) || empty($input)) {
	echo json_encode(array("error" => "Missing params ".$input));
	http_response_code(422);
	exit();
}

else {
	//transformer l'objet json en string
	$json_obj = json_decode($input,true);
	
	//si on ne trouve pas l'id de la publication, il y a un problème
	if(!isset($json_obj['id_publication']))
	{
		echo json_encode(array("error" => "Missing id_publication"));
		http_response_code(422);
		exit();
	}

	//déclarations des variables
	$id_user = $_SESSION['id_user'];
	$id_publication = $json_obj['id_publication'];
	$liked = $json_obj['liked'];

	//requête php pour insérer les like dans la bdd
	$count_like = [];
	$stmt = MyPDO::getInstance()->prepare(<<<SQL
		INSERT INTO user_liked(id_publication, id_user, liked)
		VALUES (:id_publication, :id_user, :liked)
SQL
);
		$stmt->bindParam(':id_publication',$id_publication);
		$stmt->bindParam(':id_user',$id_user);
		$stmt->bindParam(':liked',$liked);
		$stmt->execute();
		//on renvoie un tableau avec l'id de la publication, l'id de l'user et le booleen like
		$resp = array("id_publication" => $id_publication, "id_user" => $id_user, "liked" => $liked);
		echo json_encode($resp);
}

exit();

?>