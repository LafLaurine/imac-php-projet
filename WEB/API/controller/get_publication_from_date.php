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
	FROM publication
	ORDER BY publication.date_publication DESC;
SQL
);
	$stmt->execute();
	$pub = [];

	while(($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
		array_push($pub,$row);
	}
	if(empty($pub)) {
		echo json_encode(array("error" => "Missing publications"));
		http_response_code(422);
		exit();
	}

echo json_encode($pub,JSON_UNESCAPED_UNICODE);
exit();
