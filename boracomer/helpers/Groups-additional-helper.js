import {conect} from '../untils/conect.js'
export const GroupsAdditionalHelpers = {
    url:'grupo-adicionals',
    async findByStoreAlias(storeAlias){
        return await conect.get(this.url,{"empresa.apelido":storeAlias,ativo:true});
    }, 
}