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


$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT *
	FROM user_liked;
SQL
);

$stmt->execute();
$user_liked = [];

while (($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
	array_push($user_liked,$row); 
}
if(empty($user_liked)) {
	echo json_encode(array("error" => "Missing publications"));
	http_response_code(422);
}
echo json_encode($user_liked,JSON_UNESCAPED_UNICODE);
exit();
