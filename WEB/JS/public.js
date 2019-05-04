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
				let val_comm = document.getElementById("valider_comm");
				let reac = document.getElementById("reaction");
				var img_reac = document.createElement("img");
				img_reac.setAttribute("id", "reac");
				img_reac.setAttribute("src", "./SRC/heart.png");
				img_reac.setAttribute('onclick','chngimg()');
				reac.appendChild(img_reac);
				val_comm.setAttribute("data-id_publication",publi.id_publication)
				let content = document.getElementById("publicontent");
				let date = document.getElementById("date");
				let commentaire = document.getElementById("commentaire");
				titre_publi.innerHTML = publi.titre_publication;
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
				let com_h3 = document.createElement("h3");
				com_h3.setAttribute("id","comm_pub");
				let date_h3 = document.createElement("h3");
				date_h3.setAttribute("id","date_comm");
				com_div.appendChild(com_h3);
				com_div.appendChild(date_h3);
				com_h3.innerHTML = comm.content_com;
				date_h3.innerHTML = comm.date_commentaire;
			});
		})
		.catch(error => { console.log(error) });
});

document.getElementById("valider_comm").onclick = event => {
		let valid = document.getElementById("valider_comm");
		let id_publication = valid.getAttribute("data-id_publication");
		console.log(id_publication);
		event.preventDefault();
		const form = document.querySelector('#pub_commentaire');
		let params = {};
		if(form.publi_com.value)
			params['publi_com'] = form.publi_com.value;
		params['id_publication'] = id_publication;
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
};


document.getElementById("logout").onclick = event => {
	fetch("./API/user/logout.php")
		.then( response => response.json() )
		.then( data => {
		})
		.catch(error => { console.log(error) });
};

function pubClose()
{
	document.location.href="index.html"; 
}

function chngimg() {
	var img = document.getElementById('reac').src;
	var count = 0;
	if (img.indexOf('heart.png')!=-1) {
		document.getElementById('reac').src  = './SRC/heart_pink.png';
		count = count+1;
	}
	 else {
	   document.getElementById('reac').src = './SRC/heart.png';
	   if(count !=0) {
		   count = count-1;
	   }
	   
   }
}