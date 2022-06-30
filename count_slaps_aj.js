function count_slaps(vote){

	// const userList = document.getElementById('userList');
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

		console.log(object);
	});
}