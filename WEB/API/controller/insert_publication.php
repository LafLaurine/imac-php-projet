<?php
// headers
//header("Content-Type: application/json; charset=UTF-8");

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

	/*
	if ($_FILES['up_file']['size']==0) { die("No file selected"); }
	if (exif_imagetype($_FILES['up_file']['tmp_name'])===false) { die("Not an image"); }

	// ALTERNATE SOLUTION
	// SIMPLY SAVE THE UPLOADED FILE INTO A PRIVATE FOLDER
	$target_file = "../private_upload/" . basename($_FILES["up_file"]["nom_image"]);
	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) { echo "ok"; }
	else{ echo "error"; }*/
	
	$resp = array("id_publication" => $id_publi, "titre_publication" => $title, "date_publication" => $date, "id_topic" => $id_topic, "content" => $content);
	echo json_encode($resp);


}

/*
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

echo json_encode($message);*/

/*

//insertion dans la table publication 
$champsPublication = ['titre'=>'', 'datepub'=>'', 'topic'=>'', 'content' =>''];
if(!empty($_POST)){
	if(isset($_POST['titre']) && !empty($_POST['titre'])){
		$champsPublication['titre'] = $_POST['titre'];			
	}
	if(isset($_POST['topic']) && !empty($_POST['topic'])){
		$req_id = MyPDO::getInstance()->prepare(<<<SQL
	SELECT *
	FROM topic
	WHERE nom_topic = $_POST['topic']	
SQL
);
		$req_id->execute();
		while (($row = $req_id->fetch()) !== false) {
			$champsPublication['topic'] = $row['id_topic'];
		}	
	}
	if(isset($_POST['content']) && !empty($_POST['content'])){
		$champsPublication['content'] = $_POST['content'];			
	}
}

$date = date('Y-m-d');
$champsPublication['datepub'] = $date ;

$stmt = MyPDO::getInstance()->prepare(<<<SQL
	INSERT INTO publication(titre_publication, date_publication, id_topic, content)
	VALUES (:titre, :datepub, :topic, :content)
SQL
);

$insertionPublication = $stmt->execute($champsPublication);

$idPub = MyPDO::lastInsertId();

// Testons si le fichier a bien été envoyé et s'il n'y a pas d'erreur
if (isset($_FILES['file']) AND $_FILES['file']['error'] == 0){
	if ($_FILES['file']['size'] <= 1000000){
		$infosfichier = pathinfo($_FILES['file']['name']);
		$extension_fichier = $infosfichier['extension'];
		//testons si le fichier est une image 
		$extensions_image = array('jpg', 'jpeg', 'gif', 'png');
		if (in_array($extension_upload, $extensions_image)){
			$destination = "../../STOCKAGE/IMAGE";
			$champsImg = ['idPub' =>'','nom'=> '', 'taille'=>'', 'type'=>'', 'description' =>'', 'blob_image'=>''];
			$nom = $_FILES['file']['name'];
			$chemin = $destination.'/'.$nom;
			$champsImg['idPub']= $idPub; 
			$champsImg['nom']= $nom; 
			$champsImg['taille']=$_FILES['file']['size'];
			$champsImg['type']=$infosfichier['extension'];
			$champsImg['blob_image']= $chemin;

			move_uploaded_file($_FILES['file']['tmp_name'],$chemin);

			$insertImg = MyPDO::getInstance()->prepare(<<<SQL
			INSERT INTO image(id_publication, nom_image, taille_image, type_image, desc_image,blob_image)
			VALUES (:idPub, :nom, :taille, :type, :description, :blob_image)
SQL
);
			$insertImg->execute($champsImg);
		}
	}
*/
exit();
?>