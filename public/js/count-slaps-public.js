let bonusBool = false;
let counter1;
let counter2;
let nonce;

document.addEventListener('DOMContentLoaded', ()=>{

	counter1 = document.getElementById('slap1');
	counter2 = document.getElementById('slap2');
	nonce = document.getElementById('nonce-div').getAttribute('data-nonce');

	ajaxFetch('return_slaps').then(object=>{
		
		counter1.innerHTML = object['slap1'];
		counter2.innerHTML = object['slap2'];
		console.log(object);
	});
});

function slap(vote){
	
	bonusBool = (parseInt(counter2.innerHTML) + 1) % 666 == 0 ? true : false;

	ajaxFetch('slap', nonce, vote, bonusBool).then(object=>{

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