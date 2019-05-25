<!DOCTYPE HTML>
<html>
<head>
	<title>Spott'IMAC</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="./CSS/style.css">
	<link rel="icon" href="SRC/favicon.png" />
</head>

<body>
	<!-- Page accueil full screen et scrollable pour acceder à la suite -->
	<img id="decorAccueil" alt="decor fond" src="SRC/accueil1.png"/>
	<img id="decorAccueil2" alt="decor fond" src="SRC/accueil2.png"/>
	<img id="decorAccueil3" alt="decor fond" src="SRC/accueil3.png"/>
	<img id="decorAccueil4" alt="decor fond" src="SRC/accueil4.png"/>

	<input type="image" id="closed_cross3" src="SRC/croix.png" onclick="pubClose()" alt="Croix pour fermer la fenêtre"/>

	<!-- Formulaire d'inscription -->
	<h3 id="register">Inscription</h3>
	<div id="cadreRegister">
	<form id="register_form">
    <p id="pseudo">Pseudo :</p>
    <input type="text" id="username" name="username" required><br>
    <p id="password">Mot de passe :</p>
    <input type="password" id="pwd" name="pwd" required><br>
		<button type="submit" id="valid_register">Inscription</button>
	</form></div>
	
	<!-- Formulaire de connexion -->
	<h3 id="login">Connexion</h3>
	<div id="cadreLogin">
	<form id="login_form">
    <p id="pseudo">Pseudo :</p>
    <input type="text" id="username_log" name="username_log" required><br>
    <p id="password">Mot de passe :</p>
    <input type="password" id="pwd_log" name="pwd_log" required><br>
		<button type="submit" id="valid_login">Connexion</button>
	</form></div>
    <script src="JS/profile.js"></script>
</body>
</html>