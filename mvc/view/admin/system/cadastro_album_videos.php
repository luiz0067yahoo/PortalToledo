<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
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
    <div id="loader" v-if="loading">
        <div class="loader"></div>
    </div>

    <h1>CADASTRO DE ÁLBUNS DE VÍDEOS</h1>
    <br>

    <div class="row">
        <div class="col-sm-12">
            <form class="cadastro" id="cadastro_album_video" method="POST" enctype="multipart/form-data" onsubmit="return false;">

                <!-- Código (busca) -->
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-key"></i></span>
                    <input type="number" class="form-control" v-model="elementCurrent.id" placeholder="Código" disabled>
                    <button type="button" class="btn btn-primary" @click="findById(elementCurrent.id)" v-if="elementCurrent.id">
                        <i class="fa fa-search"></i>
                    </button>
                </div>

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
                    <span class="input-group-text"><i class="fa fa-video"></i></span>
                    <input type="text" class="form-control" v-model="elementCurrent.nome" placeholder="Nome do Álbum de Vídeos">
                </div>

                
                <!-- Ocultar -->
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-eye-slash"></i></span>
                    <div class="form-control">
                        <input type="checkbox" v-model="elementCurrent.ocultar" id="ocultar">
                        <label for="ocultar">Ocultar</label>
                    </div>
                </div>

                <!-- Botões (mesma lógica exata do código de referência) -->
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

            <!-- Tabela -->
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

         
                <!-- Pagination -->
                 <div class="paginator" v-if="elements.length > 0">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <li class="page-item" :class="{disabled: pagination.page <= 1}">
                                <a class="page-link h-100 justify-content-center align-items-center d-flex" href="#" @click.prevent="findAllElements(1)"><i class="fas fa-angle-double-left"></i></a>
                            </li>
                            <li class="page-item" :class="{disabled: pagination.page <= 1}">
                                <a class="page-link h-100 justify-content-center align-items-center d-flex" href="#" @click.prevent="findAllElements(pagination.page - 1)"><i class="fas fa-angle-left"></i></a>
                            </li>
                            <li class="page-item disabled">
                                <span class="page-link">
                                    Pag <input type="number" v-model.lazy="pagination.page" @change="findAllElements(pagination.page)" style="width: 50px; text-align: center;"> de {{ pagination.limitpage }}
                                </span>
                            </li>
                            <li class="page-item" :class="{disabled: pagination.page >= pagination.limitpage}">
                                <a class="page-link h-100 justify-content-center align-items-center d-flex" href="#" @click.prevent="findAllElements(pagination.page + 1)"><i class="fas fa-angle-right"></i></a>
                            </li>
                            <li class="page-item" :class="{disabled: pagination.page >= pagination.limitpage}">
                                <a class="page-link h-100 justify-content-center align-items-center d-flex" href="#" @click.prevent="findAllElements(pagination.limitpage)"><i class="fas fa-angle-double-right"></i></a>
                            </li>
                            <li class="page-item inverted">
                                <select class="form-control h-100" v-model="pagination.rowCount" @change="findAllElements(1)">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </li>
                        </ul>
                    </nav>
                 </div>
            </div>


            

            <!-- Modal Delete -->
            <div class="modal-overlay" v-if="showModal">
                <div class="modal-content-custom">
                    <h4 v-if="itemToDelete" class="mb-4">{{ itemToDelete.nome }}</h4>
                    <div class="modal-actions">
                        <button class="btn btn-primary" @click="confirmDelete">Sim</button>
                        <button class="btn btn-danger" @click="closeModal">Não</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="module">
