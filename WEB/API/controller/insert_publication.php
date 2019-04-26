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
			INSERT INTO publication(id_publication, nom_image, taille_image, type_image, desc_image,blob_image)
			VALUES (:idPub, :nom, :taille, :type, :description, :blob_image)
SQL
);
			$insertImg->execute($champsImg);
		}
	}
}
exit();
?>