import {until} from '../untils/until.js'
import {ProductsHelper} from '../helpers/Products-helper.js'
import {StoresHelper} from '../helpers/Stores-helper.js'
import { FlavorsPizzaHelper } from '../helpers/Flavors-pizza-helper.js'
import { SizesPizzaHelper } from '../helpers/Sizes-pizza-helper.js'
import { BordersPizzaHelper } from '../helpers/Borders-pizza-helper.js'
export const OrderItemComponent={
    template: '#order-item-template',    
       data() {
            return {
                store:{nome:'Bora Comer'},
                storePath: '',
                storeLogo: './assets/img/logo.svg',
                product:{descricao:"",descricao_longa:"",preco:0,image_url:'./assets/img/logo.svg'},
                group:{nome:""},
                products:[],
                flavorsPizza:[],
                flavorsPizzaSelected:[],
                flavorsPizzaParam:[],
                sizesPizza:[],
                sizePizzaSelected:null,
                sizePizzaParam:null,
                bordersPizza:[],
                borderPizzaSelected:null,
                borderPizzaParam:null,
                amount:1,
                total:0,
                messageError:"",
            }
        },
        async created(){
            if(!until.isEmpty(this.$route.params.aliasStore)){
                this.storePath='/empresa/'+this.$route.params.aliasStore;
                this.store=await StoresHelper.findByAliasLocalStorage(this.$route.params.aliasStore);
            }
            
            this.product=await ProductsHelper.findByStoreAliasIdLocalStorage(this.$route.params.aliasStore,this.$route.params.id);
            this.product.image_url='./assets/img/logo.svg';
            this.group=this.product.grupo;
            this.sizesPizza=await SizesPizzaHelper.findByStoreAlias(this.$route.params.aliasStore);
            this.bordersPizza=await BordersPizzaHelper.findByStoreAlias(this.$route.params.aliasStore);

            if(!until.isEmpty(this.$route.params.idsFlavors)){
                this.flavorsPizzaSelected=await FlavorsPizzaHelper.findByStoreAliasIdsLocalStorage(this.$route.params.aliasStore,this.$route.params.idsFlavors);
                this.flavorsPizzaParam=await FlavorsPizzaHelper.findByStoreAliasIdsLocalStorage(this.$route.params.aliasStore,this.$route.params.idsFlavors);
            }
            this.flavorsPizza=await FlavorsPizzaHelper.findByStoreAliasLocalStorage(this.$route.params.aliasStore);
            if(!until.isEmpty(this.$route.params.idSize)){
                this.sizePizzaSelected=await SizesPizzaHelper.findByStoreAliasIdLocalStorage(this.$route.params.aliasStore,this.$route.params.idSize);
                this.sizePizzaParam=await SizesPizzaHelper.findByStoreAliasIdLocalStorage(this.$route.params.aliasStore,this.$route.params.idSize);
                this.changeSize();
            }
            if(!until.isEmpty(this.$route.params.IdBorder)){
                this.borderPizzaSelected=await BordersPizzaHelper.findByStoreAliasIdLocalStorage(this.$route.params.aliasStore,this.$route.params.IdBorder);
                this.borderPizzaParam=await BordersPizzaHelper.findByStoreAliasIdLocalStorage(this.$route.params.aliasStore,this.$route.params.IdBorder);
            }

            this.totalPriceProduct();
        },
        mounted: function() {
            $('title').html(this.store.nome+' - item pedido');
        },
        methods:{
            formatMoney(val){
                return until.formatMoney(val);
            },
            isEmpty(val){
                return until.isEmpty(val);
            },
            
            minusAmountitem(){
                if(this.amount>1)
                    this.amount--;
                this.totalPriceProduct();
            },
            changeSize(){
                try{
                if (this.flavorsPizzaSelected.length<this.sizePizzaSelected.maximo_sabores){
                    var addFlabors=(this.sizePizzaSelected.maximo_sabores-this.flavorsPizzaSelected.length);
                    for(var countNewsFlabors=0;countNewsFlabors<addFlabors;countNewsFlabors++){
                        this.flavorsPizzaSelected.push(null); 
                    }
                }
                else{
                    var removeFlabors=(this.flavorsPizzaSelected.length-this.sizePizzaSelected.maximo_sabores);
                    for(var countNewsFlabors=0;countNewsFlabors<removeFlabors;countNewsFlabors++){
                        this.flavorsPizzaSelected.pop(); 
                    }
                }
                this.totalPriceProduct();
                }
                catch(e){
                    console.log(e);
                }
            },
            changeFlavor(index){
                try{
                    var acumulator=[...this.flavorsPizzaSelected];
                    acumulator.shift();
                    if(until.arrayAllElementsIsEmpy( acumulator )){
                        this.flavorsPizzaSelected.forEach((element,index) => {
                            if(index!=0){
                                this.flavorsPizzaSelected[index]=this.flavorsPizzaSelected[0];
                            }
                        });
                    }
                    this.totalPriceProduct();
                }
                catch(e){
                    //console.log(e);
                }
            },
            
            addAmountitem(){
                this.amount++;
                this.totalPriceProduct();
            },            
            goBack(){
                this.$router.go(-1);
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
            totalPriceProduct(){
                this.total=this.amount*this.calcPriceProduct();
                return this.total;
            },
           
            calcPriceProduct(){
                var price=0;
                if(this.product.pizza===true){
                    price=this.calcPriceSizePizza()+this.sumPriceFlavorsPizza()+this.calcPriceBorderPizza();
                }
                else{
                    price=this.product.preco;
                }
                return price;
            },
            sumPriceFlavorsPizza(){
                var sum=0;
                this.flavorsPizzaSelected.forEach(element => {
                    if((!until.isEmpty(element))&&(typeof  element.valor == 'number')){
                        sum=sum+element.valor;
                    }
                });
                return sum;
            },
            calcPriceSizePizza(){
                var price=0;
                if(!until.isEmpty(this.sizePizzaSelected)){
                    price=this.sizePizzaSelected.valor_tamanho;
                }
                return price;
            },
            calcPriceBorderPizza(){
                var price=0;
                if(!until.isEmpty(this.borderPizzaSelected)){
                    //console.log(this.borderPizzaSelected);
                    //price=this.sizePizzaSelected.valor_borda;
                }
                return price;
            },
            itemPizzaEquals(productA,sizeA,flavorsA,borderA,productB,sizeB,flavorsB,borderB){
                var result=(
                    (
                        this.getIdsFlavorsPizza(flavorsA).join(",")
                        ==
                        this.getIdsFlavorsPizza(flavorsB).join(",")
                    )
                    &&(productA.id==productB.id)
                    &&
                    (
                        (
                            (!until.isEmpty(borderA))
                            &&(!until.isEmpty(borderB))
                            &&(borderA.id==borderB.id)
                        )
                        ||
                        (
                            (until.isEmpty(borderA))
                            &&(until.isEmpty(borderA))
                        )
                    )
                    &&
                    (
                        (
                            (!until.isEmpty(sizeA))
                            &&(!until.isEmpty(sizeB))
                            &&(sizeA.id==sizeB.id)
                        )
                    )
                );
                return result;
            },
            itemIndexOf(order,product,size,flavors,border){
                var position=-1;
                order.forEach((element,index) => {
                    if
                    (
                        (
                            (element.product.pizza===true)
                            &&
                            this.itemPizzaEquals(
                                element.product,
                                element.size,
                                element.flavors,
                                element.border,
                                product,
                                size,
                                flavors,
                                border,
                            )
                        )
                        ||
                        (
                            (element.product.pizza==false)
                            &&
                            (element.product.id==product.id)
                        )
                    )
                    {
                        position= index;
                    }

                });
                return position;
            },
            validPizzaSize(product,size){
                if((product.pizza===true)&&(until.isEmpty(size))){
                     throw new Error("Selecione o tamanho da pizza");
                }
                return true;
            },
            validPizzaFlavors(product,flavors){
                if((product.pizza===true)&&(until.arrayAllElementsIsEmpy(flavors))){
                     throw new Error("Selecione o sabor da pizza");
                }
                return true;
            },
            validPizza(product,size,flavors){
                var result=
                (
                    (product.pizza===true)
                    &&
                    (
                        (this.validPizzaSize(product,size))
                        &&
                        (this.validPizzaFlavors(product,flavors))
                    )
                );
                return result;
            },
            loadOrder(){
                var order=[]
                if(!until.isEmpty(JSON.parse(localStorage.order))){
                    order=JSON.parse(localStorage.order);
                }
                return order;
            },
            saveOrder(order){
                localStorage.order=JSON.stringify(order);
            },
            async addItem(){
                try{
                    var order=this.loadOrder();
                    var amount=this.amount;
                    var product=this.product;
                    var flavors=this.flavorsPizzaSelected; 
                    var border=this.borderPizzaSelected;
                    var size=this.sizePizzaSelected;

                    var flavorsParam=this.flavorsPizzaParam; 
                    var borderParam=this.borderPizzaParam;
                    var sizeParam=this.sizePizzaParam;
                    
                    var total=this.calcPriceProduct();                   
                    if((product.pizza===false)||this.validPizza(product,size,flavors)){
                        var index=this.itemIndexOf(order,product,sizeParam,flavorsParam,borderParam);
                        if(index!=-1){
                            order[index].amount=amount;
                            order[index].total=total;
                            if(product.pizza===true){
                                order[index].flavors=flavors;
                                order[index].size=size;
                                order[index].border=border;
                            }
                        }
                        else{
                            order.push({"amount":amount,"product":product,"size":size,"flavors":flavors,"border":border,"total":total})
                        }
                        this.saveOrder(order);
                        this.$router.push({ name: 'itens-pedido-store',path:this.storePath+'/itens-pedido'});
                    }
                }
                catch(e){
                    console.log(e);
                    this.messageError=e.message;
                }
            },

        }
    
    }
    