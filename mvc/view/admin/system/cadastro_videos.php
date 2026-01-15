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

    <h1>CADASTRO DE VÍDEOS</h1>
    <br>

    <div class="row">
        <div class="col-sm-12">
            <form class="cadastro" id="cadastro_video" method="POST" onsubmit="return false;">

                <!-- Código (desabilitado, só para busca) -->
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-key"></i></span>
                    <input type="number" class="form-control" v-model="elementCurrent.id" placeholder="Código" disabled>
                    <button type="button" class="btn btn-primary" @click="findById(elementCurrent.id)" v-if="elementCurrent.id">
                        <i class="fa fa-search"></i>
                    </button>
                </div>

                <!-- Álbum Pai -->
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-link"></i></span>
                    <select v-model="elementCurrent.id_album" class="form-control">
                        <option value="">Selecione um Álbum</option>
                        <option v-for="album in albumList" :value="album.id" :key="album.id">{{ album.nome }}</option>
                    </select>
                </div>

                <!-- Nome do vídeo -->
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-bars"></i></span>
                    <input type="text" class="form-control" v-model="elementCurrent.nome" placeholder="Nome do vídeo">
                </div>

                <!-- YouTube Link -->
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa-brands fa-youtube"></i></span>
                    <input type="url" class="form-control" v-model.trim="elementCurrent.video"
                           placeholder="Cole o link completo do YouTube (ex: https://www.youtube.com/watch?v=... ou https://www.youtube.com/shorts/...)"
                           @input="validateYouTubeUrl">
                </div>

                <!-- Preview do thumbnail -->
                <div class="mb-4 text-center" v-if="currentVideoThumbnail">
                    <div class="position-relative d-inline-block">
                        <img :src="currentVideoThumbnail" alt="Pré-visualização do YouTube"
                             style="max-height:160px; border-radius:8px; box-shadow:0 2px 12px rgba(0,0,0,0.2);">
                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 translate-middle rounded-circle"
                                @click="elementCurrent.video = ''; youtubeUrlInvalid = false">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <p class="form-text text-muted mt-2">Pré-visualização automática do thumbnail do YouTube (inclui Shorts)</p>
                </div>

                <!-- Mensagem de URL inválida -->
                <div class="alert alert-warning mt-2" v-if="youtubeUrlInvalid && elementCurrent.video">
                    <small>URL do YouTube parece inválida. Verifique o formato (inclui Shorts).</small>
                </div>

                <!-- Ocultar -->
                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" id="ocultarCheck" v-model="elementCurrent.ocultar">
                    <label class="form-check-label" for="ocultarCheck">Ocultar vídeo</label>
                </div>

                <!-- Botões -->
                <div class="row">
                    <div class="col-md-12 d-flex gap-2 flex-wrap">
                        <button v-if="state==='default'" @click="prepareNew" type="button" class="btn btn-dark">
                            <i class="fa fa-sticky-note"></i> Novo
                        </button>
                        
                        <button v-if="state==='new'" @click="findAllElements(1); state='find'" type="button" class="btn btn-primary">
                            <i class="fa fa-search"></i> Buscar
                        </button>
                    
                        <button v-if="['default','new','edit','find'].includes(state)" @click="saveElement" class="btn btn-success">
                            <i class="fas fa-save"></i> Salvar
                        </button>
                    
                        <button v-if="state==='findById'" @click="state='edit'" type="button" class="btn btn-primary">
                            <i class="fa fa-edit"></i> Editar
                        </button>
                        
                        <button v-if="state==='edit'" @click="requestDelete(elementCurrent)" type="button" class="btn btn-danger">
                            <i class="fa fa-times"></i> Excluir
                        </button>
                        
                        <button v-if="['new','edit','find'].includes(state)" @click="cancelAction" type="button" class="btn btn-danger">
                            <i class="fa fa-ban"></i> Cancelar
                        </button>
                    </div>
                </div>
                
            </form>

            <!-- Mensagens -->
            <div class="alert alert-success mt-3" v-if="successMsg" v-html="successMsg"></div>
            <div class="alert alert-danger mt-3" v-if="errorMsg" v-html="errorMsg"></div>
            <div class="alert alert-info mt-3" v-if="infoMsg" v-html="infoMsg"></div>

            <!-- Tabela -->
            <table class="table table-striped mt-4" v-if="elements.length > 0">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Álbum</th>
                        <th>Preview</th>
                        <th>Nome</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="element in elements" :key="element.id">
                        <td>{{ element.id }}</td>
                        <td>{{ getAlbumName(element.id_album) }}</td>
                        <td>
                            <img v-if="element.video" :src="getThumbnail(element.video)"
                                 style="height:60px; width:auto; cursor:pointer; border-radius:4px;"
                                 @click="openImageModal(getThumbnail(element.video, true))">
                        </td>
                        <td>{{ element.nome }}</td>
                        <td>
                            <button @click="editItem(element)" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
                            <button @click="requestDelete(element)" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Modal Confirmação Exclusão -->
            <div class="modal-overlay" v-if="showModal">
                <div class="modal-content-custom">
                    <h4 v-if="itemToDelete" class="mb-4">{{ itemToDelete.nome }}</h4>
                    <div class="modal-actions">
                        <button class="btn btn-primary" @click="confirmDelete">Sim</button>
                        <button class="btn btn-danger" @click="closeModal">Não</button>
                    </div>
                </div>
            </div>

            <!-- Modal Zoom Imagem -->
            <div v-if="modalImage" class="modal-overlay" @click="closeImageModal" style="z-index: 10000;">
                <div class="modal-content-custom" style="background:transparent; box-shadow:none; border:none;">
                    <img :src="modalImage" style="max-width: 90vw; max-height: 90vh; border: 2px solid white; box-shadow: 0 0 15px rgba(0,0,0,0.5);">
                </div>
            </div>

        </div>
    </div>
