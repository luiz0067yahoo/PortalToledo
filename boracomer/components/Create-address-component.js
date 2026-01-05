import {until} from '../untils/until.js'
import {StoresHelper} from '../helpers/Stores-helper.js'
export const CreateAddressComponent={
    template: '#create-address-template',
    data() {
        return {
            store:{nome:'Bora Comer'},
            store_path: '',
            store_logo: './assets/img/logo.svg',
            store_text1: 'Bora satisfazer',
            store_text2: 'seu apetite!',
        }
    },
    async created(){
        if(!until.isEmpty(this.$route.params.aliasStore)){
            this.storePath='/empresa/'+this.$route.params.aliasStore;
            this.store= await StoresHelper.findByAliasLocalStorage(this.$route.params.aliasStore);
        }
    },
    methods:{
        
    }

}
