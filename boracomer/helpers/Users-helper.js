import {conect} from '../untils/conect.js'
export const UsersHelper= {
    url:'users',
    async findByAlias(alias){
        let resultUser;
        try{
            resultUser= conect.get(this.url,{apelido:alias});
            return resultUser.data;
        }
        catch(e){throw Error(e.message);}
    }, 
    async createNewUser(usernamme,email,password){
        let resultUser;
        try{
            resultUser= await conect.post(this.url,{"usernamme":usernamme,"email":email,"password":password});
            return resultUser.data;
        }
        catch(e){throw Error(e.message);}
    },
    async login(usernamme,password){
        let resultUser;
        try{
            resultUser= await conect.post(this.url,{"identifier":usernamme,"password":password});
            localStorage.user=resultUser.data;
            return resultUser.data;
        }
        catch(e){throw Error("Login ou senha incorreto");}
    },
    async findUsers(usernamme,email){
        return await conect.get(this.url,{"usernamme":usernamme,"email":email});
    },
}