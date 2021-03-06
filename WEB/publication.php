<!DOCTYPE HTML>
<html>
<head>
	<title>Publication</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="./CSS/style.css">
	<link rel="icon" href="SRC/favicon.png" />
</head>

<body>
	<!-- Publication sur une nouvelle page : full screen, commantaires et réactions -->
	<img id="page1" alt="decor fond" src="SRC/page1.png"/>
	<img id="page2" alt="decor fond 2" src="SRC/page2.png"/>
	<img id="page3" alt="decor fond 3" src="SRC/page3.png"/>
	<!-- Div principale publication -->
	<div id="fullpublication">
		<div id="fondPub"></div>
		<input type="image" id="closed_cross2" src="SRC/cross2.png" onclick="pubClose()" alt="Croix pour fermer la fenêtre"/>
		<h2 id="titrepubli"></h2>
		<div id="content">
			<p id="publicontent"></p>
			<div id="fichier">
			</div>
		</div>
		<h3 id="from_user"></h3>
		<div id="dateContent">
			<h3>Date :</h3>
			<h3 id="date"></h3>
		</div>
		<div id="reaction">
		</div>
		<div id="commentaire">
			<h3>Commentaires :</h3>
		</div>
		<form id="pub_commentaire">
			<h3 id="pubComm">Publier un commentaire :</h3>
			<textarea id="publi_com" name="publi_com" required></textarea><br>
			<button type="submit" id="valider_comm">Valider</button>
		</form>
	</div>
	<div id="rectVertPub"></div>
	<script src="JS/public.js"></script>
</body>
</html>