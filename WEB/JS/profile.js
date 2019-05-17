// document ready in ES6
Document.prototype.ready = callback => {
	if(callback && typeof callback === 'function') {
		document.addEventListener("DOMContentLoaded", () =>  {
			if(document.readyState === "interactive" || document.readyState === "complete") {
				return callback();
			}
		});
	}
};

//si user clique sur s'enregistrer
document.getElementById("valid_register").onclick = event => {
	event.preventDefault();
    const form = document.querySelector('#register_form');
	let params = {};
	//param envoyé au serveur via url
    if(form.username.value)
        params['username'] = form.username.value;
    if(form.pwd.value)
        params['pwd'] = form.pwd.value;
	var body = JSON.stringify(params);
	console.log(body);
	var request = new XMLHttpRequest();
    request.onreadystatechange = () => {
        if(request.readyState == 4) {
        	console.log(request.status);
            if(request.status == 200)
            {
				Array.prototype = true;
				var response = JSON.parse(request.responseText);
				alert("Register succeed");
			}
			else {
				console.log("Erreur");
			}
		}
		
	}
	//appel vers requête php de l'inscription
    request.open("POST", "./API/user/register.php",true);
    request.send(body);
};

//si user clique sur login
document.getElementById("valid_login").onclick = event => {
	event.preventDefault();
    const form = document.querySelector('#login_form');
	let params = {};
	//param envoyé au serveur via url
    if(form.username_log.value)
        params['username_log'] = form.username_log.value;
    if(form.pwd_log.value)
        params['pwd_log'] = form.pwd_log.value;
	var body = JSON.stringify(params);
	console.log(body);
	var request = new XMLHttpRequest();
    request.onreadystatechange = () => {
        if(request.readyState == 4) {
        	console.log(request.status);
            if(request.status == 200)
            {
				Array.prototype = true;
				var response = JSON.parse(request.responseText);
				document.location.href="index.php"; 
			}
			else {
				console.log("Erreur");
			}
		}
		
	}
	//appel requête php connexion
    request.open("POST", "./API/user/login.php",true);
    request.send(body);
};

//ferme pop up
function pubClose()
{
	document.location.href="./index.php"; 
}