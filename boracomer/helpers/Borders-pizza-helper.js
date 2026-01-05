import {conect} from '../untils/conect.js'
import { until } from '../untils/until.js';
export const BordersPizzaHelper = {
    url:'bordas',
    async findByStoreAlias(storeAlias){
		let resultBordersPizza;
        try{
            resultBordersPizza= await conect.get(this.url,{"empresa.apelido":storeAlias,ativo:true});
            localStorage.bordersPizza=JSON.stringify(resultBordersPizza.data);
            return resultBordersPizza.data
        }catch(e){throw Error("Erro ao buscar borda da pizza");}
    },
    async findByStoreAliasLocalStorage(storeAlias){
		if (!until.isEmpty(JSON.parse(localStorage.getItem('bordersPizza')))){
            return JSON.parse(localStorage.getItem('bordersPizza'));
        }
        return this.findByStoreAlias(storeAlias);
    }, 
    async findByStoreAliasIdLocalStorage(storeAlias,id){
        var accumulatorBorderPizza=null;
        try{
            if(until.isEmpty(JSON.parse(localStorage.getItem('bordersPizza')))){
                await this.findByStoreAlias(storeAlias);
            } 
            JSON.parse(localStorage.getItem('bordersPizza')).forEach(element => {
                if(element.id==id){
                    accumulatorBorderPizza=element;
                    return element;
                }
            });
            return accumulatorBorderPizza;
        }
        catch(e){
            throw Error("Erro ao buscar borda da pizza");
        }
    },
}