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
	//récupérer et afficher toutes les catégories
	fetch("./API/controller/get_categories.php")
		.then( response => response.json() )
		.then( data => {
			if(data != null) {
			//créations des éléments qui vont contenir les catégories
			let categories = document.getElementById('choixcategorie');
			let att_cat = document.createElement("li");
			let all  = document.createElement("a");
			//ajout d'un élément "tout" pour sélectionner toutes les catégories
			all.setAttribute("data-id_cat",100);
			all.innerHTML = "tout";
			att_cat.appendChild(all);
			categories.appendChild(att_cat);
			let cat_publi = document.getElementById('publicategorie');
			
			let option_pub  = document.createElement("option");
			option_pub.innerHTML = "choisir une option";
			option_pub.setAttribute('disabled', '')
			option_pub.setAttribute('selected', '')
			option_pub.setAttribute('value', '')
			cat_publi.appendChild(option_pub);
		
			//pour chaque catégorie, on remplit les éléments définis plus haut
			data.forEach( categorie => {
				let choix_cat  = document.createElement("li");
				let cate  = document.createElement("a");
				choix_cat.setAttribute("class","choix_cat_menu")
				//on associe à data-id_cat l'id de la catégorie
				cate.setAttribute("data-id_cat",categorie.id_categorie);
				choix_cat.appendChild(cate);
				//le nom de la catégorie
				cate.innerHTML = categorie.nom_categorie;
                let option_pub  = document.createElement("option");
				option_pub.innerHTML = categorie.nom_categorie;
				option_pub.value=categorie.id_categorie;
				option_pub.classList.add("categorie");
				categories.appendChild(choix_cat);
				cat_publi.appendChild(option_pub);
				
			});
			}
		})
		.catch(error => { console.log(error) });

		//récupérer et afficher toutes les publications
		fetch("./API/controller/get_publications.php")
		.then( response => response.json() )
		.then( data => {
			if(data != null) {
				//pour chaque publication, création des éléments
				for(var i = 0; i<data.length; i++) {
					createPublication(data,i);
				}
			}
		})
		.catch(error => { console.log(error) });

		//récupérer et afficher une quote random
		fetch("./API/controller/get_quotes.php")
		.then( response => response.json() )
		.then( data => {
			if(data!=null) {
				let quote = document.getElementById('quote');
				let author = document.getElementById('author');
				quote.innerHTML = data[0].content_quotes;
				author.innerHTML = data[0].firstname_author + " " + data[0].lastname_author;
			}
		})
		.catch(error => { console.log(error) });

		//récupère le booléen like d'une publication : si publication liké, alors coeur reste rose
		fetch("./API/controller/get_liked.php")
		.then( response => response.json() )
		.then( data => {
			if(data!=null) {
				var select = document.getElementsByClassName("reac");
				//pour chaque coeur on vérif si c'est liké
				for (var i = 0; i < select.length; i++) {
					var id_publi = select[i].getAttribute("data-id_publication");
					for (var j = 0; j<data.length; j++){
						if(id_publi == data[j].id_publication){
							if (data[j].liked == "1"){
								select[i].setAttribute("src", "./SRC/heart_pink.png");
							}
						}
					}
			}
		}

		}).catch(error => { console.log(error) });
});

/* ------------------------------------ TRI ----------------------------------------------- */

//tri topics par catégorie, ce fait dans "nouvelle publication" quand on clique sur catégorie
document.getElementById('publicategorie').onchange = event  => {
	event.preventDefault();
	//récup paramètre formulaire
    const form = document.querySelector('#form');
    let params = {};
    if(form.publicategorie.value)
		params ['publicategorie'] = form.publicategorie.value;
		var request = new XMLHttpRequest();
		request.onreadystatechange = () => {
			if(request.readyState == 4) {
				console.log(request.status);
				if(request.status == 200)
				{
					//regarde réponse reçue
					var data = JSON.parse(request.responseText);
					//création élément pour topic
					let cat_topic = document.getElementById('publitopic');
					cat_topic.innerHTML='';
					data.forEach(topic => {
						//pour chaque topic on le mets dans option
						let option_pub  = document.createElement("option");
						option_pub.value=topic.id_topic;
						option_pub.innerHTML = topic.nom_topic;
						option_pub.classList.add("topic");
						cat_topic.appendChild(option_pub);
					});
				}
				else {
					console.log("Erreur");
				}
			}
			
		}
		//envoie de la requête
		request.open("GET", "./API/controller/get_topics.php?publicategorie="+params['publicategorie'],true);
		request.send();
};


//Si on est sur "Tri par:", qui est le tri par défaut alors toutes les publications sont générées
document.getElementById("tri_default").onclick = event => {
	fetch("./API/controller/get_publications.php")
		.then( response => response.json() )
		.then( data => {
			if(data!=null) {
				//A chaque publication les éléments sont crées
				let public = document.getElementById("publication");
				//on supprime les publications qu'il y avait sur la page
				while (public.firstChild) {
					public.removeChild(public.firstChild);
				}
				//A chaque publication est crée les éléments + les valeurs correspondantes y sont associées
				for(var i =0; i<data.length; i++) {
					createPublication(data,i);
				}
			}
		})
		.catch(error => { console.log(error) });
};

//tri des publications par le nombre de like
document.getElementById("tri_like").onclick = event => {
	//appel vers le php
	fetch("./API/controller/get_publication_from_like.php")
		.then( response => response.json() )
		.then( data => {
			if(data!=null) {
				//supprime les publications déjà présentes sur la page
				let public = document.getElementById("publication");
				while (public.firstChild) {
					public.removeChild(public.firstChild);
				}
				//a chaque publication est crée des éléments + leur valeur correspondantes sont associées
				for(var i =0; i<data.length; i++) {
					createPublication(data,i);
				}
			}
		})
		.catch(error => { console.log(error) });
};

