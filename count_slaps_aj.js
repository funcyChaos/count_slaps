function count_slaps(vote){

	const counter1 = document.getElementById('slap1');
	const counter2 = document.getElementById('slap2');
	const div = document.getElementById('nonce-div');

	fetch(myAjax.ajaxurl, {

		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
		},
		body: `action=count_slaps&nonce=${div.getAttribute('data-nonce')}&slap=${vote}`
	})
	.then(res=>res.json())
	.then(object=>{

		if(object['slap1']){

			counter1.innerHTML = object['slap1'];
		}else{

			counter2.innerHTML = object['slap2'];
		}

		console.log(object);
	});
}