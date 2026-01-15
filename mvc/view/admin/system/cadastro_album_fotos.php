<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
include($_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/top.php');
?>

<script type="importmap">
{
  "imports": {
    "vue": "https://unpkg.com/vue@3/dist/vue.esm-browser.js"
  }
}
</script>

<!-- Axios -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.min.js"></script>

<style>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}
.modal-content-custom {
    background: white;
    padding: 30px;
    border-radius: 8px;
    min-width: 300px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
}
.modal-actions {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 20px;
}
</style>

<div id="app" class="container">
    <h1>CADASTRO DE ÁLBUNS DE FOTOS</h1>
    <br>

    <!-- Loader -->
    <div id="loader" v-if="loading">
        <div class="loader"></div>
    </div>

    <form @submit.prevent>
        <!-- Menu Pai -->
        <div class="input-group mb-3">
            <span class="input-group-text"><i class="fa fa-link"></i></span>
            <select v-model="elementCurrent.id_menu" class="form-control">
                <option value="">Selecione um Menu Pai (Opcional)</option>
                <option v-for="menu in parentMenus" :value="menu.id" :key="menu.id">{{ menu.nome }}</option>
            </select>
        </div>

        <!-- Nome do Álbum -->
        <div class="input-group mb-3">
            <span class="input-group-text"><i class="fa fa-image"></i></span>
            <input type="text" class="form-control" v-model="elementCurrent.nome" placeholder="Nome do Álbum">
        </div>

        <!-- Ocultar -->
        <div class="input-group mb-3">
            <span class="input-group-text"><i class="fa fa-eye-slash"></i></span>
            <div class="form-control">
                <input type="checkbox" id="ocultar" v-model="elementCurrent.ocultar">
                <label for="ocultar"> Ocultar</label>
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="row">
            <div class="col-md-12 d-flex gap-2">
                <button v-if="state=='default'" @click="prepareNew" type="button" class="btn btn-dark novo">
                    <i class="fa fa-sticky-note"></i> Novo
                </button>

                <button v-if="state=='new'" @click="findAllElements(1); state='find'" type="button" class="btn btn-primary buscar">
                    <i class="fa fa-search"></i> Buscar
                </button>

                <button v-if="state=='default'||state=='new'||state=='edit'||state=='find'" @click="saveElement" class="btn btn-success salvar">
                    <i class="fas fa-save"></i> Salvar
                </button>

                <button v-if="state=='findById'" @click="state='edit'" type="button" class="btn btn-primary editar">
                    <i class="fa fa-edit"></i> Editar
                </button>

                <button v-if="state=='edit'" @click="requestDelete(elementCurrent)" type="button" class="btn btn-danger excluir">
                    <i class="fa fa-times"></i> Excluir
                </button>

                <button v-if="state=='new'||state=='edit'||state=='find'" @click="cancelAction" type="button" class="btn btn-danger cancelar">
                    <i class="fa fa-ban"></i> Cancelar
                </button>
            </div>
        </div>
    </form>

    <!-- Mensagens -->
    <div class="alert alert-success mt-3" v-if="successMsg" role="alert" v-html="successMsg"></div>
    <div class="alert alert-danger mt-3" v-if="errorMsg" role="alert" v-html="errorMsg"></div>
    <div class="alert alert-info mt-3" v-if="infoMsg" role="alert" v-html="infoMsg"></div>

    <!-- Tabela de Álbuns -->
    <table class="table table-striped mt-4" v-if="elements.length > 0">
        <thead>
            <tr>
                <th>Código</th>
                <th>Menu Pai</th>
                <th>Nome</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="element in elements" :key="element.id">
                <td>{{ element.id }}</td>
                <td>{{ getMenuName(element.id_menu) }}</td>
                <td>{{ element.nome }}</td>
                <td>
                    <button @click="editItem(element)" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
                    <button @click="requestDelete(element)" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button>
                </td>
            </tr>
        </tbody>
    </table>

    
            <div class="paginator" v-if="elements.length > 0">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">

                        <li class="page-item" :class="{ disabled: pagination.page === 1 }">
                            <a class="page-link h-100 justify-content-center align-items-center d-flex"
                            href="#"
                            @click.prevent="findAllElements(1)">
                                <i class="fas fa-angle-double-left"></i>
                            </a>
                        </li>

                        <li class="page-item" :class="{ disabled: pagination.page === 1 }">
                            <a class="page-link h-100 justify-content-center align-items-center d-flex"
                            href="#"
                            @click.prevent="findAllElements(pagination.page - 1)">
                                <i class="fas fa-angle-left"></i>
                            </a>
                        </li>

                        <li class="page-item disabled">
                            <span class="page-link">
                                Pag
                                <input type="number"
                                    :value="pagination.page"
                                    @change="onPageInput($event)"
                                    style="width:50px; text-align:center;">
                                de {{ pagination.limitpage }}
                            </span>
                        </li>

                        <li class="page-item" :class="{ disabled: pagination.page === pagination.limitpage }">
                            <a class="page-link h-100 justify-content-center align-items-center d-flex"
                            href="#"
                            @click.prevent="findAllElements(pagination.page + 1)">
                                <i class="fas fa-angle-right"></i>
                            </a>
                        </li>

                        <li class="page-item" :class="{ disabled: pagination.page === pagination.limitpage }">
                            <a class="page-link h-100 justify-content-center align-items-center d-flex"
                            href="#"
                            @click.prevent="findAllElements(pagination.limitpage)">
                                <i class="fas fa-angle-double-right"></i>
                            </a>
                        </li>

                        <li class="page-item inverted">
                            <select class="form-control h-100"
                                    v-model.number="pagination.rowCount"
                                    @change="findAllElements(1)">
                                <option :value="10">10</option>
                                <option :value="20">20</option>
                                <option :value="50">50</option>
                                <option :value="100">100</option>
                            </select>
                        </li>

                    </ul>
                </nav>
            </div>

            

    <!-- Modal de Confirmação de Exclusão -->
    <div class="modal-overlay" v-if="showModal">
        <div class="modal-content-custom">
            <h4 class="mb-4">Tem certeza que deseja excluir <strong>{{ itemToDelete?.nome }}</strong>?</h4>
            <div class="modal-actions">
                <button class="btn btn-primary" @click="confirmDelete">Sim</button>
                <button class="btn btn-danger" @click="closeModal">Não</button>
            </div>
        </div>
    </div>
