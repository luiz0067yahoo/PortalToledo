import {until} from '../untils/until.js'
import {StoresHelper} from '../helpers/Stores-helper.js'
export const LoginUserErrorComponent={
    template: '#login-user-error-template',
    //props:['menssageError'],
    data() {
        return {
            empresaNome:'Bora Comer',
            storePath: '',
            storeLogo: './assets/img/logo.svg',
            menssageError: 'Login ou senha incorreto!',
        }
    },
    async created(){
        if(!until.isEmpty(this.$route.params.aliasStore)){
            this.storePath='/empresa/'+this.$route.params.aliasStore;
            this.store= await StoresHelper.findByAliasLocalStorage(this.$route.params.aliasStore);
        }
    },
    mounted: function() {
        $('title').html(this.empresaNome+' - Págia Inicial');
    },
    methods:{
       
    }
}
