import {StoresHelper} from '../helpers/Stores-helper.js'

export const SearchStoresComponent={
    template: '#search-stores-template',
    data() {
        return {
            storeLogo: './assets/img/logo.svg',
            stores:[],
            inputSearch:'',
        }
    },
    async created(){
        let resultStores=await StoresHelper.all();
        this.stores=JSON.parse(localStorage.getItem('stores'));
    },
    mounted: function() {
        $('title').html('Buscar empresa - Págia Inicial');
    },
    methods:{
        async searchStores(){
            this.stores=await StoresHelper.findByNameStorage(this.inputSearch);
        },
    }

}
