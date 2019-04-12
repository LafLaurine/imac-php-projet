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

// fill genres from generate_categories.php
document.ready( () => {
	fetch("./API/controller/get_categories.php")
		.then( response => response.json() )
		.then( data => {
            let categories = document.getElementById('trier');
			data.forEach( categorie => {
                let option  = document.createElement("option");
                option.innerHTML = categorie;
                option.classList.add("categorie");
                categories.appendChild(option);
			});
		})
		.catch(error => { console.log(error) });
});