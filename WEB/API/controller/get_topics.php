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
	FROM topic
	WHERE categories.id_categorie = topic.id_categorie
SQL
);

$stmt->execute();
$topic = [];

while (($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
	array_push($topic,$row['nom_topic']); 
}
sort($cat);
echo json_encode($topic);
exit();
?>