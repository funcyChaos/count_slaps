class SlapCounter{

	constructor(){

		this.nonce = document.getElementById('nonce-div').getAttribute('data-nonce');

		this.counter1 = 0;
		this.counter2 = 0;

		this.timeDiff = 30;
		this.slap2bonus = false;

		this.xmlcounter1 = document.getElementById('slap1');
		this.xmlcounter2 = document.getElementById('slap2');

		document.getElementById('slap1btn').addEventListener('click',()=>this.slap('slap1'));
		document.getElementById('slap2btn').addEventListener('click',()=>this.slap('slap2'));

		setInterval(() => {
			
			this.ajaxFetch('tally_slaps').then(object=>{
				
				console.log(object);
				this.cnt1inner = object['slap1'];
				this.cnt2inner = object['slap2'];
			});
			
			this.counter1 = 0;
			this.counter2 = 0;
		}, 3000);
	}

	set cnt1inner(x){this.xmlcounter1.innerHTML = x;}
	set cnt2inner(x){this.xmlcounter2.innerHTML = x;}

	slap(vote){
		
		const current = new Date();

		if(vote == 'slap1'){
			
			if(current.getMinutes() == 0){
				
				this.counter1 += 2;
				this.showBonus(vote);
			}else{

				this.counter1 += 1;
			}
		}else if(vote == 'slap2'){
	
			if(	
			(parseInt(this.xmlcounter2.innerHTML) + 1) % 666 == 0 ||
			this.slap2bonus
			){
			
				if(!this.slap2bonus){

					this.timeDiff = new Date();
					this.slap2bonus = true;
				}

				if(current.getSeconds() - this.timeDiff.getSeconds() > 10)this.slap2bonus = false;

				this.counter2 += 6;
				this.showBonus(vote);
			}else{
				this.counter2 += 1;
			}
		}
	}

	showBonus(vote){
	
		const bonus = document.getElementById(`${vote}-bonus`);
		
		bonus.style.opacity = 1;
		
		setTimeout(()=>{
			
			bonus.style.opacity = 0;
		}, 1000);
	}

	async ajaxFetch(action){
	
		const ajaxReq = fetch(ajax.ajaxurl, {
	
			method: "POST",
			headers: {
	
				'content-Type': 'application/x-www-form-urlencoded; charset-UTF-8'
			},
			body: `action=${action}&nonce=${this.nonce}&slap1=${this.counter1}&slap2=${this.counter2}`
		});
	
		const data = (await ajaxReq).json();
	
		const object = await data;
	
		return object;
	}
}

document.addEventListener('DOMContentLoaded', ()=>{

	let counter = new SlapCounter();
	
	counter.ajaxFetch('return_slaps').then(object=>{
		
		counter.cnt1inner = object['slap1'];
		counter.cnt2inner = object['slap2'];
		console.log(object);
	});
});