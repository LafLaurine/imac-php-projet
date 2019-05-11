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

if(!empty($_GET['like'])){
    $id_like = $_GET['like'];
}

else {
    echo json_encode(array("error" => "Missing id like"));
	http_response_code(422);
}

if($id_like !=100) {
	$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT *
	FROM like_publication
	INNER JOIN publication ON like_publication.id_like = publication.id_like
	--INNER JOIN publication ON publication.id_topic = topic.id_topic
	WHERE like_publication.id_like = :id_like;
SQL
);
	$stmt->bindParam(':id_like',$id_like);
	$stmt->execute();
	$like = [];

	while(($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
		array_push($like,$row);
	}
	if(empty($like)) {
		echo json_encode(array("error" => "Missing like"));
		http_response_code(422);
	}
	echo json_encode($like,JSON_UNESCAPED_UNICODE);
}

else {
	include_once "./get_publications.php";
}

exit();
