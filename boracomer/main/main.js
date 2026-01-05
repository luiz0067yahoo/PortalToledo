import { router } from "../router/routes.js";
function isEmpty(val){
    return (val === undefined || val == null || val.length <= 0) ? true : false;
}
var app;
function startRouterVue(){
    var templates=$("script[type='text/x-template']");
    var countLoadTemplate=0;
    templates.each(function(){
        if($(this).attr("status")=="done") countLoadTemplate++;
    })
    if (countLoadTemplate==templates.length){
        app = Vue.createApp({ 
            data() {
                return {
                    pageContent:'',
                }
            },
            watch: {
            
            },
            mounted: function() {
                
            },
            methods:{
            
                
            }
        })
        
        localStorage.token=null;
        localStorage.user=null;
        localStorage.store=null;
        localStorage.stores=null;
        localStorage.groups=null;
        localStorage.products=null;
        localStorage.flavorsPizza=null;
        localStorage.sizesPizza=null;
        localStorage.bordersPizza=null;
        localStorage.order=null;

        app.use(router);
        app.mount('#divMain');  
    }
}
async function  loadTemplates(){
    var base_path=window.location.protocol+"//"+window.location.hostname+"/boracomer/";
    var templates=$("script[type='text/x-template']");
    templates.each(function(index){
        var template=$(this);
        var src=template.attr("src");
        if((src!=null)&&(src.lengh!=0)){
            template.load(base_path+src,function() {
                template.attr("status","done")
                startRouterVue();
            });
        }
    });
}
loadTemplates();