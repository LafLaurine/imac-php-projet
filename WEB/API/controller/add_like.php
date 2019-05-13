<?php 

session_start();

//headers
header("Content-Type: application/json; charset=UTF-8");

include_once "../data/MyPDO.spottimac.include.php";
if (!isset($_SESSION['id_user'])){
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
	
	
	if(!isset($json_obj['id_publication']))
	{
		echo json_encode(array("error" => "Missing id_publication"));
		exit();
	}

	$id_user = $_SESSION['id_user'];
	$id_publication = $json_obj['id_publication'];

	$count_like = [];
	$stmt = MyPDO::getInstance()->prepare(<<<SQL
		INSERT INTO user_liked(id_publication, id_user)
		VALUES (:id_publication, :id_user)
SQL
);
		$stmt->bindParam(':id_publication',$id_publication);
		$stmt->bindParam(':id_user',$id_user);
		$stmt->execute();
		$resp = array("id_publication" => $id_publication, "id_user" => $id_user);
		echo json_encode($resp);
}

exit();

?>