import { createApp, ref, onMounted, computed } from 'vue';

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
            ocultar: false,
            video: ''  // nome do arquivo no servidor (se já existir)
        });

        const elements = ref([]);
        const pagination = ref({
            page: 1,
            rowCount: 10,
            total: 0,
            limitpage: 1
        });

        const parentMenus = ref([]);
        const serverUrl = '/server/albumVideos'; // Ajuste conforme seu endpoint real

        // Arquivo de vídeo
        const video_base64 = ref('');
        const videoNome = ref('');
        const files = { video: ref(null) };
        const fileInput = ref(null);

        const temArquivoParaMostrar = computed(() => !!videoNome.value || !!elementCurrent.value.video);
        const arquivoNome = computed(() => videoNome.value || elementCurrent.value.video || '');

        const generateToken = (length) => {
            const a = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890".split("");
            let b = [];
            for (let i = 0; i < length; i++) {
                b[i] = a[Math.floor(Math.random() * a.length)];
            }
            return b.join("");
        };

        const getToken = () => {
            const userData = localStorage.getItem('portalToledoData');
            if (userData) {
                try { return JSON.parse(userData).token; } catch (e) { return ''; }
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
            elementCurrent.value = { id: '', id_menu: '', nome: '', ocultar: false, video: '' };
            files.video.value = null;
            videoNome.value = '';
            video_base64.value = '';
            if (fileInput.value) fileInput.value.value = '';
            state.value = 'new';
        };

        const cancelAction = () => {
            clearMsg();
            state.value = 'default';
            prepareNew();
            elements.value = [];
            pagination.value = { page: 1, rowCount: 10, total: 0, limitpage: 1 };
            carregarParentMenus();
        };

        const editItem = (element) => {
            clearMsg();
            elementCurrent.value = { ...element };
            elementCurrent.value.ocultar = !!element.ocultar;

            files.video.value = null;
            videoNome.value = '';
            video_base64.value = '';
            if (fileInput.value) fileInput.value.value = '';

            state.value = 'edit';
        };

        const handleFile = (e) => {
            const file = e.target.files[0];
            files.video.value = file;

            if (file) {
                videoNome.value = file.name;
                const reader = new FileReader();
                reader.onload = (ev) => {
                    const result = ev.target.result;
                    video_base64.value = result.split(',')[1];
                };
                reader.readAsDataURL(file);
            } else {
                videoNome.value = '';
                video_base64.value = '';
            }
        };

        const removeFile = () => {
            elementCurrent.value.video = '';
            videoNome.value = '';
            video_base64.value = '';
            files.video.value = null;
            if (fileInput.value) fileInput.value.value = '';
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

            if (data.mensagem_erro || (data.message && data.error)) errorMsg.value = data.mensagem_erro || data.message;
            if (data.mensagem_sucesso || (data.message && !data.error)) successMsg.value = data.mensagem_sucesso || data.message;
            if (data.mensagem_informacao) infoMsg.value = data.mensagem_informacao;

            if (data.elements) elements.value = data.elements;

            if (data.recordsCount !== undefined) pagination.value.total = parseInt(data.recordsCount);
            else if (data.numero_registros !== undefined) pagination.value.total = parseInt(data.numero_registros);

            pagination.value.limitpage = pagination.value.total > 0
                ? Math.ceil(pagination.value.total / pagination.value.rowCount)
                : 1;
        };

        const findById = (id) => {
            if (!id) return;
            loading.value = true;
            axios.get(`${serverUrl}/${id}`, { headers: getAuthHeader() })
                .then(res => {
                    processResponse(res.data);
                    if (res.data.elements && res.data.elements.length > 0) {
                        elementCurrent.value = res.data.elements[0];
                        elementCurrent.value.ocultar = !!elementCurrent.value.ocultar;
                        state.value = 'findById';
                    }
                })
                .catch(err => errorMsg.value = "Erro: " + err)
                .finally(() => loading.value = false);
        };

        const findAllElements = (page = 1) => {
            pagination.value.page = page;
            if (pagination.value.page < 1) pagination.value.page = 1;
            if (pagination.value.limitpage > 0 && pagination.value.page > pagination.value.limitpage) pagination.value.page = pagination.value.limitpage;

            loading.value = true;
            const params = new URLSearchParams();
            params.append('page', pagination.value.page);
            params.append('row_count', pagination.value.rowCount);
            params.append('token', generateToken(256));
            if (elementCurrent.value.nome) params.append('nome', elementCurrent.value.nome);

            axios.get(`${serverUrl}?${params.toString()}`, { headers: getAuthHeader() })
                .then(res => processResponse(res.data))
                .catch(err => errorMsg.value = "Erro na conexão: " + err)
                .finally(() => loading.value = false);
        };

        const saveElement = () => {
            loading.value = true;
            const data = { ...elementCurrent.value };
            data.ocultar = data.ocultar ? 1 : 0;

            if (video_base64.value) {
                data.video_base64 = video_base64.value;
                data.video = {
                    namefile: files.video.value ? files.video.value.name : 'video.mp4',
                    data: video_base64.value
                };
            }

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

        return {
            loading, state, showModal, itemToDelete,
            errorMsg, successMsg, infoMsg,
            elementCurrent, elements, pagination, parentMenus,
            arquivoNome, temArquivoParaMostrar,
            fileInput, handleFile, removeFile,
            prepareNew, cancelAction, editItem,
            saveElement, findById, findAllElements, getMenuName,
            requestDelete, confirmDelete, closeModal
        };
    }
}).mount('#app');
</script>

<?php include($_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/foot.php'); ?>