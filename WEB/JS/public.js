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

document.ready( () => {
	var url_string = window.location.href;
	var new_url = url_string.split('=');
	var id_publication = new_url[1];
	fetch("./API/controller/get_comm.php?id="+id_publication)
		.then( response => response.json() )
		.then( data => {
			data.forEach( comm => {
				let comm_pub  = document.getElementById("comm_pub");
				let date_comm = document.getElementById("date_comm");
				comm_pub.innerHTML = comm.content_com;
				date_comm.innerHTML = comm.date_commentaire;
			});
		})
		.catch(error => { console.log(error) });
});


/*

document.getElementById("valider_comm").onclick = event => {
    event.preventDefault();
    const form = document.querySelector('#pub_commentaire');
    let params = {};
    if(form.publi_com.value)
        params['publi_com'] = form.publi_com.value;
	var body = JSON.stringify(params);
	var request = new XMLHttpRequest();
    request.onreadystatechange = () => {
		console.log(request);
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
    request.open("POST", "http://localhost/PHP/PROJET_NEW/imac-php-projet/WEB/API/controller/insert_comm.php",true);
    request.send(body);
};*/