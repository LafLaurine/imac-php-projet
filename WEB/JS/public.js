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
	//récupère l'url sur laquelle on est actuelle pour récup id de la publication
	var url_string = window.location.href;
	//split l'url après le = pour récup l'id de la publication
	var new_url = url_string.split('=');
	var id_publication = new_url[1];

	//Récupérer une publication en fonction de son id

	//appel vers php en envoyant en paramètre l'id de la publication (utile pour requête php)
	fetch("./API/controller/get_one_publication.php?id="+id_publication)
		.then( response => response.json() )
		.then( data => {
			//pour chaque publication on crée des éléments et on y associe les valeurd
			data.forEach( publi => {
				//définitions des éléments
				let titre_publi  = document.getElementById("titrepubli");
				let from_user  = document.getElementById("from_user");
				let val_comm = document.getElementById("valider_comm");
				let reac = document.getElementById("reaction");

				//si la publication contient un lien vers un fichier
				if(publi.lien_fichier != undefined) {
					//on récupère les trois derniers caractères de l'url pour savoir de quel type est le fichier
					var fichier_type = publi.lien_fichier.substr(-3);
					//on récupère aussi les caractères de 12 à 19, ça c'est spécialement pour vérifier que c'est "youtube"
					var fichier_type_vid = publi.lien_fichier.substr(12,7);

					//si le type de fichier est une image
					if(fichier_type == "jpg" || fichier_type == "png" || fichier_type == "gif") {
						//création des éléments pour une image + association valeurs à la source
						let fichier = document.getElementById("fichier");
						var img_fichier = document.createElement("img");
						img_fichier.setAttribute("id", "img_fichier");
						img_fichier.setAttribute("src", publi.lien_fichier);
						fichier.appendChild(img_fichier);
					}
					//si le type de fichier est une vidéo
					else if(fichier_type == "mp4" || fichier_type == "webm" || fichier_type_vid == "youtube"){
						//création des éléments pour une vidéo + association valeurs à la source
						let fichier = document.getElementById("fichier");
						var vid_fichier = document.createElement("iframe");
						vid_fichier.setAttribute("id", "vid_fichier");
						vid_fichier.setAttribute("src", publi.lien_fichier);
						fichier.appendChild(vid_fichier);
					}
					
				}
			
				//création élément coeur
				var img_reac = document.createElement("img");
				img_reac.setAttribute("id", "reac");
				img_reac.setAttribute("src", "./SRC/heart.png");
				img_reac.setAttribute("data-id_publication", publi.id_publication);
				img_reac.setAttribute('onclick','chngimg()');
				reac.appendChild(img_reac);
				
				//récupère le booléen like d'une publication : si publication liké, alors coeur reste rose
				fetch("./API/controller/get_liked_one_publi.php?id="+id_publication)
				.then( response => response.json() )
				.then( data => {
					if(data != null) {
						if (data[0].liked == 1){
							console.log("oui");
							img_reac.setAttribute("src", "./SRC/heart_pink.png");
						}	
					}
				}).catch(error => { console.log(error) });

				//élément date
				val_comm.setAttribute("data-id_publication",publi.id_publication)
				let content = document.getElementById("publicontent");
				let date = document.getElementById("date");
				//insertion des valeurs
				titre_publi.innerHTML = publi.titre_publication;
				from_user.innerHTML = "Écrit par " + publi.username;
				content.innerHTML = publi.content;
				date.innerHTML = publi.date_publication;
			});
		})
		.catch(error => { console.log(error) });

		//récupérer les commentaires associés à une publication

		//appel vers php en envoyant en paramètre l'id de la publication dont on veut les commentaires
		fetch("./API/controller/get_comm.php?id_publi="+id_publication)
		.then( response => response.json() )
		.then( data => {
			//a chaque commentaire on crée des éléments et on lui associe des valeurs
			data.forEach( comm => {
				//div principale
				let com_div = document.getElementById("commentaire");
				//div écrit par
				let com_from = document.createElement("h4");
				com_from.setAttribute("id","comm_from");
				//contenu du commentaire
				let com_h3 = document.createElement("h3");
				com_h3.setAttribute("id","comm_pub");
				//date commentaire
				let date_h3 = document.createElement("h3");
				date_h3.setAttribute("id","date_comm");
				//associations des éléments à la div principale
				com_div.appendChild(com_from);
				com_div.appendChild(com_h3);
				com_div.appendChild(date_h3);
				//insertion des valeurs dans l'html
				com_h3.innerHTML = comm.content_com;
				com_from.innerHTML = "De " + comm.username;
				date_h3.innerHTML = comm.date_commentaire;
			});
		})
		.catch(error => { console.log(error) });

		
});

//insertion d'un commentaire
document.getElementById("valider_comm").onclick = event => {
	let valid = document.getElementById("valider_comm");
	let id_publication = valid.getAttribute("data-id_publication");
	event.preventDefault();
	const form = document.querySelector('#pub_commentaire');
	let params = {};
	//envoie des param au serveur
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
	//appel vers requête php d'insertion
	request.open("POST", "http://localhost/PHP/PROJET_NEW/imac-php-projet/WEB/API/controller/insert_comm.php",true);
	request.send(body);
};

//fonction pour changer l'image du coeur en fonction du like ou non + requête like
function chngimg() {
	var img = event.target.src;
	var count = 0;
	var liked = 0;		
	if (img.indexOf('heart.png')!=-1) {
		event.target.src  = './SRC/heart_pink.png';
		count = count+1;
		liked = 1;
		event.preventDefault();
		let params = {};
		//param envoyé pour le like
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
		//appel php vers ajout like si coeur cliqué
		request.open("POST", "./API/controller/add_like.php",true);
		request.send(body);
		console.log(liked);
	}

	else {
		//si coeur est déjà rose donc déjà cliqué
		event.target.src = './SRC/heart.png';
		if(count !=0) {
			count = count-1;
		}
		liked = 0;
		event.preventDefault();
		let params = {};
		//param envoyé au serveur via url
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
		//requête php vers le dislike
		request.open("POST", "./API/controller/remove_like.php",true);
		request.send(body);
	}	
}


//ferme page, retour vers index
function pubClose()
{
	document.location.href="index.php"; 
}