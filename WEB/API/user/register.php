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

function testpseudoValidity ($username) {
    $stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT *
	FROM user
	WHERE username = '$username';
SQL
);
    $result = $stmt->fetch();
    if ($result)
        return true;
    else 
        return false;
}

$input = file_get_contents('php://input');

if (!isset($input) || empty($input)) {
	echo json_encode(array("error" => "Missing params ".$input));
	http_response_code(422);
}
else {
	$json_obj = json_decode($input,true);

	if(!isset($json_obj['username']))
	{
		echo json_encode(array("error" => "Missing username"));
		exit();
    }
    
    if(!isset($json_obj['pwd']))
	{
		echo json_encode(array("error" => "Missing password"));
		exit();
    }
    
    $username = $json_obj['username'];
    $pseudoValidity = testpseudoValidity($username);
    if ($pseudoValidity) {
        echo json_encode(array("message" => "Username already used"));
        exit();
    }
    $pwd = $json_obj['pwd'];
    $pwd = hash('sha256',$pwd);
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
	echo json_encode($resp);
}

exit();
?>