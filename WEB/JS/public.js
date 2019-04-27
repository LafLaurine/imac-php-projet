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
				let content = document.getElementById("publicontent");
				let date = document.getElementById("date");
				let commentaire = document.getElementById("commentaire");
				titre_publi.innerHTML = publi.titre_publication;
				content.innerHTML = publi.content;
				date.innerHTML = publi.date_publication;
			});
		})
		.catch(error => { console.log(error) });
});