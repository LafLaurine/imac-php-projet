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
$valeurs = ['titre'=>'', 'datepub'=>'', 'topic'=>'', 'content' =>''];
if(!empty($_GET)){
	if(isset($_GET['titre']) && !empty($_GET['titre'])){
		$valeurs['titre'] = $_GET['titre'];			
	}
	if(isset($_GET['topic']) && !empty($_GET['topic'])){
		$req_id = MyPDO::getInstance()->prepare(<<<SQL
	SELECT *
	FROM topic
	WHERE nom_topic = $_GET['topic']	
SQL
);
		$req_id->execute();
		while (($row = $req_id->fetch()) !== false) {
			$valeurs['topic'] = $row['id_topic'];
		}	
	}
	if(isset($_GET['content']) && !empty($_GET['content'])){
		$valeurs['content'] = $_GET['content'];			
	}
}

$date = date('Y-m-d');
$valeurs['datepub'] = $date ;

$stmt = MyPDO::getInstance()->prepare(<<<SQL
	INSERT INTO publication(titre_publication, date_publication, id_topic, content)
	VALUES (:titre, :datepub, :topic, :content)
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