//click sur la colonne de gauche : tri des publications en fonction des catégories
document.getElementById("choixcategorie").onclick = event => {
	var id_categorie = event.target.dataset.id_cat;
	//appel au php avec comme param l'id de la catégorie dont on a besoin pour la requête sql
	fetch("./API/controller/get_publication_from_categorie.php?id="+id_categorie)
		.then( response => response.json() )
		.then( data => {
			if(data!=null) {
				data.forEach(cat => {
					//a chaque catégorie on remplace le titre "bienvenue" par le nom de la catégorie
					document.getElementById("titrecategorie").innerHTML = cat.nom_categorie;
					//si "tout" est choisi alors c'est "Tout" qui est mis en titre
					if(id_categorie == 100) {
						document.getElementById("titrecategorie").innerHTML  = "Tout";
					}
	
					let public = document.getElementById("publication");
					//suppression précédentes publications
					while (public.firstChild) {
						public.removeChild(public.firstChild);
					}
					
					//création élement publication + valeurs associées
					for(var i =0; i<data.length; i++) {
						createPublication(data,i);
					}
				});		
			}	
		})
		.catch(error => { console.log(error) });
};

//Accueil : tri publi par date
document.getElementById("tri_date").onclick = event => {
	//appel vers php
	fetch("./API/controller/get_publication_from_date.php")
		.then( response => response.json() )
		.then( data => {
			if(data!=null) {
				data.forEach( publication => {
					let public = document.getElementById("publication");
					//supprime publication précédentes
					while (public.firstChild) {
						public.removeChild(public.firstChild);
					}
					//a chaque publication est crée un élément avec sa valeur associée
					for(var i =0; i<data.length; i++) {
						createPublication(data,i);
					}
				});	
			}
		})
		.catch(error => { console.log(error) });
	
};

//Insertion publication
document.getElementById("validerpubli").onclick = event => {
	event.preventDefault();
	//envoie des paramètres dans l'url
    const form = document.querySelector('#form');
	let params = {};
    if(form.titre.value)
        params['titre'] = form.titre.value;
    if(form.publitopic.value)
        params['topic'] = form.publitopic.value;
    if(form.contenu.value)
		params['content'] = form.contenu.value;
	if(form.up_file.value)
		params['up_file'] = form.up_file.value;
	/*var fileSelect = document.getElementById('file');
	var files = fileSelect.files;
	//le FormData permet créer des paires clé/valeur du même format 
	//que celles générées par l'attribut 'name' dans les champs <input> du formulaire.
	var formData = new FormData();
	for (var i = 0; i < files.length; i++) {
  		var file = files[i];
  		formData.append('file', file);
  	}
	// Loop through each of the selected files.
  	params['fileName'] = file.name;
  	params['fileType'] = file.type;
	params['fileSize'] = file.size;*/
	var body = JSON.stringify(params);
	var request = new XMLHttpRequest();
    request.onreadystatechange = () => {
        if(request.readyState == 4) {
        	console.log(request.status);
            if(request.status == 200)
            {
				Array.prototype = true;
				var response = JSON.parse(request.responseText);
			}
			else {
				console.log("Erreur");
			}
		}
		
	}
	//envoie requête
    request.open("POST", "./API/controller/insert_publication.php",true);
	request.send(body);


};

function createPublication(data,i) {
	let public = document.getElementById("publication");
	let publi = document.createElement('div');
	publi.setAttribute('class','publi');
	public.appendChild(publi);
	//titre publication
	var title = document.createElement('h2');
	title.setAttribute('class','titrepubli');
	publi.appendChild(title);
	title.innerHTML = data[i].titre_publication;

	//date publication
	var date_publi = document.createElement('h3');
	date_publi.setAttribute('class','date_publi');
	publi.appendChild(date_publi);
	date_publi.innerHTML = data[i].date_publication;

	//coeur publication
	var reac_div = document.createElement('div');
	reac_div.setAttribute('class','reaction');
	var reac = document.createElement("img");
	reac.setAttribute("class", "reac");
	reac.setAttribute("src", "./SRC/heart.png");
	reac.setAttribute("id", data[i].id_publication);
	reac.setAttribute('onclick','chngimg()'); 
	publi.appendChild(reac_div);
	reac_div.appendChild(reac);

	//voir plus publication
	var para = document.createElement('p');
	para.setAttribute("id","paraVoir");
	var link = document.createElement('a');
	link.setAttribute("href", "publication.php?id="+data[i].id_publication);
	link.setAttribute("class", "voirPlus");
	link.setAttribute("id", data[i].id_publication);
	link.innerHTML = "→ Voir plus";
	publi.appendChild(para);
	para.appendChild(link);
}

//pop up new publication
function popupAppear()
{
	document.getElementById("publier").style.display = "block";
}
	
//ferme pop up new publication
function popupClose()
{
	document.getElementById("publier").style.display = "none";
}

//active warning new publication
function noFile()
{
	document.getElementById("publier").style.display = "block";
}

//ferme pop up new publication	
function pubClose()
{
	document.location.href="index.php"; 
}

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

//si user se déconnecte
if(document.getElementById("logout") != null) {
	document.getElementById("logout").onclick = event => {
		fetch("./API/user/logout.php")
			.then( response => response.json() )
			.then( data => {
				document.location.href="index.php"; 
			})
			.catch(error => { console.log(error) });
	};
	
}
