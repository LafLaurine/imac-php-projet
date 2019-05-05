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
			let cat_publi = document.getElementById('publicategorie');
			
			let option_pub  = document.createElement("option");
			option_pub.innerHTML = "choisir une option";
			option_pub.setAttribute('disabled', '')
			option_pub.setAttribute('selected', '')
			option_pub.setAttribute('value', '')

			cat_publi.appendChild(option_pub);

			data.forEach( categorie => {
				let titre_cat = document.getElementById("titrecategorie");
				let choix_cat  = document.createElement("li");
				let cate  = document.createElement("a");
				cate.setAttribute("data-id_cat",categorie.id_categorie);
				choix_cat.appendChild(cate);
				cate.innerHTML = categorie.nom_categorie;

                let option_pub  = document.createElement("option");
				option_pub.innerHTML = categorie.nom_categorie;
				option_pub.value=categorie.id_categorie;
				option_pub.classList.add("categorie");
				categories.appendChild(choix_cat);
 				titre_cat.innerHTML = categorie[Object.keys(categorie)[1]];
				cat_publi.appendChild(option_pub);
			});
		})
		.catch(error => { console.log(error) });

		fetch("./API/controller/get_topics.php")
		.then( response => response.json() )
		.then( data => {
			console.log(data);
			
			let topics = document.getElementById('choixtopic');
			let cat_topic = document.getElementById('publitopic');
			data.forEach(topic => {
				let choix_topic  = document.createElement("li");
				let topi  = document.createElement("a");
				topi.setAttribute("data-id_topic",topic.id_topic);
				choix_topic.appendChild(topi);
				topi.innerHTML = topic.nom_topic;
				// let option_pub  = document.createElement("option");
				// option_pub.value=topic.id_topic;
                // option_pub.innerHTML = topic.nom_topic;
				// option_pub.classList.add("topic");
				// cat_topic.appendChild(option_pub);
				topics.appendChild(choix_topic);
				
			});
		})
		.catch(error => { console.log(error) });
});


//tri topics par cat
document.getElementById('publicategorie').onchange = event  => {
	event.preventDefault();
	console.log("oulala");
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
				reac.setAttribute("src", "./SRC/heart.png");
				reac.setAttribute('onclick','chngimg()'); 
				public.appendChild(reac_div);
				reac_div.appendChild(reac);

				var para = document.createElement('p');
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

});


document.getElementById("validerpubli").onclick = event => {
	event.preventDefault();
	console.log("oulala");
    const form = document.querySelector('#form');
    let params = {};
    if(form.titre.value)
        params ['titre'] = form.titre.value;
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
  		formData.append('file', file, file.name);
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
    request.send(body);
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
	document.location.href="index.html"; 
}

function chngimg() {
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