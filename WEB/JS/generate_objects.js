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


// get categories
document.ready( () => {
	fetch("./API/controller/get_categories.php")
		.then( response => response.json() )
		.then( data => {
			let categories = document.getElementById('choixcategorie');
			let att_cat = document.createElement("li");
			let all  = document.createElement("a");
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
		

			data.forEach( categorie => {
				let choix_cat  = document.createElement("li");
				let cate  = document.createElement("a");
				choix_cat.setAttribute("class","choix_cat_menu")
				cate.setAttribute("data-id_cat",categorie.id_categorie);
				choix_cat.appendChild(cate);
				cate.innerHTML = categorie.nom_categorie;
				
                let option_pub  = document.createElement("option");
				option_pub.innerHTML = categorie.nom_categorie;
				option_pub.value=categorie.id_categorie;
				option_pub.classList.add("categorie");
				categories.appendChild(choix_cat);
				cat_publi.appendChild(option_pub);
				
			});
			
		})
		.catch(error => { console.log(error) });

		fetch("./API/controller/get_topics.php")
		.then( response => response.json() )
		.then( data => {			
			let topics = document.getElementById('choixtopic');
			let cat_topic = document.getElementById('publitopic');
			data.forEach(topic => {
				/*let choix_topic  = document.createElement("li");
				let topi  = document.createElement("a");
				topi.setAttribute("data-id_topic",topic.id_topic);
				choix_topic.appendChild(topi);
				topi.innerHTML = topic.nom_topic;*/
				// let option_pub  = document.createElement("option");
				// option_pub.value=topic.id_topic;
                // option_pub.innerHTML = topic.nom_topic;
				// option_pub.classList.add("topic");
				// cat_topic.appendChild(option_pub);
				//topics.appendChild(choix_topic);
				
			});
		})
		.catch(error => { console.log(error) });
});


