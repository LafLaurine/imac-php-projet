<?php
// headers
header("Content-Type: application/json; charset=UTF-8");

// check HTTP method
$method = strtolower($_SERVER['REQUEST_METHOD']);
$stmt;

if ($method !== 'get') {
	http_response_code(405);
	echo json_encode(array('message' => 'This method is not allowed.'));
	exit();
}

// include data
include_once "../data/MyPDO.spottimac.include.php";
$topics;
if(!empty($_GET['publicategorie'])) {
	$topics= tri_by_cat($_GET['publicategorie']);
} else {
	$topics= allTopic();
}
function tri_by_cat($id)
{
	// response status
	http_response_code(200);

	$stmt = MyPDO::getInstance()->prepare(<<<SQL
		SELECT *
		FROM topic
		LEFT JOIN categories
		ON topic.id_categorie = categories.id_categorie
		WHERE categories.id_categorie = :publicategorie
		ORDER BY nom_topic
SQL
	);

	$stmt->execute(array(':publicategorie'=>$id));
	return $stmt;
}

function allTopic() {
	$stmt = MyPDO::getInstance()->prepare(<<<SQL
		SELECT *
		FROM topic
		ORDER BY nom_topic
SQL
	);
	$stmt->execute();
	return $stmt;
}

	$topic = [];

	while(($row = $topics->fetch(PDO::FETCH_ASSOC))) {
		array_push($topic,$row); 
	}

	if(empty($topic)) {
		echo json_encode(array("error" => "Missing topic"));
		http_response_code(422);
	}

	echo json_encode($topic,JSON_UNESCAPED_UNICODE);

exit();
?>