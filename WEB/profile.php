<!DOCTYPE HTML>
<html>
<head>
	<title>Spott'IMAC</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="./CSS/style.css">
</head>

<body>
	<!-- Page accueil full screen et scrollable pour acceder à la suite -->
	<img id="decorAccueil" alt="decor fond" src="SRC/accueil1.png"/>
	<img id="decorAccueil2" alt="decor fond" src="SRC/accueil2.png"/>
	<img id="decorAccueil3" alt="decor fond" src="SRC/accueil3.png"/>
	<img id="decorAccueil4" alt="decor fond" src="SRC/accueil4.png"/>
	<h3>Register</h3>
	<form id="register_form">
    <p id="pseudo">Pseudo :</p>
    <input type="text" id="username" name="username" required><br>
    <p id="password">Mot de passe :</p>
    <input type="password" id="pwd" name="pwd" required><br>
		<button type="submit" id="valid_register">Inscription</button>
	</form>
	
	<h3>Login</h3>
	<form id="login_form">
    <p id="pseudo">Pseudo :</p>
    <input type="text" id="username_log" name="username_log" required><br>
    <p id="password">Mot de passe :</p>
    <input type="password" id="pwd_log" name="pwd_log" required><br>
		<button type="submit" id="valid_login">Login</button>
	</form>
    <script src="JS/profile.js"></script>
</body>
</html>