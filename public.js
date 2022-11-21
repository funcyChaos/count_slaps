// setTimeout(() => {
// 	window.location.replace(`${url}/fight-test#top"`);
// }, 300000);

let waitingForTimeout = false;
function setWaiting(){
	if(waitingForTimeout){
		return;
	}else{
		waitingForTimeout = true;
		setTimeout(() => {
			slapQueue.enqueue(()=>getSlaps());
			waitingForTimeout = false;
		}, 3000);
	}
}

document.addEventListener("DOMContentLoaded", ()=>{
	xmlCount1	= document.getElementById("xml_count_1").children[0].children[0];
	xmlCount2	= document.getElementById("xml_count_2").children[0].children[0];

	document.getElementById("slap_btn_1").addEventListener("click",()=>{
		slapQueue.enqueue(()=>postSlap(1));
		setWaiting();
	});

	document.getElementById("slap_btn_2").addEventListener("click",()=>{
		slapQueue.enqueue(()=>postSlap(2));
		setWaiting();
	});
	
	slapQueue.enqueue(()=>getSlaps());
});

const postSlap	= (team) => new Promise(r => r(team))
  .then(res => {
		fetch(`${url}/wp-json/count-slaps/slaps/${res}`, {
			method: "POST",
		})
			.then(res=>res.json())
			.then(obj=>console.log(obj));
});

const getSlaps	= () => new Promise(r=>r())
  .then(()=>{
		fetch(`${url}/wp-json/count-slaps/slaps/0`).then(res=>res.json()).then(obj=>{
			xmlCount1.innerText = obj["team1"];
			xmlCount2.innerText = obj["team2"];
			console.log(obj);
		});
});

class slapQueue{
	static queue 					= [];
	static pendingPromise = false;

	static enqueue(promise){
		return new Promise((resolve, reject)=>{
			this.queue.push({
				promise,
				resolve,
				reject
			});
			this.dequeue();
		});
	}

	static dequeue(){
		if(this.pendingPromise){
			return false;
		}
		const item = this.queue.shift();
		if(!item){
			return false;
		}
		try{
			this.pendingPromise = true;
			item.promise()
			.then((value)=>{
				this.pendingPromise = false;
				item.resolve(value);
				this.dequeue();
			})
			.catch(err=>{
				this.pendingPromise = false;
				item.reject(err);
				this.dequeue();
			});
		}catch(err){
			this.pendingPromise = false;
			item.reject(err);
			this.dequeue();
		}
		return true;
	}
}