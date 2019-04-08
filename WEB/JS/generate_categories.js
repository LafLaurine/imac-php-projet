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

// fill genres from data_genres.php
document.ready( () => {
	fetch("./API/controller/get_categories.php")
		.then( response => response.json() )
		.then( data => {
            let categories = document.getElementById('choixcategorie');
			data.forEach( categorie => {
                let li  = document.createElement("li");
                li.innerHTML = categorie;
                li.classList.add("categorie");
                categories.appendChild(li);
			});
		})
		.catch(error => { console.log(error) });
});