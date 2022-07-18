let counter1;
let counter2;
let nonce;
let timer = false;

function slap(vote){

	let bonus = false;
	
	if(vote == 'slap1'){
		
		const current = new Date();
		bonus = current.getMinutes() == 0 ? true : false;
	}else if(vote == 'slap2'){
		
		bonus = (parseInt(counter2.innerHTML) + 1) % 666 == 0 ? true : false;
		const compare = new Date();

		if(bonus)timer = new Date();

		if(timer){

			if(timer.getMinutes() == compare.getMinutes()){
				
				bonus = true;
			}else{timer = false;}
		}
	}

	ajaxFetch('slap', nonce, vote, bonus).then(object=>{

		if(bonus)showBonus(vote);
		console.log(object);
	});
}

function showBonus(vote){
	
	const bonus = document.getElementById(`${vote}-bonus`);
	
	bonus.style.opacity = 1;
	
	setTimeout(()=>{
		
		bonus.style.opacity = 0;
	}, 1000);
}

document.addEventListener('DOMContentLoaded', ()=>{

	counter1 = document.getElementById('slap1');
	counter2 = document.getElementById('slap2');
	nonce = document.getElementById('nonce-div').getAttribute('data-nonce');

	ajaxFetch('return_slaps').then(object=>{
		
		counter1.innerHTML = object['slap1'];
		counter2.innerHTML = object['slap2'];
		console.log(object);
	});

	setInterval(()=>{
		
		ajaxFetch('return_slaps').then(object=>{
		
			counter1.innerHTML = object['slap1'];
			counter2.innerHTML = object['slap2'];
		});
	}, 3000);
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