</div>

<script type="module">
import { createApp, ref, onMounted } from 'vue';

createApp({
    setup() {
        const loading = ref(false);
        const state = ref('default');
        const showModal = ref(false);
        const itemToDelete = ref(null);

        const errorMsg = ref("");
        const successMsg = ref("");
        const infoMsg = ref("");

        const elementCurrent = ref({
            id: '',
            id_menu: '',
            nome: '',
            ocultar: false
        });

        const elements = ref([]);
        const pagination = ref({
            page: 1,
            rowCount: 10,
            total: 0,
            limitpage: 1
        });

        const parentMenus = ref([]);
        const serverUrl = '/server/albumFotos';

        const generateToken = (length) => {
            const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890".split("");
            let result = [];
            for (let i = 0; i < length; i++) {
                result.push(chars[Math.floor(Math.random() * chars.length)]);
            }
            return result.join("");
        };

        const getToken = () => {
            const userData = localStorage.getItem('portalToledoData');
            if (userData) {
                try {
                    return JSON.parse(userData).token;
                } catch (e) {
                    return '';
                }
            }
            return '';
        };

        const getAuthHeader = () => ({ 'Authorization': `Bearer ${getToken()}` });
        const getAuthHeaderJSON = () => ({ 'Authorization': `Bearer ${getToken()}`, 'Content-Type': 'application/json' });

        const clearMsg = () => {
            errorMsg.value = successMsg.value = infoMsg.value = "";
        };

        const prepareNew = () => {
            clearMsg();
            elementCurrent.value = { id: '', id_menu: '', nome: '', ocultar: false };
            state.value = 'new';
        };

        const cancelAction = () => {
            clearMsg();
            prepareNew();
            state.value = 'default';
            elements.value = [];
            pagination.value = { page: 1, rowCount: 10, total: 0, limitpage: 1 };
        };

        const editItem = (element) => {
            clearMsg();
            elementCurrent.value = { ...element };
            elementCurrent.value.ocultar = !!element.ocultar;
            state.value = 'edit';
        };

        const carregarParentMenus = async () => {
            try {
                const res = await axios.get('/server/site/menusHierarchy', { headers: getAuthHeader() });
                if (res.data.elements) parentMenus.value = res.data.elements;
            } catch (e) {
                console.error(e);
            }
        };

        const getMenuName = (id) => {
            const menu = parentMenus.value.find(m => m.id == id);
            return menu ? menu.nome : id || '-';
        };

        const processResponse = (data) => {
            clearMsg();

            if (typeof data === 'string') {
                try { data = JSON.parse(data); } catch (e) { return; }
            }

            if (data.mensagem_erro || (data.message && data.error))
                errorMsg.value = data.mensagem_erro || data.message;

            if (data.mensagem_sucesso || (data.message && !data.error))
                successMsg.value = data.mensagem_sucesso || data.message;

            if (data.mensagem_informacao)
                infoMsg.value = data.mensagem_informacao;

            if (data.elements)
                elements.value = data.elements;

            if (data.recordsCount !== undefined)
                pagination.value.total = parseInt(data.recordsCount);
            else if (data.numero_registros !== undefined)
                pagination.value.total = parseInt(data.numero_registros);

            pagination.value.rowCount = parseInt(pagination.value.rowCount) || 10;

            pagination.value.limitpage = pagination.value.total > 0
                ? Math.ceil(pagination.value.total / pagination.value.rowCount)
                : 1;

            if (pagination.value.page > pagination.value.limitpage) {
                pagination.value.page = pagination.value.limitpage;
            }
        };

        const findAllElements = (page = 1) => {
            page = parseInt(page);

            if (isNaN(page) || page < 1) page = 1;
            if (page > pagination.value.limitpage) {
                page = pagination.value.limitpage;
            }

            pagination.value.page = page;
            loading.value = true;

            const params = new URLSearchParams();
            params.append('page', page);
            params.append('row_count', pagination.value.rowCount);
            params.append('token', generateToken(256));

            if (elementCurrent.value.nome) {
                params.append('nome', elementCurrent.value.nome);
            }

            axios.get(`${serverUrl}?${params.toString()}`, {
                headers: getAuthHeader()
            })
            .then(res => processResponse(res.data))
            .catch(err => {
                errorMsg.value = 'Erro na conexão: ' + err;
            })
            .finally(() => {
                loading.value = false;
            });
        };

        const saveElement = () => {
            loading.value = true;
            const data = { ...elementCurrent.value };
            data.ocultar = data.ocultar ? 1 : 0;

            const url = data.id ? `${serverUrl}/${data.id}` : serverUrl;
            const method = data.id ? 'put' : 'post';

            axios[method](url, data, { headers: getAuthHeaderJSON() })
                .then(res => {
                    processResponse(res.data);
                    if (successMsg.value) {
                        prepareNew();
                        state.value = 'default';
                        findAllElements(pagination.value.page);
                    }
                })
                .catch(err => errorMsg.value = "Erro ao salvar: " + err)
                .finally(() => loading.value = false);
        };

        const deleteElement = (id) => {
            loading.value = true;
            axios.delete(`${serverUrl}/${id}`, { headers: getAuthHeader() })
                .then(res => {
                    processResponse(res.data);
                    if (successMsg.value || !errorMsg.value) {
                        prepareNew();
                        state.value = 'default';
                        findAllElements(pagination.value.page);
                    }
                })
                .catch(err => errorMsg.value = "Erro ao excluir: " + err)
                .finally(() => loading.value = false);
        };

        const requestDelete = (el) => {
            itemToDelete.value = el;
            showModal.value = true;
        };

        const confirmDelete = () => {
            if (itemToDelete.value) deleteElement(itemToDelete.value.id);
            closeModal();
        };

        const closeModal = () => {
            showModal.value = false;
            itemToDelete.value = null;
        };

        onMounted(() => {
            carregarParentMenus();
            findAllElements(1);
        });


        const onPageInput = (event) => {
            const page = parseInt(event.target.value);
            if (page >= 1 && page <= pagination.value.limitpage) {
                findAllElements(page);
            }
        };

        return {
            loading, state, showModal, itemToDelete,
            errorMsg, successMsg, infoMsg,
            elementCurrent, elements, pagination, parentMenus,
            prepareNew, cancelAction, editItem,
            saveElement, findAllElements, getMenuName,
            requestDelete, confirmDelete, closeModal,
            onPageInput
        };

    }
}).mount('#app');
</script>

<?php include($_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/foot.php'); ?>