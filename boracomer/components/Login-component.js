import {until} from '../untils/until.js'
import {StoresHelper} from '../helpers/Stores-helper.js'
export const LoginComponent={
    template: '#login-template',
    data() {
        return {
            store:{nome:'Bora Comer'},
            storePath: '',
            storeLogo: './assets/img/logo.svg',
            storeText1: 'Bora satisfazer',
            storeText2: 'seu apetite!',
        }
    },
    async created(){
        if(!until.isEmpty(this.$route.params.aliasStore)){
            console.log(storePath);
            this.storePath='/empresa/'+this.$route.params.aliasStore;
            this.store= await StoresHelper.findByAliasLocalStorage(this.$route.params.aliasStore);
        }
    },
    mounted: function() {
        $('title').html(this.store_name+' - Login');
    },
    methods:{
       
    }

}
