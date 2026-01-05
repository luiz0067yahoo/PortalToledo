import {until} from '../untils/until.js'
import {StoresHelper} from '../helpers/Stores-helper.js'
import {GroupsOfProductsHelper} from '../helpers/Groups-of-products-helper.js'
import {ProductsHelper} from '../helpers/Products-helper.js'
export const SearchProductsComponent={
    template: '#search-products-template',
    data() {
        return {
            store:{nome:'Bora Comer'},
            storePath: '',
            storeLogo: './assets/img/logo.svg',
            products:[],
            groupsOfProducts:[],
            selectGroupOfProduct:null,
            inputSearch:'',
        }
    },
    async created(){
        if(!until.isEmpty(this.$route.params.aliasStore)){
            this.storePath='/empresa/'+this.$route.params.aliasStore;
            this.store=StoresHelper.findByAliasLocalStorage(this.$route.params.aliasStore);
        }
        this.groupsOfProducts= await GroupsOfProductsHelper.findByStoreAliasLocalStorage(this.$route.params.aliasStore);
    },
    mounted: function() {
        $('title').html('Buscar empresa - Págia Inicial');
    },
    methods:{
        async searchProducts(){
            try{
                if(!until.isEmpty(this.selectGroupOfProduct)){
                    this.products=await ProductsHelper.findByStoreAliasGroupDescriptionLocalStorage(this.$route.params.aliasStore,this.selectGroupOfProduct.nome,this.inputSearch);
                    this.$router.push({ name: 'produtos-store-by-group',path: this.storePath+'produtos/'+this.selectGroupOfProduct.nome,params: {groupName:this.selectGroupOfProduct.nome, products: JSON.stringify(this.products)} }); 
                }
                else{
                    this.products=await ProductsHelper.findByStoreAliasGroupDescriptionLocalStorage(this.$route.params.aliasStore,"",this.inputSearch);
                    this.$router.push({ name: 'produtos-store',path: this.storePath+'produtos',params: {products: JSON.stringify(this.products)} }); 
                }
            }catch(e){console.log(e.message);}
        },
        goBack(){
            this.$router.go(-1);
            
        },
    }
    
}
