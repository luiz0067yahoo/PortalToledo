import {until} from '../untils/until.js'
import {ProductsHelper} from '../helpers/Products-helper.js'
import {StoresHelper} from '../helpers/Stores-helper.js'
export const OrderItensComponent={
    template: '#order-itens-template',
       data() {
            return {
                store:{nome:'Bora Comer'},
                storePath: '',
                storeLogo: './assets/img/logo.svg',
                order:[],
                total:0,
            }
        },
        async created(){
            if(!until.isEmpty(this.$route.params.aliasStore)){
                this.storePath='/empresa/'+this.$route.params.aliasStore;
                this.store=await StoresHelper.findByAliasLocalStorage(this.$route.params.aliasStore);
            }
            this.order=JSON.parse(localStorage.order);
        },
        mounted: function() {
            $('title').html(this.store.nome+' Pedido ');
        },
        methods:{
            formatMoney(val){
                return until.formatMoney(val);
            },
            isEmpty(val){
                return until.isEmpty(val);
            },
            editItem(item){
                try{
                    if(item.product.pizza===true){
                        if(!until.isEmpty(item.border)){
                            this.$router.push({ path: this.storePath+'/item-pedido/'+item.product.id+'/tamanho/'+item.size.id+'/sabores/'+this.getIdsFlavorsPizza(item.flavors).join(",")+'/borda/'+item.border.id});
                        }
                        else{
                            this.$router.push({ path: this.storePath+'/item-pedido/'+item.product.id+'/tamanho/'+item.size.id+'/sabores/'+this.getIdsFlavorsPizza(item.flavors).join(",")});
                        }    
                    }
                    else{
                        this.$router.push({ path: this.storePath+'/item-pedido/'+item.product.id});
                    }
                }
                catch(e){
                    console.log(e);
                }
            },
            addItem(){
                this.$router.push({ path: this.storePath+'/buscar-produtos/'});
            },
            goBack(){
                this.$router.go(-1);
            },

            calcPriceItem(item){
                var price=0;
                if(item.product.pizza===true){
                    price= this.calcPriceProduct(item.product,item.size,item.flavors,item.border)*item.amount;
                }
                else{
                    price=item.product.preco*item.amount;;
                }
                return price;
            },
            calcPriceProduct(product,size,flavors,border){
                var price=0;
                if(product.pizza===true){
                    price=this.calcPriceSizePizza(size)+this.sumPriceFlavorsPizza(flavors)+this.calcPriceBorderPizza(border);
                }
                else{
                    price=product.preco;
                }
                return price;
            },
            sumPriceFlavorsPizza(flavors){
                var sum=0;
                flavors.forEach(element => {
                    if(!until.isEmpty(element)&& (typeof  element.valor == 'number')){
                        sum=sum+element.valor;
                    }
                });
                return sum;
            },
            calcPriceSizePizza(size){
                var price=0;
                if(!until.isEmpty(size)){
                    price=size.valor_tamanho;
                }
                return price;
            },
            calcPriceBorderPizza(border){
                var price=0;
                if(!until.isEmpty(border)){
                    //console.log(this.border);
                    //price=this.border.valor_borda;
                }
                return price;
            },

            calcTotal(){
                var total=0;
                if (!until.isEmpty(this.order)){
                    this.order.forEach(element => {
                        //total=total+(element.amount*calcPriceProduct(element.product,element.size,element.flavors,element.border));
                        total=total+(element.amount*element.total);
                    });
                    this.total=total;
                }
                return total;
            },
            cancelOrder(){
                this.total=0;
                this.order=[];
                localStorage.order=null;
                this.$router.push({ name: 'panel-store',path: this.storePath+'panel', }); 
            },
            getIdsFlavorsPizza(flavorsPizza){
                var ids=[];
                flavorsPizza.forEach(element => {
                    if(!until.isEmpty(element)){
                        ids.push(element.id);
                    }
                });
                return ids;
            },
            getDesriptionFlavorsPizza(flavorsPizza){
                try{
                    var descriptions=[];
                    flavorsPizza.forEach(element => {
                        if(!until.isEmpty(element)&& (typeof  element.descricao === 'string')){
                            descriptions.push(element.descricao);
                        }
                    });
                }
                catch(e){
                    //console.log(e);
                }
                return descriptions;
            },
            async confirmOrder(){
                var order=[];
                var id_usuario=null;
                if (!until.isEmpty(localStorage.user)){
                    id_usuario=localStorage.user.id;
                }
                if (!until.isEmpty(this.order)){
                    this.order.forEach(element => {
                        if(product.pizza===true){
                            order.push({"id_produto":element.product.id,"quantidade":element.amount,"id_borda":element.boder.id,"ids_sabores":getIdsFlavorsPizza(element.flavors),"id_tamanho":element.size.id});
                        }
                        else{
                            order.push({"id_produto":element.product.id,"quantidade":element.amount});
                        }
                    });     
                }
                console.log(order);
            },
        }
    
    }
    