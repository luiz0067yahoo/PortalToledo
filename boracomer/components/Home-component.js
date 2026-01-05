import {until} from '../untils/until.js'
import {conect} from '../untils/conect.js'
import {UsersHelper} from '../helpers/Users-helper.js'
import {StoresHelper} from '../helpers/Stores-helper.js'
export const HomeComponent={
    template: '#home-template',
    data() {
        return {
            store:{nome:'Bora Comer'},
            storePath: '',
            storeLogo: './assets/img/logo.svg',
            storeText1: 'Bora satisfazer',
            storeText2: 'seu apetite!',
            userName: '',
            userPassword: '',
        }
    },
    async created(){
        if(!until.isEmpty(this.$route.params.aliasStore)){
            this.storePath='/empresa/'+this.$route.params.aliasStore;
            this.store= await StoresHelper.findByAliasLocalStorage(this.$route.params.aliasStore);
        }
    },
    mounted: function() {
        $('title').html(this.store.nome+' - Págia Inicial');
    },
    methods:{
        async login(){
            let resultLogin;
            try{
                resultLogin=await UsersHelper.login(this.userName,this.userPassword);
                if((resultLogin!=undefined)&&(resultLogin!=null)&&(resultLogin.lenght!=0)){
                }
            }
            catch(e){
                if(this.storePath=='')
                    this.$router.push({ name: 'login-error',path: '/login-error', params: { menssageError: e.message} });
                else
                    this.$router.push({ name: 'login-error-store',path: this.storePath+'login-error', params: { menssageError: e.message} });
            }
        },
        anonymous(){
            if(this.storePath=='')
                this.$router.push({ name: 'buscar-loja',path: '/buscar-loja',});                
            else
                this.$router.push({ name: 'panel-store',path: this.storePath+'panel', });  
        },
    }

}
