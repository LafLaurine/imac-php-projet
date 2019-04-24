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
$valeurs = ['titre'=>'', 'datepub'=>'', 'topic'=>''];
if(!empty($_GET)){
	if(isset($_GET['titre']) && !empty($_GET['titre'])){
		$valeurs['titre'] = $_GET['titre'];			
	}
	if(isset($_GET['datepub']) && !empty($_GET['datepub'])){
		$valeurs['datepub'] = $_GET['datepub'];			
	}
	if(isset($_GET['topic']) && !empty($_GET['topic'])){
		$valeurs['topic'] = $_GET['topic'];			
	}	

}

$stmt = MyPDO::getInstance()->prepare(<<<SQL
	INSERT INTO publication(titre_publication, date_publication, id_topic)
	VALUES (:titre, :datepub, :topic)
SQL
);

$insertion = $stmt->execute($valeurs);

if ($insertion){
	$message = 'Operation enregistree avec succes';
}
else{
	$message = 'Operation non enregistree ';
}

echo json_encode($message);
exit();
?>