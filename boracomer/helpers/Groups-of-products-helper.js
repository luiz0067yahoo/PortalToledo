import {conect} from '../untils/conect.js'
import { until } from '../untils/until.js';

export const GroupsOfProductsHelper = {
    url:'grupos',
    async findByStoreAlias(storeAlias){
		let resultGroupsOfProducts;
        try{
            resultGroupsOfProducts= await conect.get(this.url,{"empresa.apelido":storeAlias,ativo:true});
            localStorage.groups=JSON.stringify(resultGroupsOfProducts.data);
            return resultGroupsOfProducts.data
        }catch(e){throw Error("Erro ao buscar grupo de produtos");}
    },
    async findByStoreAliasLocalStorage(storeAlias){
		if (!until.isEmpty(JSON.parse(localStorage.getItem('groups')))){
            return JSON.parse(localStorage.getItem('groups'));
        }
        return this.findByStoreAlias(storeAlias);
    }, 
    async findByStoreAliasNameGroupLocalStorage(storeAlias,groupName){
        var accumulatorgroups=[];
        try{
            if (until.isEmpty(JSON.parse(localStorage.getItem('groups')))){
                this.findByStoreAliasLocalStorage(storeAlias);
            }
            JSON.parse(localStorage.getItem('groups')).forEach(element => {
                if(
                    (element.nome.toUpperCase().indexOf(groupName.toUpperCase())>=0)
                ){
                    accumulatorgroups.push(element);
                }
            });
            return accumulatorgroups;
        }
        catch(e){
            throw Error("Erro ao buscar grupo de produtos");
        }
    }, 
}