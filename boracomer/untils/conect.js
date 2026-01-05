//const serverUrl="http://209.145.62.221:1337/";
const serverUrl="https://edp.whatsmatic.com/api/";
const limityAppTime=10*60*1000;
export const conect = {
	urlAutRequest:serverUrl+"auth/local",
	bodyRequest:{
		"identifier": "food_api@nobresistemas.com",
		"password": "jT4h4MtUqkNjLkG"
	},
	currentUser:null,
	startAppTime:(new Date())-limityAppTime,
	jwt:"",
	async startApp() {
		try {
			const response = await axios.post(this.urlAutRequest,this.bodyRequest,{headers:{"content-type":"application/json",}});
			this.currentUser=response.data.user;
			this.jwt=response.data.jwt;
			localStorage.token=this.jwt;
			localStorage.user=this.user;
			this.startAppTime=new Date();
			return response.data;
		} catch (error) {throw Error("Não foi Possível carregar o sistema!");}	
	},
	async  get(url,data) {
		if ((!((this.jwt!=null)&&(this.jwt.length>0)))||(((new Date())-this.startAppTime)>limityAppTime))
			try{await this.startApp();}
			catch(e){throw Error(e.message);}
		return await axios.get(serverUrl+url+"?"+(new URLSearchParams(data)).toString(),{headers:{Authorization: ("Bearer "+this.jwt),"content-type":"application/json",}});
	},
	async  post(url,data) {
		if ((!((this.jwt!=null)&&(this.jwt.length>0)))||(((new Date())-this.startAppTime)>limityAppTime))
			try{await this.startApp();}
			catch(e){throw Error(e.message);}
		return await axios.post(serverUrl+url,data,{data:data,headers:{Authorization: ("Bearer "+this.jwt),"content-type":"application/json",}});
	},
}