</div>

<script type="module">
import { createApp, ref, onMounted, computed } from 'vue';

createApp({
    setup() {
        // Estados principais
        const loading = ref(false);
        const state = ref('default');
        const showModal = ref(false);
        const itemToDelete = ref(null);
        const modalImage = ref(null);

        // Mensagens
        const errorMsg = ref('');
        const successMsg = ref('');
        const infoMsg = ref('');

        // Dados
        const elementCurrent = ref({
            id: '',
            id_album: '',
            nome: '',
            ocultar: false,
            video: ''
        });

        const elements = ref([]);
        const albumList = ref([]);
        const pagination = ref({
            page: 1,
            rowCount: 10,
            total: 0,
            limitpage: 0
        });

        // Config
        const serverUrl = '/server/videos';

        // YouTube
        const youtubeUrlInvalid = ref(false);

        // ──────────────────────────────────────────────────────────────
        //  Funções auxiliares
        // ──────────────────────────────────────────────────────────────
        const generateToken = (length) => {
            const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890".split("");
            let result = [];
            for (let i = 0; i < length; i++) {
                const idx = Math.floor(Math.random() * chars.length);
                result.push(chars[idx]);
            }
            return result.join("");
        };

        const getToken = () => {
            const userData = localStorage.getItem('portalToledoData');
            if (!userData) return '';
            try {
                return JSON.parse(userData).token;
            } catch {
                return '';
            }
        };

        const getAuthHeader = () => ({ 'Authorization': `Bearer ${getToken()}` });
        const getAuthHeaderJSON = () => ({
            'Authorization': `Bearer ${getToken()}`,
            'Content-Type': 'application/json'
        });

        const clearMsg = () => {
            errorMsg.value = '';
            successMsg.value = '';
            infoMsg.value = '';
        };

        // ──────────────────────────────────────────────────────────────
        //  Carregamento de álbuns
        // ──────────────────────────────────────────────────────────────
        const carregarAlbuns = async () => {
            try {
                const response = await axios.get('/server/albumVideos', { headers: getAuthHeader() });
                if (response.data?.elements) {
                    albumList.value = response.data.elements;
                }
            } catch (err) {
                console.error('Erro ao carregar álbuns de vídeos:', err);
            }
        };

        const getAlbumName = (id) => {
            const album = albumList.value.find(a => a.id == id);
            return album ? album.nome : '(sem álbum)';
        };

        // ──────────────────────────────────────────────────────────────
        //  YouTube Helpers (corrigido para Shorts)
        // ──────────────────────────────────────────────────────────────
        const getYoutubeId = (url) => {
            if (!url) return null;
            url = url.trim();
            // Regex atualizada para suportar /shorts/, /watch?v=, youtu.be, embed, etc.
            const regex = /(?:youtube(?:-nocookie)?\.com\/(?:[^\/]+\/\S+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/i;
            const match = url.match(regex);
            return match ? match[1] : null;
        };

        const getThumbnail = (videoUrl, highRes = false) => {
            const id = getYoutubeId(videoUrl);
            if (!id) return '';
            const base = `https://img.youtube.com/vi/${id}/`;
            // Prioriza hqdefault para compatibilidade com Shorts (vertical/horizontal)
            // maxresdefault como fallback high-res (pode não existir em todos os Shorts)
            return highRes ? `${base}maxresdefault.jpg` : `${base}hqdefault.jpg`;
        };

        const currentVideoThumbnail = computed(() => {
            return elementCurrent.value.video ? getThumbnail(elementCurrent.value.video) : '';
        });

        const validateYouTubeUrl = () => {
            if (!elementCurrent.value.video?.trim()) {
                youtubeUrlInvalid.value = false;
                return;
            }
            youtubeUrlInvalid.value = !getYoutubeId(elementCurrent.value.video);
        };

        // ──────────────────────────────────────────────────────────────
        //  CRUD & Estados
        // ──────────────────────────────────────────────────────────────
        const prepareNew = () => {
            clearMsg();
            elementCurrent.value = {
                id: '',
                id_album: '',
                nome: '',
                ocultar: false,
                video: ''
            };
            youtubeUrlInvalid.value = false;
            state.value = 'new';
        };

        const cancelAction = () => {
            clearMsg();
            state.value = 'default';
            elementCurrent.value = { id: '', id_album: '', nome: '', ocultar: false, video: '' };
            youtubeUrlInvalid.value = false;
        };

        const editItem = (item) => {
            clearMsg();
            elementCurrent.value = { ...item };
            elementCurrent.value.ocultar = !!item.ocultar;
            validateYouTubeUrl();
            state.value = 'edit';
        };

        const processResponse = (data) => {
            clearMsg();

            if (data.mensagem_erro || (data.message && data.error)) {
                errorMsg.value = data.mensagem_erro || data.message;
            }
            if (data.mensagem_sucesso || (data.message && !data.error)) {
                successMsg.value = data.mensagem_sucesso || data.message;
            }
            if (data.mensagem_informacao) {
                infoMsg.value = data.mensagem_informacao;
            }

            if (data.elements) {
                elements.value = data.elements;
            }

            if (data.recordsCount || data.numero_registros) {
                pagination.value.total = parseInt(data.recordsCount || data.numero_registros, 10);
                pagination.value.limitpage = Math.ceil(pagination.value.total / pagination.value.rowCount) || 1;
            }
        };

        const findById = async (id) => {
            if (!id) return;
            loading.value = true;
            try {
                const { data } = await axios.get(`${serverUrl}/${id}`, { headers: getAuthHeader() });
                processResponse(data);
                if (data.elements?.length > 0) {
                    editItem(data.elements[0]);
                }
            } catch (err) {
                errorMsg.value = 'Erro ao buscar registro: ' + err.message;
            } finally {
                loading.value = false;
            }
        };

        const findAllElements = async (page = 1) => {
            pagination.value.page = Math.max(1, page);

            loading.value = true;
            try {
                const params = new URLSearchParams({
                    page: pagination.value.page,
                    row_count: pagination.value.rowCount,
                    token: generateToken(32)
                });
                if (elementCurrent.value.nome?.trim()) {
                    params.append('nome', elementCurrent.value.nome.trim());
                }

                const { data } = await axios.get(`${serverUrl}?${params}`, { headers: getAuthHeader() });
                processResponse(data);
            } catch (err) {
                errorMsg.value = 'Erro ao listar vídeos: ' + err.message;
            } finally {
                loading.value = false;
            }
        };

        const saveElement = async () => {
            if (youtubeUrlInvalid.value) {
                errorMsg.value = "Corrigir URL do YouTube antes de salvar.";
                return;
            }

            loading.value = true;
            const data = { ...elementCurrent.value };
            data.ocultar = data.ocultar ? 1 : 0;

            const isUpdate = !!data.id;
            const url = isUpdate ? `${serverUrl}/${data.id}` : serverUrl;
            const method = isUpdate ? 'put' : 'post';

            try {
                const response = await axios[method](url, data, { headers: getAuthHeaderJSON() });
                processResponse(response.data);

                if (successMsg.value) {
                    prepareNew();
                    state.value = 'default';
                    findAllElements(pagination.value.page);
                }
            } catch (err) {
                errorMsg.value = err.response?.data?.mensagem_erro || 'Erro ao salvar vídeo';
            } finally {
                loading.value = false;
            }
        };

        const deleteElement = async (id) => {
            loading.value = true;
            try {
                const response = await axios.delete(`${serverUrl}/${id}`, { headers: getAuthHeader() });
                processResponse(response.data);
                if (!errorMsg.value) {
                    prepareNew();
                    state.value = 'default';
                    findAllElements(pagination.value.page);
                }
            } catch (err) {
                errorMsg.value = 'Erro ao excluir: ' + (err.response?.data?.mensagem_erro || err.message);
            } finally {
                loading.value = false;
            }
        };

        const requestDelete = (item) => {
            itemToDelete.value = item;
            showModal.value = true;
        };

        const confirmDelete = () => {
            if (itemToDelete.value?.id) {
                deleteElement(itemToDelete.value.id);
            }
            closeModal();
        };

        const closeModal = () => {
            showModal.value = false;
            itemToDelete.value = null;
        };

        const openImageModal = (url) => { modalImage.value = url; };
        const closeImageModal = () => { modalImage.value = null; };

        // Inicialização
        onMounted(() => {
            carregarAlbuns();
            findAllElements(1);
        });

        return {
            loading,
            state,
            showModal,
            itemToDelete,
            modalImage,
            errorMsg,
            successMsg,
            infoMsg,
            elementCurrent,
            elements,
            albumList,
            pagination,
            youtubeUrlInvalid,
            currentVideoThumbnail,
            prepareNew,
            cancelAction,
            editItem,
            findById,
            findAllElements,
            saveElement,
            requestDelete,
            confirmDelete,
            closeModal,
            openImageModal,
            closeImageModal,
            validateYouTubeUrl,
            getThumbnail,
            getAlbumName
        };
    }
}).mount('#app');
</script>

<?php include($_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/foot.php'); ?>