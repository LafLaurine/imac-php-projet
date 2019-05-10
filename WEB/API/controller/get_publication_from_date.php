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

if(!empty($_GET['id'])){
    $date_publication = $_GET['id'];
}

else {
    echo json_encode(array("error" => "Missing date"));
	http_response_code(422);
}

if($date_publication !=100) {
	$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT *
	FROM publication
	-- INNER JOIN topic ON publication.date_publication = topic.date_publication
	-- INNER JOIN publication ON publication.id_topic = topic.id_topic
	WHERE publication.date_publication = :date_publication
	ORDER BY publication.date_publication ASC;
SQL
);
	$stmt->bindParam(':date_publication',$date_publication);
	$stmt->execute();
	$date = [];

	while(($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
		array_push($date,$row);
	}
	if(empty($date)) {
		echo json_encode(array("error" => "Missing date"));
		http_response_code(422);
	}
	echo json_encode($date,JSON_UNESCAPED_UNICODE);
}

else {
	include_once "./get_publications.php";
}

exit();