//tri topics par cat
document.getElementById('publicategorie').onchange = event  => {
	event.preventDefault();
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
					var data = JSON.parse(request.responseText);
					let cat_topic = document.getElementById('publitopic');
					cat_topic.innerHTML='';
					data.forEach(topic => {
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
		//$url= '?categorie=' + params['publicategorie'];
		request.open("GET", "./API/controller/get_topics.php?publicategorie="+params['publicategorie'],true);
		request.send();
};


document.ready( () => {
	fetch("./API/controller/get_publications.php")
		.then( response => response.json() )
		.then( data => {
			let public = document.getElementById("publication");
			for(var i =0; i<data.length; i++) {
				var title = document.createElement('h2');
				title.setAttribute('class','titrepubli');
				public.appendChild(title);
				title.innerHTML = data[i].titre_publication;

				var date_publi = document.createElement('h3');
				date_publi.setAttribute('class','date_publi');
				public.appendChild(date_publi);
				date_publi.innerHTML = data[i].date_publication;

				var reac_div = document.createElement('div');
				reac_div.setAttribute('class','reaction');
				var reac = document.createElement("img");
				reac.setAttribute("class", "reac");
				reac.setAttribute("onclick", 'chngimg()');
				reac.setAttribute("src", "./SRC/heart.png");
				reac.setAttribute("data-id_publication", data[i].id_publication);
				public.appendChild(reac_div);
				reac_div.appendChild(reac);

				var para = document.createElement('p');
				para.setAttribute("id","paraVoir");
				var link = document.createElement('a');
				link.setAttribute("href", "publication.html?id="+data[i].id_publication);
				link.setAttribute("class", "voirPlus");
				link.setAttribute("id", data[i].id_publication);
				link.innerHTML = "→ Voir plus";
				public.appendChild(para);
				para.appendChild(link);

			}
		})
		.catch(error => { console.log(error) });

		/*document.getElementById("heart-id1").onclick = event => {
			let heart = document.getElementById("heart-id1");
			let id_publication = heart.getAttribute("data-id_publication");
			event.preventDefault();
			const form = document.querySelector('.reaction');
			let params = {};
			params['id_publication'] = id_publication;
			var body = JSON.stringify(params);
			var request = new XMLHttpRequest();
			request.onreadystatechange = () => {
				if(request.readyState == 4) {
					if(request.status == 200)
					{
						Array.prototype = true;
						console.log(request);
						var response = JSON.parse(request.responseText);
						console.log(response);
					}
				
				}
				else {
					console.log("Erreur");
				}
			}
			request.open("POST", "http://localhost/imac-php-projet/WEB/API/controller/add_like.php",true);
			request.send(body);
		};*/

});

document.ready( () => {
	fetch("./API/controller/get_liked.php")
	.then( response => response.json() )
	.then( data => {
		var select = document.getElementsByClassName("reac");
		console.log(select);
		for (var i = 0; i < select.length; i++) {
			var id_publi = select[i].getAttribute("data-id_publication");
			console.log(id_publi);
			for (var j = 0; j<data.length; j++){
			 	if(id_publi == data[j].id_publication){
			 		if (data[j].liked == "1"){
			 			select[i].setAttribute("src", "./SRC/heart_pink.png");
			 		}
			 	}
			 }
		}

	}).catch(error => { console.log(error) });
});

document.getElementById("tri_default").onclick = event => {
	fetch("./API/controller/get_publications.php")
		.then( response => response.json() )
		.then( data => {
			let public = document.getElementById("publication");
			while (public.firstChild) {
				public.removeChild(public.firstChild);
			}
			for(var i =0; i<data.length; i++) {
				var title = document.createElement('h2');
				title.setAttribute('class','titrepubli');
				public.appendChild(title);
				title.innerHTML = data[i].titre_publication;

				var date_publi = document.createElement('h3');
				date_publi.setAttribute('class','date_publi');
				public.appendChild(date_publi);
				date_publi.innerHTML = data[i].date_publication;

				var reac_div = document.createElement('div');
				reac_div.setAttribute('class','reaction');
				var reac = document.createElement("img");
				reac.setAttribute("class", "reac");
				reac.setAttribute("onclick", 'chngimg()');
				reac.setAttribute("src", "./SRC/heart.png");
				reac.setAttribute("data-id_publication", data[i].id_publication);
				public.appendChild(reac_div);
				reac_div.appendChild(reac);

				var para = document.createElement('p');
				para.setAttribute("id","paraVoir");
				var link = document.createElement('a');
				link.setAttribute("href", "publication.html?id="+data[i].id_publication);
				link.setAttribute("class", "voirPlus");
				link.setAttribute("id", data[i].id_publication);
				link.innerHTML = "→ Voir plus";
				public.appendChild(para);
				para.appendChild(link);

			}
		})
		.catch(error => { console.log(error) });
};

document.getElementById("tri_like").onclick = event => {
	fetch("./API/controller/get_publication_from_like.php")
		.then( response => response.json() )
		.then( data => {
			let public = document.getElementById("publication");
			while (public.firstChild) {
				public.removeChild(public.firstChild);
			}
			for(var i =0; i<data.length; i++) {
				var title = document.createElement('h2');
				title.setAttribute('class','titrepubli');
				public.appendChild(title);
				title.innerHTML = data[i].titre_publication;

				var date_publi = document.createElement('h3');
				date_publi.setAttribute('class','date_publi');
				public.appendChild(date_publi);
				date_publi.innerHTML = data[i].date_publication;

				var reac_div = document.createElement('div');
				reac_div.setAttribute('class','reaction');
				var reac = document.createElement("img");
				reac.setAttribute("class", "reac");
				reac.setAttribute("onclick", 'chngimg()');
				reac.setAttribute("src", "./SRC/heart.png");
				reac.setAttribute("data-id_publication", data[i].id_publication);
				public.appendChild(reac_div);
				reac_div.appendChild(reac);

				var para = document.createElement('p');
				para.setAttribute("id","paraVoir");
				var link = document.createElement('a');
				link.setAttribute("href", "publication.html?id="+data[i].id_publication);
				link.setAttribute("class", "voirPlus");
				link.setAttribute("id", data[i].id_publication);
				link.innerHTML = "→ Voir plus";
				public.appendChild(para);
				para.appendChild(link);

			}
		})
		.catch(error => { console.log(error) });
};

document.getElementById("choixcategorie").onclick = event => {
	var id_categorie = event.target.dataset.id_cat;

	fetch("./API/controller/get_publication_from_categorie.php?id="+id_categorie)
		.then( response => response.json() )
		.then( data => {
			data.forEach(cat => {
				document.getElementById("titrecategorie").innerHTML = cat.nom_categorie;
				if(id_categorie == 100) {
					document.getElementById("titrecategorie").innerHTML  = "Tout";
				}
				let public = document.getElementById("publication");
				while (public.firstChild) {
					public.removeChild(public.firstChild);
				}
				
				for(var i =0; i<data.length; i++) {
					var title = document.createElement('h2');
					title.setAttribute('class','titrepubli');
					public.appendChild(title);
					title.innerHTML = data[i].titre_publication;

					var date_publi = document.createElement('h3');
					date_publi.setAttribute('class','date_publi');
					public.appendChild(date_publi);
					date_publi.innerHTML = data[i].date_publication;

					var reac_div = document.createElement('div');
					reac_div.setAttribute('class','reaction');
					var reac = document.createElement("img");
					reac.setAttribute("class", "reac");
					reac.setAttribute("src", "./SRC/heart.png");
					reac.setAttribute("id", data[i].id_publication);
					reac.setAttribute('onclick','chngimg()'); 
					public.appendChild(reac_div);
					reac_div.appendChild(reac);

					var para = document.createElement('p');
					para.setAttribute("id","paraVoir");
					var link = document.createElement('a');
					link.setAttribute("href", "publication.html?id="+data[i].id_publication);
					link.setAttribute("class", "voirPlus");
					link.setAttribute("id", data[i].id_publication);
					link.innerHTML = "→ Voir plus";
					public.appendChild(para);
					para.appendChild(link);

				}
			});			
		})
		.catch(error => { console.log(error) });
};


document.getElementById("validerpubli").onclick = event => {
	event.preventDefault();
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
	var id_publication;
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
    request.open("POST", "./API/controller/insert_publication.php",true);
	request.send(body);


};

/*
var request_formData = new XMLHttpRequest();
request_formData.onreadystatechange = () => {
	if(request_formData.readyState == 4) {
		if(request_formData.status == 200)
		{
			Array.prototype = true;
		}
		else {
			console.log("Erreur");
		}
	}
					
}
request_formData.open("POST", "./API/controller/insert_file.php?id_publication="+id_publication,true);
request_formData.send(formData);
*/

//Accueil : tri publi par date
document.getElementById("tri_date").onclick = event => {

	fetch("./API/controller/get_publication_from_date.php")
		.then( response => response.json() )
		.then( data => {
			data.forEach( publication => {
				let public = document.getElementById("publication");
				while (public.firstChild) {
					public.removeChild(public.firstChild);
				}
				
				for(var i =0; i<data.length; i++) {
					var title = document.createElement('h2');
					title.setAttribute('class','titrepubli');
					public.appendChild(title);
					title.innerHTML = data[i].titre_publication;

					var date_publi = document.createElement('h3');
					date_publi.setAttribute('class','date_publi');
					public.appendChild(date_publi);
					date_publi.innerHTML = data[i].date_publication;

					var reac_div = document.createElement('div');
					reac_div.setAttribute('class','reaction');
					var reac = document.createElement("img");
					reac.setAttribute("class", "reac");
					reac.setAttribute("src", "./SRC/heart.png");
					reac.setAttribute("id", data[i].id_publication);
					reac.setAttribute('onclick','chngimg()'); 
					public.appendChild(reac_div);
					reac_div.appendChild(reac);

					var para = document.createElement('p');
					para.setAttribute("id","paraVoir");
					var link = document.createElement('a');
					link.setAttribute("href", "publication.html?id="+data[i].id_publication);
					link.setAttribute("class", "voirPlus");
					link.setAttribute("id", data[i].id_publication);
					link.innerHTML = "→ Voir plus";
					public.appendChild(para);
					para.appendChild(link);

				}
			});			
		})
		.catch(error => { console.log(error) });
	
};

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

//TQ QUE VAR = 1 COEUR EST DE COULEUR ROSE = USER A LIKE, SINON VAR = 0
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
		console.log(liked);
	}

	else {
		event.target.src = './SRC/heart.png';
		if(count !=0) {
			count = count-1;
		}
		liked = 0;
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
		request.open("POST", "http://localhost/PHP/PROJET_NEW/imac-php-projet/WEB/API/controller/remove_like.php",true);
		request.send(body);
	}	
}

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
