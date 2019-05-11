<?php 

session_start();

//headers
header("Content-Type: application/text; charset=UTF-8");

include_once "../data/MyPDO.spottimac.include.php";
if (!isset($_SESSION['id_user'])){
	echo json_encode(array('message' => 'User non connecté'));
	exit();
}

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

	if(!isset($json_obj['id_publication']))
	{
		echo json_encode(array("error" => "Missing comm"));
		exit();
	}
	$id_user = $_SESSION['id_user'];
	$id_publication = $json_obj['id_publication'];
	$count_like = [];
	$stmt_like = MyPDO::getInstance()->prepare(<<<SQL
	SELECT *
	FROM like_publication
	WHERE like_publication.id_publication = $id_publication

SQL
);
	$stmt_like->execute();
	if(($row = $stmt_like->fetch(PDO::FETCH_ASSOC))) {
		$count_like = $row['count_like'] - 1;
		$stmt = MyPDO::getInstance()->prepare(<<<SQL
		INSERT INTO like_publication(id_publication, id_user, count_like)
		VALUES (:id_publication, :id_user, :count_like)
SQL
);
		$stmt->bindParam(':id_publication',$id_publication);
		$stmt->bindParam(':id_user',$id_user);
		$stmt->bindParam(':count_like',$count_like);
		$stmt->execute();
		$id_like = MyPDO::getInstance()->lastInsertId(); 
		$resp = array("id_like" => $id_like, "id_publication" => $id_publication, "id_user" => $id_user, "count_like" => $count_like);
		echo json_encode($resp);
	}
}
exit();

?>