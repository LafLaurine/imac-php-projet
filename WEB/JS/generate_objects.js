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
			data.forEach( categorie => {
				let choix_cat  = document.createElement("li");
				choix_cat.innerHTML = categorie;
                let option_pub  = document.createElement("option");
                option_pub.innerHTML = categorie;
				option_pub.classList.add("categorie");
				categories.appendChild(choix_cat);
				cat_publi.appendChild(option_pub);
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
				choix_topic.innerHTML = topic;
                let option_pub  = document.createElement("option");
                option_pub.innerHTML = topic;
				option_pub.classList.add("topic");
				topics.appendChild(choix_topic);
				cat_topic.appendChild(option_pub);
			});
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