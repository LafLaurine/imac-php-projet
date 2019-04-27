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
	ORDER BY nom_topic
SQL
);

$stmt->execute();
$topic = [];

while(($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
	array_push($topic,$row); 
}

if(empty($topic)) {
	echo json_encode(array("error" => "Missing topic"));
	http_response_code(422);
}
echo json_encode($topic,JSON_UNESCAPED_UNICODE);

//allow us to get topic from categories (need to put it under categories)

/*
$topic_cat = [];
$stmt_topic_cat = MyPDO::getInstance()->prepare(<<<SQL
	SELECT nom_topic, nom_categorie 
	FROM topic,categories 
	WHERE topic.id_categorie = categories.id_categorie
SQL
);

while (($row_2 = $stmt_topic_cat->fetch(PDO::FETCH_ASSOC))) {
	array_push($topic_cat,$row['nom_topic']); 
}*/


exit();
?>