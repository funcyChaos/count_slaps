let bonusBool = false;

document.addEventListener('DOMContentLoaded', event=>{

	const counter1 = document.getElementById('slap1');
	const counter2 = document.getElementById('slap2');
	const nonce = document.getElementById('nonce-div').getAttribute('data-nonce');
	
	bonusBool = (parseInt(counter2.innerHTML) + 1) % 666 ? true : false;
	
	fetch(myAjax.ajaxurl, {
	
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
		},
		body: `action=return_slaps&nonce=${nonce}`
	})
	.then(res=>res.json())
	.then(object=>{
		
		counter1.innerHTML = object['slap1'];
		counter2.innerHTML = object['slap2'];
	
		console.log(object);
	});
});


function count_slaps(vote){

	const counter1 = document.getElementById('slap1');
	const counter2 = document.getElementById('slap2');
	const nonce = document.getElementById('nonce-div').getAttribute('data-nonce');

	fetch(myAjax.ajaxurl, {

		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
		},
		body: `action=count_slaps&nonce=${nonce}&slap=${vote}&bonus=${bonusBool}`
	})
	.then(res=>res.json())
	.then(object=>{

		if(object['slap1']){

			counter1.innerHTML = object['slap1'];
		}else{

			counter2.innerHTML = object['slap2'];
			
			if(bonusBool){

				showBonus('team2');
				console.log(bonusBool);
			}
		}

		console.log(object);
	});
}

function showBonus(team){

	bonus = document.getElementById(`${team}-bonus`);

	bonus.style.opacity = 1;

	setTimeout(()=>{
		
		bonus.style.opacity = 0;
	}, 100);
}