import {LoginComponent} from '../components/Login-component.js'
import {LoginUserErrorComponent} from '../components/Login-user-error-component.js'
import {CreateUserComponent} from '../components/Create-user-component.js'
import {CreateUserSuccessComponent} from '../components/Create-user-success-component.js'
import {CreateAddressComponent} from '../components/Create-address-component.js'
import {HomeComponent} from '../components/Home-component.js'
import {PanelComponent} from '../components/Panel-component.js'
import {ProductsComponent} from '../components/Products-component.js'
import {SearchProductsComponent} from '../components/Search-products-component.js'
import {SearchStoresComponent} from '../components/Search-stores-component.js'
import {OrderItemComponent} from '../components/Order-item-component.js'
import {OrderItensComponent} from '../components/Order-itens-component.js'

$('body').append(jQuery('<script>',{id:'login-template',type:'text/x-template',src:'/templates/login.html',}));
$('body').append(jQuery('<script>',{id:'login-user-error-template',type:'text/x-template',src:'/templates/login-user-error.html',}));
$('body').append(jQuery('<script>',{id:'create-user-template',type:'text/x-template',src:'/templates/create-user.html',}));
$('body').append(jQuery('<script>',{id:'create-user-success-template',type:'text/x-template',src:'/templates/create-user-success.html',}));
$('body').append(jQuery('<script>',{id:'create-address-template',type:'text/x-template',src:'/templates/create-address.html',}));
$('body').append(jQuery('<script>',{id:'home-template',type:'text/x-template',src:'/templates/home.html',}));
$('body').append(jQuery('<script>',{id:'panel-template',type:'text/x-template',src:'/templates/panel.html',}));
$('body').append(jQuery('<script>',{id:'products-template',type:'text/x-template',src:'/templates/products.html',}));
$('body').append(jQuery('<script>',{id:'search-products-template',type:'text/x-template',src:'/templates/search-products.html',}));
$('body').append(jQuery('<script>',{id:'search-stores-template',type:'text/x-template',src:'/templates/search-stores.html',}));
$('body').append(jQuery('<script>',{id:'order-item-template',type:'text/x-template',src:'/templates/order-item.html',}));
$('body').append(jQuery('<script>',{id:'order-itens-template',type:'text/x-template',src:'/templates/order-itens.html',}));




const routes = [

    
    {name: 'home',          path: '/' ,             component:HomeComponent},
    {name: 'logout',        path: '/logout',        HomeComponent},
    {name: 'login',         path: '/login',         component:LoginComponent},
    {name: 'login-error',   path: '/login-error',   component:LoginUserErrorComponent, props: true },

    {name: 'create-user',       	path: '/create-user',           component:CreateUserComponent},
    {name: 'create-user-success',   path: '/create-user-success',   component:CreateUserSuccessComponent},
    {name: 'create-address',    	path: '/create-address',        component:CreateAddressComponent},
   
    {name: 'buscar-loja',       path: '/buscar-loja',   component:SearchStoresComponent},
    {name: 'item-pedido',       path: '/item-pedido',   component:OrderItemComponent,},
    {name: 'itens-pedido',      path: '/itens-pedido',  component:OrderItensComponent,},

    {name: 'home-store',            path: '/empresa/:aliasStore/' , component:HomeComponent},
    {name: 'logout-store',          path: '/empresa/:aliasStore/logout' , HomeComponent},
    {name: 'login-store',           path: '/empresa/:aliasStore/login' , component:LoginComponent},
    {name: 'login-error-store',     path: '/empresa/:aliasStore/login-error' , component:LoginUserErrorComponent},
   
    {name: 'create-user-store',             path: '/empresa/:aliasStore/create-user' , component:CreateUserComponent},
    {name: 'create-user-success-store',     path: '/empresa/:aliasStore/create-user-success' , component:CreateUserSuccessComponent},
    {name: 'create-address-store',          path: '/empresa/:aliasStore/create-address' , component:CreateAddressComponent},

    {name: 'panel-store',            	path: '/empresa/:aliasStore/panel' , component:PanelComponent},
    {name: 'produtos-store-by-group',   path: '/empresa/:aliasStore/produtos/:groupName' , component:ProductsComponent},
    {name: 'produtos-store',         	path: '/empresa/:aliasStore/produtos' , component:ProductsComponent},
    {name: 'buscar-produtos-store',  	path: '/empresa/:aliasStore/buscar-produtos' , component:SearchProductsComponent},
   
    {name: 'buscar-loja-store',      path: '/empresa/:aliasStore/buscar-loja' , component:SearchStoresComponent},
    {name: 'item-pedido-store',      path: '/empresa/:aliasStore/item-pedido/:id' , component:OrderItemComponent,},
    {name: 'item-pizza-pedido-store',path: '/empresa/:aliasStore/item-pedido/:id/tamanho/:idSize/sabores/:idsFlavors/borda/:IdBorder' , component:OrderItemComponent,},
    {name: 'item-pizza-pedido-less-border-store',path: '/empresa/:aliasStore/item-pedido/:id/tamanho/:idSize/sabores/:idsFlavors' , component:OrderItemComponent,},
    {name: 'itens-pedido-store',     path: '/empresa/:aliasStore/itens-pedido' , component:OrderItensComponent,},
]
export const router = VueRouter.createRouter({
    history: VueRouter.createWebHashHistory(),
    routes,
});
