import {conect} from '../untils/conect.js'
import { until } from '../untils/until.js';
export const FlavorsPizzaHelper = {
    url:'sabors',
    async findByStoreAlias(storeAlias){
		let resultFlavorsPizza;
        try{
            resultFlavorsPizza= await conect.get(this.url,{"empresa.apelido":storeAlias,ativo:true});
            localStorage.flavorsPizza=JSON.stringify(resultFlavorsPizza.data);
            return resultFlavorsPizza.data
        }catch(e){throw Error("Erro ao buscar sabor da pizza");}
    },
    async findByStoreAliasLocalStorage(storeAlias){
		if (!until.isEmpty(JSON.parse(localStorage.getItem('flavorsPizza')))){
            return JSON.parse(localStorage.getItem('flavorsPizza'));
        }
        return await this.findByStoreAlias(storeAlias);
    }, 
    async findByStoreAliasIdLocalStorage(storeAlias,id){
        var accumulatorFlavorPizza=null;
        try{
            if(until.isEmpty(JSON.parse(localStorage.getItem('flavorsPizza')))){
                await this.findByStoreAlias(storeAlias);
            } 
            JSON.parse(localStorage.getItem('flavorsPizza')).forEach(element => {
                if(element.id==id){
                    accumulatorFlavorPizza=element;
                }
            });
            return accumulatorFlavorPizza;
        }
        catch(e){
            throw Error("Erro ao buscar sabor da pizza");
        }
    },
    async findByStoreAliasIdsLocalStorage(storeAlias,ids){
        var accumulatorFlavorsPizza=[];
        ids.split(",").forEach(id => {
            this.findByStoreAliasIdLocalStorage(storeAlias,id).then((element)=>{
                accumulatorFlavorsPizza.push(element);    
            });
        });
        return accumulatorFlavorsPizza;
    }
}