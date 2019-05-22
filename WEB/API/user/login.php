<?php
// headers
header("Content-Type: application/json; charset=UTF-8");

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

    //si on ne récupère pas l'username on renvoie une erreur
	if(!isset($json_obj['username_log']))
	{
        echo json_encode(array("error" => "Missing username"));
        http_response_code(422);
		exit();
    }
    
    //si on ne récupère pas le password on renvoie une erreur
    if(!isset($json_obj['pwd_log']))
	{
        echo json_encode(array("error" => "Missing password"));
        http_response_code(422);
		exit();
    }

    //variables
    $username = $json_obj['username_log'];
    $pwd = $json_obj['pwd_log'];

    //requête SQL pour vérifier que les identifiants de l'user sont bon (verif que username correspond à son password)
    $stmt_user = MyPDO::getInstance()->prepare(<<<SQL
	SELECT * FROM user 
    WHERE username = :username AND password = :pwd
SQL
);
    $stmt_user->execute(array(":username" => $username, ":pwd" => hash('sha256',$pwd)));

    //si l'username n'existe pas dans la bdd ou que le password est faux alors on envoie un message d'erreur
    if(($row = $stmt_user->fetch()) == false) {
        http_response_code(422);
        echo json_encode(array("message" => "Wrong username or password"));
        exit();
    }
    //si la session n'existe déjà pas alors on peut la commencer
    if (!session_id())
        session_start();
            
    //définitions des variables de session
    $_SESSION['username'] = $row['username'];
    $_SESSION['id_user']= $row['id_user'];
    //on récupère le dernier id inséré
    $id_user = MyPDO::getInstance()->lastInsertId();
    $resp = array("id_user" => $id_user, "username" => $username);
    //envoie des infos au client
    echo json_encode($resp);
}

exit();
?>