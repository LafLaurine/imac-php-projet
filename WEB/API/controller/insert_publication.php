<?php
// headers
header("Content-Type: application/json; charset=UTF-8");

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

	if(!isset($json_obj['titre']))
	{
		echo json_encode(array("error" => "Missing title"));
		exit();
	}

	if(!isset($json_obj['topic']))
	{
		echo json_encode(array("error" => "Missing topic"));
		exit();
	}

	if(!isset($json_obj['content']))
	{
		echo json_encode(array("error" => "Missing content"));
		exit();
	}

	$title = $json_obj['titre'];
	$id_topic = $json_obj['topic'];
	$content = $json_obj['content'];
	$date = date('Y-m-d');

	$stmt = MyPDO::getInstance()->prepare(<<<SQL
	INSERT INTO publication(titre_publication, date_publication, id_topic, content)
	VALUES (:titre_publication, :date_publication, :id_topic, :content)
SQL
);
	$stmt->bindParam(':titre_publication',$title);
	$stmt->bindParam(':date_publication',$date);
	$stmt->bindParam(':id_topic',$id_topic);
	$stmt->bindParam(':content',$content);
	$stmt->execute();
	
	$id_publi = MyPDO::getInstance()->lastInsertId(); 
	
	$resp = array("id_publication" => $id_publi, "titre_publication" => $title, "date_publication" => $date, "id_topic" => $id_topic, "content" => $content);
	echo json_encode($resp);
}
exit();
?>