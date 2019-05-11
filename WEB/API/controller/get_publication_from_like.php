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

//COUNT ON LIKE
$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT *
	FROM publication
	ORDER BY id_publication = (SELECT MAX(id_publication)
							FROM user_liked) DESC
SQL
);

$stmt->execute();
$like = [];

while(($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
	array_push($like,$row);
}

if(empty($like)) {
	echo json_encode(array("error" => "Missing like"));
	http_response_code(422);
	exit();
}

echo json_encode($like,JSON_UNESCAPED_UNICODE);
exit();
