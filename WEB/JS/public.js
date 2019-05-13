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

document.ready( () => {
	var url_string = window.location.href;
	var new_url = url_string.split('=');
	var id_publication = new_url[1];
	fetch("./API/controller/get_one_publication.php?id="+id_publication)
		.then( response => response.json() )
		.then( data => {
			data.forEach( publi => {
				let titre_publi  = document.getElementById("titrepubli");
				let from_user  = document.getElementById("from_user");
				let val_comm = document.getElementById("valider_comm");
				let reac = document.getElementById("reaction");
				if(publi.lien_fichier != undefined) {
					let fichier = document.getElementById("fichier");
					var img_fichier = document.createElement("img");
					img_fichier.setAttribute("id", "img_fichier");
					img_fichier.setAttribute("src", publi.lien_fichier);
					fichier.appendChild(img_fichier);
				}
			
				var img_reac = document.createElement("img");
				img_reac.setAttribute("id", "reac");
				img_reac.setAttribute("src", "./SRC/heart.png");
				img_reac.setAttribute("data-id_publication", publi.id_publication);
				img_reac.setAttribute('onclick','chngimg()');
				reac.appendChild(img_reac);

				val_comm.setAttribute("data-id_publication",publi.id_publication)
				console.log(publi);
				let content = document.getElementById("publicontent");
				let date = document.getElementById("date");
				let commentaire = document.getElementById("commentaire");
				titre_publi.innerHTML = publi.titre_publication;
				from_user.innerHTML = "Écrit par " + publi.username;
				content.innerHTML = publi.content;
				date.innerHTML = publi.date_publication;
			});
		})
		.catch(error => { console.log(error) });

		fetch("./API/controller/get_comm.php?id_publi="+id_publication)
		.then( response => response.json() )
		.then( data => {
			data.forEach( comm => {
				let com_div = document.getElementById("commentaire");
				let com_from = document.createElement("h4");
				com_from.setAttribute("id","comm_from");
				let com_h3 = document.createElement("h3");
				com_h3.setAttribute("id","comm_pub");
				let date_h3 = document.createElement("h3");
				date_h3.setAttribute("id","date_comm");
				com_div.appendChild(com_from);
				com_div.appendChild(com_h3);
				com_div.appendChild(date_h3);
				com_h3.innerHTML = comm.content_com;
				com_from.innerHTML = "De " + comm.username;
				date_h3.innerHTML = comm.date_commentaire;
			});
		})
		.catch(error => { console.log(error) });
});

document.getElementById("valider_comm").onclick = event => {
	let valid = document.getElementById("valider_comm");
	let id_publication = valid.getAttribute("data-id_publication");
	event.preventDefault();
	const form = document.querySelector('#pub_commentaire');
	let params = {};
	if(form.publi_com.value)
		params['publi_com'] = form.publi_com.value;
	params['id_publication'] = id_publication;
	var body = JSON.stringify(params);
	var request = new XMLHttpRequest();
	request.onreadystatechange = () => {
		if(request.readyState == 4) {
			if(request.status == 200)
			{
				Array.prototype = true;
				console.log(request);
				alert("Commentaire ajouté !");
			}
		
		}
		else {
			console.log("Erreur");
		}
	}
	request.open("POST", "http://localhost/PHP/PROJET_NEW/imac-php-projet/WEB/API/controller/insert_comm.php",true);
	request.send(body);
};

function chngimg() {
	var img = event.target.src;
	var count = 0;
	var liked = 0;		
	if (img.indexOf('heart.png')!=-1) {
		event.target.src  = './SRC/heart_pink.png';
		count = count+1;
		event.preventDefault();
		let params = {};
		params['id_publication'] = event.target.dataset.id_publication;
		params['liked'] = liked;
		var body = JSON.stringify(params);
		var request = new XMLHttpRequest();
		request.onreadystatechange = () => {
		if(request.readyState == 4) {
			if(request.status == 200)
			{
				Array.prototype = true;
				console.log(request);
			}
		}
		else {
			console.log("Erreur");
		}
	}
		request.open("POST", "http://localhost/PHP/PROJET_NEW/imac-php-projet/WEB/API/controller/add_like.php",true);
		request.send(body);
		liked = 1;
		console.log(liked);
	}

	else {
		event.target.src = './SRC/heart.png';
		if(count !=0) {
			count = count-1;
		}
		event.preventDefault();
		let params = {};
		params['id_publication'] = event.target.dataset.id_publication;
		var body = JSON.stringify(params);
		var request = new XMLHttpRequest();
		request.onreadystatechange = () => {
		if(request.readyState == 4) {
			if(request.status == 200)
			{
				Array.prototype = true;
				console.log(request);
			}
		}
		else {
			console.log("Erreur");
		}
	}
		request.open("POST", "http://localhost/PHP/PROJET_NEW/imac-php-projet/WEB/API/controller/remove_like.php",true);
		request.send(body);
	}	
}

function pubClose()
{
	document.location.href="index.php"; 
}