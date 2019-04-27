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
			//let cat_publi = document.getElementById('publicategorie');
			data.forEach( categorie => {
				let choix_cat  = document.createElement("li");
				choix_cat.innerHTML = categorie.nom_categorie;
                let option_pub  = document.createElement("option");
				option_pub.innerHTML = categorie.nom_categorie;
				option_pub.value=categorie.id_categorie;
				option_pub.classList.add("categorie");
				categories.appendChild(choix_cat);
				//cat_publi.appendChild(option_pub);
			});
		})
		.catch(error => { console.log(error) });
});

//get topics
document.ready( () => {
	fetch("./API/controller/get_topics.php")
		.then( response => response.json() )
		.then( data => {
			let topics = document.getElementById('choixtopic');
			let cat_topic = document.getElementById('publitopic');
			data.forEach( topic => {
				let choix_topic  = document.createElement("li");
				choix_topic.innerHTML = topic.nom_topic;
				let option_pub  = document.createElement("option");
				option_pub.value=topic.id_topic;
                option_pub.innerHTML = topic.nom_topic;
				option_pub.classList.add("topic");
				topics.appendChild(choix_topic);
				cat_topic.appendChild(option_pub);
			});
		})
		.catch(error => { console.log(error) });
});


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
				var reac = document.createElement('h3');
				reac.innerHTML = "Réactions :";
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

document.getElementById("validerpubli").onclick = event => {
    event.preventDefault();
    const form = document.querySelector('#form');
    let params = {};
    if(form.titre.value)
        params ['titre'] = form.titre.value;
    if(form.publitopic.value)
        params['topic'] = form.publitopic.value;
    if(form.contenu.value)
		params['content'] = form.contenu.value;
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
    request.open("POST", "http://localhost/PHP/PROJET_NEW/imac-php-projet/WEB/API/controller/insert_publication.php",true);
    request.send(body);
};