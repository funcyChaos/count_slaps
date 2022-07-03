let bonusBool = false;
let counter1;
let counter2;
let nonce;

function returnSlaps(){
	
	ajaxFetch('return_slaps', nonce).then(object=>{
		
		counter1.innerHTML = object['slap1'];
		counter2.innerHTML = object['slap2'];
		console.log(object);
	});
}

function adminReset(){
	
	ajaxFetch('reset_slaps', nonce).then(object=>{
		
		console.log(object);
		counter1.innerHTML = '0';
		counter2.innerHTML = '0';
	});
}

function toggleCounting(){
	
	ajaxFetch('toggle_slaps', nonce).then(object=>{
		
		console.log(object);
		counter1.innerHTML = object['slap1'];
		counter2.innerHTML = object['slap2'];
		document.getElementById('count-toggle').innerHTML = object['state'] ? 'Stop Counting' : 'Start Counting';
	})
}

document.addEventListener('DOMContentLoaded', ()=>{

	counter1 = document.getElementById('slap1');
	counter2 = document.getElementById('slap2');
	nonce = document.getElementById('nonce-div').getAttribute('data-nonce');

	ajaxFetch('return_slaps', nonce).then(object=>{
		
		counter1.innerHTML = object['slap1'];
		counter2.innerHTML = object['slap2'];
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