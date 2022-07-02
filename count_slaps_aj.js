let bonusBool = false;

async function ajaxFetch(action, nonce, x, y){

	var1 = x ? x : false;
	var2 = y ? y : false;

	const ajax = fetch(myAjax.ajaxurl, {

		method: "POST",
		headers: {

			'content-Type': 'application/x-www-form-urlencoded; charset-UTF-8'
		},
		body: `action=${action}&nonce=${nonce}&var1=${var1}&var2=${var2}`
	});

	const data = (await ajax).json();

	const object = await data;

	return object;
}

function adminReset(){

	const counter1 = document.getElementById('slap1');
	const counter2 = document.getElementById('slap2');
	const nonce = document.getElementById('nonce-div').getAttribute('data-nonce');

	ajaxFetch('reset_slaps', nonce).then(object=>{

		console.log(object);
		counter1.innerHTML = '0';
		counter2.innerHTML = '0';
	});
}

function toggleCounting(){

	const counter1 = document.getElementById('slap1');
	const counter2 = document.getElementById('slap2');
	const nonce = document.getElementById('nonce-div').getAttribute('data-nonce');

	ajaxFetch('toggle_slaps', nonce).then(object=>{

		console.log(object);
		counter1.innerHTML = object['slap1'];
		counter2.innerHTML = object['slap2'];
		document.getElementById('count-toggle').innerHTML = object['state'] ? 'Stop Counting' : 'Start Counting';
	})
}

function returnSlaps(){

	const counter1 = document.getElementById('slap1');
	const counter2 = document.getElementById('slap2');
	const nonce = document.getElementById('nonce-div').getAttribute('data-nonce');

	ajaxFetch('return_slaps', nonce).then(object=>{

		counter1.innerHTML = object['slap1'];
		counter2.innerHTML = object['slap2'];
	});
}

document.addEventListener('DOMContentLoaded', ()=>{

	const counter1 = document.getElementById('slap1');
	const counter2 = document.getElementById('slap2');
	const nonce = document.getElementById('nonce-div').getAttribute('data-nonce');
	
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

	bonusBool = (parseInt(counter2.innerHTML) + 1) % 666 ? true : false;

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