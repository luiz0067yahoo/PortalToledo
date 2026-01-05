export const until = {
	isEmpty(val){
		return (val === undefined || val == null || val.length <= 0) ? true : false;
	},
	arrayAllElementsIsEmpy(array){
		var count=0
		array.forEach((element,index) => {
			if(this.isEmpty(element)){
				count=count+1;
			}
		});
		return (count===array.length);
	},
	formatMoney(val){
		return "R$ "+parseFloat(val).toFixed(2).replace('.',',');
	}
	
}