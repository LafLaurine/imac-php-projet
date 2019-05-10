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
						console.log(topic.id_topic);
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
			var count_heart_id = 1;
			for(var i =0; i<data.length; i++) {
				var title = document.createElement('h2');
				title.setAttribute('class','titrepubli');
				public.appendChild(title);
				title.innerHTML = data[i].titre_publication;

				var date_publi = document.createElement('h3');
				date_publi.setAttribute('class','date_publi');
				public.appendChild(date_publi);
				date_publi.innerHTML = data[i].date_publication;

				var reac_div = document.createElement('form');
				reac_div.setAttribute('class','reaction');
				var reac = document.createElement("img");
				reac.setAttribute("class", "reac");
				reac.setAttribute("src", "./SRC/heart.png");
				reac.setAttribute("data-id_publication", data[i].id_publication);
				reac.setAttribute("id", "heart-id"+count_heart_id);
				count_heart_id++;
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
		})
		.catch(error => { console.log(error) });

		document.getElementById("heart-id1").onclick = event => {
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
			request.open("POST", "http://localhost/imac-php-projet/WEB/API/controller/like.php",true);
			request.send(body);
		};

});

//accueil afficher publication selon catégorie
document.getElementById("choixcategorie").onclick = event => {
	var id_categorie = event.target.dataset.id_cat;

	fetch("./API/controller/get_publication_from_categorie.php?id="+id_categorie)
		.then( response => response.json() )
		.then( data => {
			data.forEach(cat => {
				document.getElementById("titrecategorie").innerHTML = cat.nom_categorie;
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


//nouvelle publication
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

	var fileSelect = document.getElementById('file');
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
  	params['fileSize'] = file.size;
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
				console.log(request);

			}
			else {
				console.log("Erreur");
			}
		}
		
	}
    request.open("POST", "./API/controller/insert_publication.php",true);
    request.send("body="+body+"&formData ="+formData);
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

function chngimg() {
	var length = document.querySelectorAll('.reac').length;

	for(var i = 0; i < length; i++)
	{
		var heart_id = document.getElementsByClassName("reac")[i].id;
		console.log(heart_id);
		var img = document.querySelector('.reac').src;
		var count = 0;
		if (img.indexOf('heart.png')!=-1) {
			document.querySelector('.reac').src  = './SRC/heart_pink.png';
			count = count+1;
		}
		 else {
		   document.querySelector('.reac').src = './SRC/heart.png';
		   if(count !=0) {
			   count = count-1;
		   }
		   
	   }
	
   }
}

if(document.getElementById("logout") != null) {
	document.getElementById("logout").onclick = event => {
		fetch("./API/user/logout.php")
			.then( response => response.json() )
			.then( data => {
			})
			.catch(error => { console.log(error) });
	};
	
}


//Accueil : tri publi par date
document.getElementById("tri_date").onclick = event => {
	var id_tri = event.target.dataset.id_date;

	fetch("./API/controller/get_publication_from_date.php?id="+id_tri)
		.then( response => response.json() )
		.then( data => {
			data.forEach( date => {
				document.getElementById("titrecategorie").innerHTML = cat.nom_categorie;
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