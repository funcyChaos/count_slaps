let bonusBool = false;
let counter1;
let counter2;
let nonce;

function returnSlaps(){
	
	ajaxFetch('return_slaps').then(object=>{
		
		counter1.innerHTML = object['team1'];
		counter2.innerHTML = object['team2'];
		console.log(object);
	});
}

function adminReset(){

	const sure = confirm('Are you sure you want to reset all the slaps?');
	
	if(sure){

		ajaxFetch('reset_slaps', nonce).then(object=>{
			
			console.log(object);
			counter1.innerHTML = '0';
			counter2.innerHTML = '0';
		});
	}
}

function toggleCounting(){

	ajaxFetch('toggle_slaps', nonce).then(object=>{
		
		console.log(object);
		counter1.innerHTML = object['team1'];
		counter2.innerHTML = object['team2'];
		document.getElementById('count-toggle').innerHTML = object['state'] ? 'Stop Counting' : 'Start Counting';
	})
}

document.addEventListener('DOMContentLoaded', ()=>{

	counter1 = document.getElementById('team1');
	counter2 = document.getElementById('team2');
	nonce = document.getElementById('nonce-div').getAttribute('data-nonce');

	ajaxFetch('return_slaps').then(object=>{
		
		counter1.innerHTML = object['team1'];
		counter2.innerHTML = object['team2'];
		console.log(object);
	});
});

async function ajaxFetch(action, nonce, x, y){

	var1 = x ? x : false;
	var2 = y ? y : false;

	const ajaxReq = fetch(ajax.ajaxurl, {

		method: "POST",
		headers: {

			'content-Type': 'application/x-www-form-urlencoded; charset-UTF-8'
		},
		body: `action=${action}&nonce=${nonce}&var1=${var1}&var2=${var2}`
	});

	const data = (await ajaxReq).json();

	const object = await data;

	return object;
}