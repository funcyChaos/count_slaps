setTimeout(() => {
	window.location.replace("https://meetmeinthechilisparkinglot.com/#top");
}, 300000);

class SlapCounter{
	constructor(){
		this.nonce			= document.getElementById('nonce-div').getAttribute('data-nonce');
		this.xmlCount1	= document.getElementById('xml_count_1').children[0].children[0];
		this.xmlCount2	= document.getElementById('xml_count_2').children[0].children[0];
		this.slapCount1	= 0;
		this.slapCount2	= 0;
		this.slap1bonus	= false
		this.slap2bonus = false;
		this.tallying		= false;

		document.getElementById('slap_btn_1').addEventListener('click',()=>{
			this.slap('team1');
			this.tallySlaps();
		});

		document.getElementById('slap_btn_2').addEventListener('click',()=>{
			this.slap('team2');
			this.tallySlaps();
		});
	}

	set _xmlCount1(x){this.xmlCount1.innerText = x;}
	set _xmlCount2(x){this.xmlCount2.innerText = x;}

	slap(vote){
		if(vote == 'team1'){
			if((parseInt(this.xmlCount1.innerText) + 1) % 666 == 0 || this.slap1bonus){
				if(!this.slap1bonus){
					this.slap1bonus = true;
					setTimeout(() => {
						this.slap1bonus = false;
					}, 60000);
				}else{
					this.slapCount1 += 6;
					// this.showBonus(vote);
				}
			}else{
				this.slapCount1 += 1;
			}
			console.log(this.slapCount1);
		}else if(vote == 'team2'){
			if((parseInt(this.xmlCount2.innerText) + 1) % 666 == 0 || this.slap2bonus){
				if(!this.slap2bonus){
					this.slap2bonus = true;
					setTimeout(() => {
						this.slap2bonus = false;
					}, 60000);
				}else{
					this.slapCount2 += 6;
					// this.showBonus(vote);
				}

			}else{
				this.slapCount2 += 1;
			}
			console.log(this.slapCount2);
		}
	}

	showBonus(vote){
		const bonus = document.getElementById(`${vote}_bonus`);
		bonus.style.opacity = 1;
		setTimeout(()=>{
			bonus.style.opacity = 0;
		}, 500);
	}

	tallySlaps(){
		if(!this.tallying){
			this.tallying	= true;
			setTimeout(() => {
				this.apiFetch('POST').then(object=>{
					console.log(object);
					this._xmlCount1 = object['team1'];
					this._xmlCount2 = object['team2'];
					this.slapCount1 = 0;
					this.slapCount2 = 0;
					this.tallying		= false;
				});
			}, 5000);
		}else{
			return
		}
	}

	async apiFetch(method){
		let opts;

		if(method == 'POST'){
			opts = {
				method,
				headers: {
					'content-Type': 'application/json'
				},
				body: JSON.stringify({
					team1:	this.slapCount1,
					team2:	this.slapCount2
				})
			}
		}

		const req = fetch(`${url}/wp-json/count-slaps/tally-slaps`, opts);
		const  data		= (await req).json();
		const	 object	= await data;
		return object;
	}
}

document.addEventListener('DOMContentLoaded', ()=>{
	let counter = new SlapCounter();
	
	counter.apiFetch('GET').then(object=>{
		counter._xmlCount1 = object['team1'];
		counter._xmlCount2 = object['team2'];
		console.log(object);
	});
})