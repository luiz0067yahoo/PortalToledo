import {conect} from '../untils/conect.js'
export const ItemGroupsAdditionalHelpers = {
    url:'item-grupo-adicionals',
    async findByStoreAlias(storeAlias){
        return await conect.get(this.url,{"empresa.apelido":storeAlias,ativo:true});
    }, 
}