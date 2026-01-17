<?php
require ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
include($_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/top.php');
?>
<script type="importmap">
{
  "imports": {
    "vue": "https://unpkg.com/vue@3/dist/vue.esm-browser.js",
    "@/": "/mvc/view/admin/js/"
  }
}
</script>

<div id="app" class="container">
    <h1>CADASTRO DE USUÁRIOS</h1>
    <br>
    
    <div class="row">
        <div class="col-sm-12">
            <form @submit.prevent="enviarCadastro">
                <div class="input-group mb-3">
                     <span class="input-group-text"><i class="fa fa-key"></i></span>
                     <input type="number" class="form-control" v-model="form.id" placeholder="Código" disabled>
                     <button type="button" class="btn btn-primary" @click="buscarPorId" v-if="form.id"><i class="fa fa-search"></i></button>
                </div>
 
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-bars"></i></span>
                    <input type="text" class="form-control" v-model="form.nome" placeholder="Nome">
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                    <input type="email" class="form-control" v-model="form.e_mail" placeholder="E-mail">
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                    <input type="text" class="form-control" v-model="form.login" placeholder="Login">
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                    <input type="password" class="form-control" v-model="form.senha" placeholder="Senha">
                </div>

                <div class="btn-group">
                    <button type="button" class="btn btn-dark" @click="resetForm"><i class="fa fa-sticky-note"></i> Novo</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Salvar</button>
                    <button type="button" class="btn btn-primary" @click="buscarTodos"><i class="fa fa-search"></i> Buscar</button>
                    <button type="button" class="btn btn-danger" @click="excluir" v-if="form.id"><i class="fa fa-times"></i> Excluir</button>
                </div>
            </form>

            <div v-if="msg.text" :class="'alert alert-'+msg.type" class="mt-3">{{ msg.text }}</div>

            <table class="table table-striped mt-4" v-if="lista.length > 0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Login</th>
                        <th>E-mail</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in lista" :key="item.id">
                        <td>{{ item.id }}</td>
                        <td>{{ item.nome }}</td>
                        <td>{{ item.login }}</td>
                        <td>{{ item.e_mail }}</td>
                        <td>
                            <button @click="editar(item)" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></button>
                            <button @click="excluirItem(item)" class="btn btn-sm btn-danger"><i class="fa fa-times"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="module">
import { createApp, ref, onMounted } from 'vue';
import { postCadastro } from '/assets/js/services/api.js';

createApp({
    setup() {
        const token = localStorage.getItem('portalToledoData') ? JSON.parse(localStorage.getItem('portalToledoData')).token : '';
        const form = ref({
            id: '',
            nome: '',
            e_mail: '',
            login: '',
            senha: ''
        });
        const lista = ref([]);
        const msg = ref({ text: '', type: '' });
        const apiUrl = window.location.origin + window.location.pathname; 

        onMounted(() => {
            buscarTodos();
        });

        async function enviarCadastro() {
            const payload = { ...form.value };
            if(!payload.id) delete payload.id;
            
            // If updating, strictly we might not want to send empty password if not changed?
            // Existing controller likely handles password hashing if present.
            // If empty, logic depends on Controller. User implies standard Payload.
            
            try {
                const method = form.value.id ? 'PUT' : 'POST';
                const response = await fetch(apiUrl, {
                    method: method,
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                }).then(res => res.json());

                if (response.success || response.mensagem_sucesso || response.id) {
                    msg.value = { text: 'Salvo com sucesso!', type: 'success' };
                    resetForm();
                    buscarTodos();
                } else {
                     msg.value = { text: response.error || response.mensagem_erro || 'Erro ao salvar', type: 'danger' };
                }
            } catch (error) {
                msg.value = { text: 'Erro na requisição: ' + error.message, type: 'danger' };
            }
        }

        async function buscarTodos() {
             try {
                const response = await fetch(`${apiUrl}?all=true`, {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const data = await response.json();
                if(data.elements) lista.value = data.elements;
                else if(Array.isArray(data)) lista.value = data;
            } catch (e) {
                console.error(e);
            }
        }
        
        async function buscarPorId() {
            if(!form.value.id) return;
             try {
                const response = await fetch(`${apiUrl}?id=${form.value.id}`, {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const data = await response.json();
                const item = data.site && data.site[0] ? data.site[0] : (data.elements?.[0] || data);
                if(item) editar(item);
            } catch (e) {
                console.error(e);
            }
        }

        async function excluir() {
            if(!confirm("Deseja excluir?")) return;
            try {
                 const response = await fetch(apiUrl, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: form.value.id })
                }).then(res => res.json());
                
                if(response.success || !response.error) {
                    msg.value = { text: 'Excluído com sucesso', type: 'success' };
                    resetForm();
                    buscarTodos();
                }
            } catch(e) {
                 msg.value = { text: 'Erro ao excluir', type: 'danger' };
            }
        }

        async function excluirItem(item) {
            if(!confirm("Deseja excluir?")) return;
            try {
                 const response = await fetch(apiUrl, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: item.id })
                }).then(res => res.json());
                
                if(response.success || !response.error) {
                    msg.value = { text: 'Excluído com sucesso', type: 'success' };
                    if(form.value.id == item.id) resetForm();
                    buscarTodos();
                } else {
                     msg.value = { text: response.error || response.mensagem_erro || 'Erro ao excluir', type: 'danger' };
                }
            } catch(e) {
                 msg.value = { text: 'Erro ao excluir ' + e.message, type: 'danger' };
            }
        }

        function editar(item) {
            form.value = { ...item };
            // Clear password on edit so user doesn't accidentally re-save hash or see it?
            // Typically password field is empty on edit.
            form.value.senha = ''; 
            window.scrollTo(0,0);
        }

        function resetForm() {
            form.value = {
                id: '',
                nome: '',
                e_mail: '',
                login: '',
                senha: ''
            };
        }

        return {
            form,
            lista,
            msg,
            enviarCadastro,
            buscarTodos,
            buscarPorId,
            excluir,
            excluirItem,
            editar,
            resetForm
        };
    }
}).mount('#app');
</script>
<?php include($_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/foot.php');?>