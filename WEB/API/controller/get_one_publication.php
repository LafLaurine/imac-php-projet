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
    $id_publi = $_GET['id'];
}

else {
    echo json_encode(array("error" => "Missing id publi"));
	http_response_code(422);
}


$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT *
    FROM publication
    WHERE id_publication = :id_publi;
SQL
);
$stmt->bindParam(':id_publi',$id_publi);
$stmt->execute();
$publi = [];

if(($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
	array_push($publi,$row);
}

echo json_encode($publi,JSON_UNESCAPED_UNICODE);
exit();
