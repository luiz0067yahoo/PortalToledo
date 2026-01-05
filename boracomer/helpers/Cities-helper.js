import {conect} from '../untils/conect.js'
export const CitiesHelpers = {
    url:'municipios',
    async findByStoreAlias(storeAlias){
        return await conect.get(this.url,{"empresa.apelido":storeAlias,ativo:true});
    }, 
    async all(){
        return await conect.get(this.url,{});
    }, 
}