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

else {
    echo json_encode(array("error" => "Missing id publi"));
	http_response_code(422);
}

$input = file_get_contents('php://input');

if (!isset($input) || empty($input)) {
	echo json_encode(array("error" => "Missing params ".$input));
	http_response_code(422);
}
else {
	$json_obj = json_decode($input,true);

	if(!isset($json_obj['publi_com']))
	{
		echo json_encode(array("error" => "Missing comm"));
		exit();
    }
    
    $commentaire = $json_obj['publi_com'];
    $date_comm = date('Y-m-d');

    $stmt = MyPDO::getInstance()->prepare(<<<SQL
	INSERT INTO commentaire(date_commentaire, id_publication, content_com)
	VALUES (:date_commentaire, :id_publication, :content_com)
SQL
);
	$stmt->bindParam(':date_commentaire',$date_comm);
	$stmt->bindParam(':content_com',$commentaire);
	$stmt->execute();
	
	$id_commentaire = MyPDO::getInstance()->lastInsertId(); 
	
	$resp = array("id_commentaire" => $id_commentaire, "date_commentaire" => $date_comm, "content_com" => $commentaire);
	echo json_encode($resp);
}
exit();
