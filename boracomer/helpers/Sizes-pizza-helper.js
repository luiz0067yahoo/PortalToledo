import {conect} from '../untils/conect.js'
import { until } from '../untils/until.js';
export const SizesPizzaHelper = {
    url:'tamanhos',
    async findByStoreAlias(storeAlias){
		let resultSizesPizza;
        try{
            resultSizesPizza= await conect.get(this.url,{"empresa.apelido":storeAlias,ativo:true});
            localStorage.sizesPizza=JSON.stringify(resultSizesPizza.data);
            return resultSizesPizza.data
        }catch(e){throw Error("Erro ao buscar tamanho da pizza");}
    },
    async findByStoreAliasLocalStorage(storeAlias){
		if (!until.isEmpty(JSON.parse(localStorage.getItem('sizesPizza')))){
            return JSON.parse(localStorage.getItem('sizesPizza'));
        }
        return this.findByStoreAlias(storeAlias);
    }, 
    async findByStoreAliasIdLocalStorage(storeAlias,id){
        var accumulatorSizePizza=null;
        try{
            if(until.isEmpty(JSON.parse(localStorage.getItem('sizesPizza')))){
                await this.findByStoreAlias(storeAlias);
            } 
            JSON.parse(localStorage.getItem('sizesPizza')).forEach(element => {
                if(element.id==id){
                    accumulatorSizePizza=element;
                    return element;
                }
            });
            return accumulatorSizePizza;
        }
        catch(e){
            throw Error("Erro ao buscar tamanho da pizza");
        }
    },
}