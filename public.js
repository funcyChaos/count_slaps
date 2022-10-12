class SlapCounter{
	constructor(){
		this.nonce			= document.getElementById('nonce-div').getAttribute('data-nonce');
		this.slapCount1	= 0;
		this.slapCount2	= 0;
		this.xmlCount1	= document.getElementById('xml_count_1');
		this.xmlCount2	= document.getElementById('xml_count_2');
		this.slap1bonus	= false
		this.timeDiff1 	= false;
		this.slap2bonus = false;
		this.timeDiff2 	= false;

		document.getElementById('slap_btn_1').addEventListener('click',()=>this.slap('team1'));
		document.getElementById('slap_btn_2').addEventListener('click',()=>this.slap('team2'));

		setInterval(() => {
			this.ajaxFetch('tally_slaps').then(object=>{
				console.log(object);
				this._xmlCount1 = object['team1'];
				this._xmlCount2 = object['team2'];
			});
			
			this.slapCount1 = 0;
			this.slapCount2 = 0;
		}, 3000);
	}

	set _xmlCount1(x){this.xmlCount1.innerHTML = x;}
	set _xmlCount2(x){this.xmlCount2.innerHTML = x;}

	slap(vote){
		const current = new Date();

		if(vote == 'team1'){
			if((parseInt(this.xmlCount1.innerHTML) + 1) % 666 == 0 || this.slap1bonus){
				if(!this.slap1bonus){
					this.timeDiff1 = new Date();
					this.slap1bonus = true;
				}else{
					if(current.getSeconds() - this.timeDiff1.getSeconds() > 6)this.slap1bonus = false;
				}

				this.slapCount1 += 6;
				this.showBonus(vote);
			}else{
				this.slapCount1 += 1;
			}
		}else if(vote == 'team2'){
			if((parseInt(this.xmlCount2.innerHTML) + 1) % 666 == 0 || this.slap2bonus){
				if(!this.slap2bonus){
					this.timeDiff2 = new Date();
					this.slap2bonus = true;
				}else{
					if(current.getSeconds() - this.timeDiff2.getSeconds() > 6)this.slap2bonus = false;
				}

				this.slapCount2 += 6;
				this.showBonus(vote);
			}else{
				this.slapCount2 += 1;
			}
		}
	}

	showBonus(vote){
		const bonus = document.getElementById(`${vote}_bonus`);
		
		bonus.style.opacity = 1;
		
		setTimeout(()=>{
			bonus.style.opacity = 0;
		}, 500);
	}

	async ajaxFetch(action){
		const ajaxReq = fetch(ajax.ajaxurl, {
			method: "POST",
			headers: {
				'content-Type': 'application/x-www-form-urlencoded; charset-UTF-8'
			},
			body: `action=${action}&nonce=${this.nonce}&team1=${this.slapCount1}&team2=${this.slapCount2}`
		});
	
		const  data		= (await ajaxReq).json();
		const	 object	= await data;
		return object;
	}
}

document.addEventListener('DOMContentLoaded', ()=>{
	let counter = new SlapCounter();
	
	counter.ajaxFetch('return_slaps').then(object=>{
		counter._xmlCount1 = object['team1'];
		counter._xmlCount2 = object['team2'];
		console.log(object);
	});
})