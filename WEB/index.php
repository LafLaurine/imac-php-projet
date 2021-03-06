<!DOCTYPE HTML>
<html>
<head>
	<title>Spott'IMAC</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="./CSS/style.css">
	<link rel="icon" href="SRC/favicon.png" />
</head>

<?php session_start() ?>

<body>
	<!-- Page accueil full screen et scrollable pour acceder à la suite -->
	<img id="decorAccueil" alt="decor fond" src="SRC/accueil1.png"/>
	<img id="decorAccueil2" alt="decor fond" src="SRC/accueil2.png"/>
	<img id="decorAccueil3" alt="decor fond" src="SRC/accueil3.png"/>
	<img id="decorAccueil4" alt="decor fond" src="SRC/accueil4.png"/>
<div id="accueil">
	<h1>Spott'IMAC</h1>
	<p id="texteAccueil" class="pageAccueil">IMACIENS IMACIENNES,
	Bonjour à tou.te.s, voici le seul et l’unique Spott’IMAC, un spotted fait par des IMACS pour des IMACS!
	Ici vous pourrez exprimer et partager vos idées, projets et tout autre sujet qui vous passe par la tête! Tout cela se fera de façon anonyme, maintenant à vos claviers!

	Merci de rester respectueux.se et bienveillant.te, si nous voyons des publications offensantes, nous ne nous priverons pas de les supprimer.

	(Nous ne sommes en rien responsables des abus et mauvaises utilisations du site)</p>

	<?php
	//Vérification connection de l'user. Proposition différente en fonction de s'il est connecté ou non
	if (!isset($_SESSION['id_user'])){
	echo "<p class='pageAccueil'><a href='profile.php' id='go'>Se connecter/s'inscrire</a></p>";
	} 
	else {
		echo "<p class='pageAccueil'><a href='./API/user/logout.php' id='logout'>Se déconnecter</a></p>";
	}?>
	
	<p class="pageAccueil"><a href="#actu" id="go">C'est parti !</a></p>
	<p id="siteLien" class="pageAccueil">Consultez le site de <a href="https://www.ingenieur-imac.fr/" target="_blank" id="siteImac" class="pageAccueil">l'IMAC</a></p>
	
</div>

<!-- Page scrollable avec les publications récentes : fil d'actualités -->
<div id="actu">
	<!-- En tête fixe : sticky bar -->
	<header>
		<div id="rectVert"></div>
			<select id="trier" name="trier">
				<option id="tri_default" name="tri_default">Trier par :</option>
				<option id="tri_date" name="tri_date">Plus récent</option>
				<option id="tri_like" name="tri_like">Plus populaire</option>
			</select>
			<h2 id="titrecategorie">Bienvenue</h2>
			<div id="quoteDiv">
				<h2 id="quote"></h2>
				<h3 id="author"></h3>
			</div>
	</header>

	<!-- Barre latérale fixe : menu de sélection -->
	<aside>
		<div id="rectVert2"></div>
		<img src="SRC/logo.png" id="logo"><br>
		<button id="newpubli" onclick="popupAppear()"><a href="#publier" id="newPubText"> Nouvelle publication</a></button>
		<ul id="choixcategorie">
		</ul>
		<ul id="choixtopic">
		</ul>
	</aside>

	<!-- Contenu principal de la page : fil d'actualités scrollable -->
	<article>
		<img id="zoom1" alt="decor fond" src="SRC/zoom1.png"/>
		<img id="zoom2" alt="decor fond 2" src="SRC/zoom2.png"/>
		<div id="publication"></div>
		<!-- Lien pour afficher une publication en entière : redirection vers une autre page -->
	</article>
</div>

<p id="mentionLegales">Consultez les <a id="mentionLink" href="./mentions.php" target="_blank">mentions légales</a></p>


	<!-- Fenêtre pop up : écrire une nouvelle publication  -->
	<div id="publier">
		<form id="form">
			<div id="fond_fenetre">
				<img id="decorPop" alt="decor fond" src="SRC/popup3.png"/>
				<img id="decorPop2" alt="decor fond 2" src="SRC/popup4.png"/>
				<div id="modalContainer">
					<input type="image" id="closed_cross" src="SRC/cross2.png" onclick="popupClose()" alt="Croix pour fermer la fenêtre"/>
					<p id="titrePub">Titre :</p>
					<input type="text" id="titre" name="titre" required><br>
					<!-- Pour l'instant en commentaire car pas lien entre public et cat -->
					<p id="listCat">Catégories</p>
					<select id="publicategorie" name="publicategorie" size="0" required></select><img id="select-icon" src= "SRC/down-arrow.svg" alt="icône de flèche vers le bas"/><br>
					<p id="listTopic">Topics</p>
					<select id="publitopic" name="publitopic" size="0" required></select><br>
					<textarea id="contenu" name="contenu"required></textarea><br>
					<p id="txt_file">Lien vers votre fichier</p>
					<input type="text" id="file" name="up_file"><br>
					<button type="submit" id="validerpubli" onclick="popupClose()">Publier</button>
				</div>
				<p id="nvPub">NOUVELLE</p>
				<p id="nvPub2">PUBLICATION</p>

		</form>
	</div>
	<script src="JS/generate_objects.js"></script>
</body>
</html>