<?php

//headers
header("Content-Type: application/json; charset=UTF-8");

//Check HTTP method
$method = strtolower($_SERVER["REQUEST_METHOD"]);
if($method !== "post") {
    http_response_code(405);
    echo json_encode(array("message" => "This method is not allowed."));
    exit();
}

// include data
include_once "../data/MyPDO.spottimac.include.php";

http_response_code(200);

//fonction pour vérifier si le pseudo existe déjà dans la base de données
function testpseudoValidity ($username) {
    $stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT *
	FROM user
	WHERE username = $username;
SQL
);
    $result = $stmt->fetch();
    if ($result)
        return true;
    else 
        return false;
}
//récup param envoyé
$input = file_get_contents('php://input');

//si param vide, on renvoie un message d'erreur
if (!isset($input) || empty($input)) {
	echo json_encode(array("error" => "Missing params ".$input));
    http_response_code(422);
    exit();
}
else {
    //on récupère et décode l'objet envoyé
	$json_obj = json_decode($input,true);

    //si username pas récupéré on envoie un message d'erreur
	if(!isset($json_obj['username']))
	{
        echo json_encode(array("error" => "Missing username"));
        http_response_code(422);
		exit();
    }
    
    //si password non récupéré on envoie un message d'erreur
    if(!isset($json_obj['pwd']))
	{
        echo json_encode(array("error" => "Missing password"));
        http_response_code(422);
		exit();
    }
    
    //variables
    $username = $json_obj['username'];
    //vérif que pseudo n'existe pas déjà
    $pseudoValidity = testpseudoValidity($username);
    //s'il existe, on envoie un message d'erreur
    if ($pseudoValidity) {
        echo json_encode(array("message" => "Username already used"));
        http_response_code(422);
        exit();
    }
    //on n'oublie pas de crypter le password
    $pwd = $json_obj['pwd'];
    $pwd = hash('sha256',$pwd);

    //requête SQL pour insérer les données du nouveau user dans la table user
    $stmt_user = MyPDO::getInstance()->prepare(<<<SQL
	INSERT INTO user(username, password)
	VALUES (:username, :pwd)
SQL
);
	$stmt_user->bindParam(':username',$username);
	$stmt_user->bindParam(':pwd',$pwd);
	$stmt_user->execute();
	
    $id_user = MyPDO::getInstance()->lastInsertId(); 
    $resp = array("id_user" => $id_user, "username" => $username);
    //envoie des infos au client
	echo json_encode($resp);
}

exit();
?>