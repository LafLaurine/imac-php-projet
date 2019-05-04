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

$input = file_get_contents('php://input');

if (!isset($input) || empty($input)) {
	echo json_encode(array("error" => "Missing params ".$input));
	http_response_code(422);
}
else {
    $json_obj = json_decode($input,true);

	if(!isset($json_obj['username_log']))
	{
		echo json_encode(array("error" => "Missing username"));
		exit();
    }
    
    if(!isset($json_obj['pwd_log']))
	{
		echo json_encode(array("error" => "Missing password"));
		exit();
    }

    $username = $json_obj['username_log'];
    $pwd = $json_obj['pwd_log'];
    $stmt_user = MyPDO::getInstance()->prepare(<<<SQL
	SELECT * FROM user 
    WHERE username = :username AND password = :pwd
SQL
);
    $stmt_user->execute(array(":username" => $username, ":pwd" => hash('sha256',$pwd)));
    if(($row = $stmt_user->fetch()) == false) {
        http_response_code(422);
        echo json_encode(array("message" => "Wrong username or password"));
        exit();
    }
    if (!session_id())
        session_start();
            
    $_SESSION['username'] = $row['username'];
    $_SESSION['id_user']= $row['id_user'];
    $id_user = MyPDO::getInstance()->lastInsertId();
    $resp = array("id_user" => $id_user, "username" => $username);
    echo json_encode($resp);
}

exit();
?>