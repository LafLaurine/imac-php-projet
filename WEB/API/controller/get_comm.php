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

if(!empty($_GET['id_publi'])){
    $id_publi = $_GET['id_publi'];
}

else {
    echo json_encode(array("error" => "Missing id publi"));
	http_response_code(422);
}


$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT *
    FROM commentaire
    WHERE id_publication = :id_publi;
SQL
);
$stmt->bindParam(':id_publi',$id_publi);
$stmt->execute();
$comm = [];

while(($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
	array_push($comm,$row); 
}

echo json_encode($comm,JSON_UNESCAPED_UNICODE);
exit();
