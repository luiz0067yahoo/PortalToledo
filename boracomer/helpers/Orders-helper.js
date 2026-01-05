import {conect} from '../untils/conect.js'
export const OrdersHelper= {
    url:'pedido',
    
    async sendOrder(userId,order){
        try{
            let resultOrder= await conect.post(this.url,{"id_usuario":userId,"pedido":order});
            return resultOrder.data;
        }
        catch(e){throw Error("Erro ao enviar o pedido");}
    },
    async lastOrdes(userId){
        let resultOrder= await conect.post(this.url,{"id_usuario":userId});
        return resultOrder.data;
    },
}