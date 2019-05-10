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

if(!empty($_GET['date'])){
    $id_categorie = $_GET['date'];
}

else {
    echo json_encode(array("error" => "Missing id categorie"));
	http_response_code(422);
}

if($id_categorie !=100) {
	$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT *
	FROM categories
	INNER JOIN topic ON categories.id_categorie = topic.id_categorie
	INNER JOIN publication ON publication.id_topic = topic.id_topic
	WHERE categories.id_categorie = :id_categorie;
SQL
);
	$stmt->bindParam(':id_categorie',$id_categorie);
	$stmt->execute();
	$cat = [];

	while(($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
		array_push($cat,$row);
	}
	if(empty($cat)) {
		echo json_encode(array("error" => "Missing categorie"));
		http_response_code(422);
	}
	echo json_encode($cat,JSON_UNESCAPED_UNICODE);
}

else {
	include_once "./get_publications.php";
}

exit();
