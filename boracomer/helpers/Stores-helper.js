import {until} from '../untils/until.js'
import {conect} from '../untils/conect.js'
export const StoresHelper= {
    url:'lojas',
    async findByAlias(alias){
        let resulStore;
        try{
            resulStore= await conect.get(this.url,{apelido:alias});
            if(!until.isEmpty(resulStore.data)){
                localStorage.store=JSON.stringify(resulStore.data[0]);
            }
            return resulStore.data;
        }catch(e){throw Error("Erro ao buscar empresa");}
    },  
    async findByAliasLocalStorage(alias){
        var acumulador_store={};
        if(until.isEmpty(JSON.parse(localStorage.getItem('stores')))){
            await this.all();
        }
        JSON.parse(localStorage.getItem('stores')).forEach(element => {
            if((element.apelido.indexOf(alias)>=0)){
                acumulador_store=element;
            }
        });
        localStorage.store=JSON.stringify(acumulador_store);
        return acumulador_store;
    },  
    async findByNameStorage(search){
        var acumulador_store=JSON.parse(localStorage.getItem('stores'));
        if(!until.isEmpty(search)){
            acumulador_store=[];
            if(until.isEmpty(JSON.parse(localStorage.getItem('stores')))){
                await this.all();
            }
            JSON.parse(localStorage.getItem('stores')).forEach(element => {
                if((element.nome.toUpperCase().indexOf(search.toUpperCase())>=0)){
                    acumulador_store.push(element);
                }
            });
        }
        return acumulador_store;
    }, 
    async all(){
        let resulStore;
        try{
            resulStore= await conect.get(this.url);
            localStorage.stores=JSON.stringify(resulStore.data);
            return resulStore.data
        }catch(e){throw Error("Erro ao buscar empresas");}
    }   
}