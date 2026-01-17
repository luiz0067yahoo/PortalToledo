<?php 
require($_SERVER['DOCUMENT_ROOT'].'/library/functions.php'); 
include($_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/top.php'); 
?>

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

  <h1>CADASTRO DE ANÚNCIOS</h1>
  <br>

  <div class="row">
    <div class="col-sm-12">
      <form class="cadastro" id="cadastro_anuncio" method="POST" enctype="multipart/form-data" onsubmit="return false;">

        <!-- Código -->
        <div class="input-group mb-3">
          <span class="input-group-text"><i class="fa fa-key"></i></span>
          <input type="number" class="form-control" v-model="elementCurrent.id" placeholder="Código" disabled>
          <button type="button" class="btn btn-primary" @click="findById(elementCurrent.id)" v-if="elementCurrent.id">
            <i class="fa fa-search"></i>
          </button>
        </div>

        <!-- Tipo de Anúncio -->
        <div class="input-group mb-3">
          <span class="input-group-text"><i class="fa fa-link"></i></span>
          <select v-model="elementCurrent.id_tipo_anuncio" class="form-control">
            <option value="">Selecione o Tipo</option>
            <option v-for="t in tiposAnuncios" :value="t.id" :key="t.id">{{ t.nome }}</option>
          </select>
        </div>

        <!-- Menu -->
        <div class="input-group mb-3">
          <span class="input-group-text"><i class="fa fa-list"></i></span>
          <select v-model="elementCurrent.id_menu" class="form-control">
            <option value="">Selecione o Menu</option>
            <option v-for="m in menus" :value="m.id" :key="m.id">{{ m.nome }}</option>
          </select>
        </div>

        <!-- Nome -->
        <div class="input-group mb-3">
          <span class="input-group-text"><i class="fa fa-bars"></i></span>
          <input type="text" class="form-control" v-model="elementCurrent.nome" placeholder="Nome">
        </div>

        <!-- Fotos -->
        <div class="mb-3" v-for="field in fileFields" :key="field.key">
            <!-- Miniatura da imagem atual ou preview da nova -->
            <div v-if="hasImage(field.key)" class="mb-3 position-relative" style="display: inline-block;">
                <img :src="getImageSrc(field.key)" style="height:100px; width:auto; border:1px solid #ccc; border-radius:4px;">
                <button type="button" @click="removeImage(field.key)" class="btn btn-danger btn-sm position-absolute top-0 end-0" style="transform: translate(50%, -50%); border-radius: 50%;">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <!-- Input file -->
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-image"></i></span>
                <input type="file" :ref="el => setFileInputRef(el, field.key)" class="form-control" @change="e => handleFile(e, field.key)" accept="image/*">
                <span class="input-group-text">{{ field.label }}</span>
            </div>
        </div>

        <!-- Textos -->
        <textarea class="form-control mb-3" rows="4" v-model="elementCurrent.introducao" placeholder="Introdução"></textarea>
        <textarea class="form-control mb-3" rows="4" v-model="elementCurrent.introducao2" placeholder="Introdução 2"></textarea>
        <textarea class="form-control mb-3" rows="6" v-model="elementCurrent.descricao" placeholder="Descrição"></textarea>

        <!-- Links sociais -->
        <div class="input-group mb-3" v-for="social in socials" :key="social">
          <span class="input-group-text"><i class="fa fa-globe"></i></span>
          <input type="text" class="form-control" v-model="elementCurrent[social]" :placeholder="social.charAt(0).toUpperCase() + social.slice(1).replace('_', ' ')">
        </div>

        <!-- Ocultar -->
        <div class="input-group mb-3">
          <span class="input-group-text"><i class="fa fa-eye-slash"></i></span>
          <div class="form-control">
            <input type="checkbox" v-model="elementCurrent.ocultar" id="ocultar">
            <label for="ocultar">Ocultar</label>
          </div>
        </div>

        <!-- Botões -->
        <div class="row">
          <div class="col-md-12 d-flex gap-2 flex-wrap">
            <button v-if="state=='default'" @click="prepareNew" type="button" class="btn btn-dark novo"><i class="fa fa-sticky-note"></i> Novo</button>
            <button v-if="state=='new'" @click="findAllElements(1); state='find'" type="button" class="btn btn-primary buscar"><i class="fa fa-search"></i> Buscar</button>
            <button v-if="state=='default'||state=='new'||state=='edit'||state=='find'" @click="saveElement" class="btn btn-success salvar"><i class="fas fa-save"></i> Salvar</button>
            <button v-if="state=='findById'" @click="state='edit'" type="button" class="btn btn-primary editar"><i class="fa fa-edit"></i> Editar</button>
            <button v-if="state=='edit'" @click="requestDelete(elementCurrent)" type="button" class="btn btn-danger excluir"><i class="fa fa-times"></i> Excluir</button>
            <button v-if="state=='new'||state=='edit'||state=='find'" @click="cancelAction" type="button" class="btn btn-danger cancelar"><i class="fa fa-ban"></i> Cancelar</button>
            <a target="_blank" :href="'/admin/anunciosFotos/' + elementCurrent.id"><button v-if="state=='edit'" type="button" class="btn btn-primary "><i class="fa fa-times"></i> Cadastrar Fotos</button></a>
            <a target="_blank" :href="'/admin/anunciosAnexos/' + elementCurrent.id"><button v-if="state=='edit'" type="button" class="btn btn-primary "><i class="fa fa-file"></i> Cadastrar Anexos</button></a>
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
            <th>ID</th>
            <th>Tipo</th>
            <th>Menu</th>
            <th>Nome</th>
            <th>Foto</th>
            <th>Ação</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="element in elements" :key="element.id">
            <td>{{ element.id }}</td>
            <td>{{ getTipoName(element.id_tipo_anuncio) }}</td>
            <td>{{ getMenuName(element.id_menu) }}</td>
            <td>{{ element.nome }}</td>
            <td>
              <img v-if="element.foto" :src="'/uploads/anuncio/original/' + element.foto" style="height:40px;width:auto;cursor:pointer;" @click="openImageModal('/uploads/anuncio/1024x768/' + element.foto)">
            </td>
            <td>
              <button @click="editItem(element)" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
              <button @click="requestDelete(element)" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Paginação -->
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
              <span class="page-link"> Pag <input type="number" v-model.lazy="pagination.page" @change="findAllElements(pagination.page)" style="width: 50px; text-align: center;"> de {{ pagination.limitpage }} </span>
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

  <!-- Modal Image Zoom -->
  <div v-if="modalImage" class="modal-overlay" @click="closeImageModal" style="z-index: 10000;">
    <div class="modal-content-custom" style="background:transparent;box-shadow:none;">
      <img :src="modalImage" style="max-width: 90vw; max-height: 90vh; border: 2px solid white; box-shadow: 0 0 15px rgba(0,0,0,0.5);">
    </div>
  </div>
</div>
<script type="importmap">
  {
    "imports": {
      "vue": "https://unpkg.com/vue@3/dist/vue.esm-browser.js",
      "@/": "/mvc/view/admin/js/"
    }
  }
</script>

<!-- Axios para consistência com o exemplo de Menus -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.min.js"></script>


<script type="module">
  import { createApp, ref, onMounted } from 'vue';
  import { fileToBase64 } from '/assets/js/utils/base64.js';

  createApp({
    setup() {
      // Estado geral
      const loading = ref(false);
      const state = ref('default');
      const showModal = ref(false);
      const itemToDelete = ref(null);
      const modalImage = ref(null);

      // Mensagens
      const errorMsg = ref("");
      const successMsg = ref("");
      const infoMsg = ref("");

      // Dados
      const elementCurrent = ref({
        id: '', nome: '', id_tipo_anuncio: '', id_menu: '', introducao: '', introducao2: '', descricao: '',
        ocultar: false, facebook: '', youtube: '', instagram: '', whatsapp: '', endereco: '', telefone: '',
        e_mail: '', website: '', url: ''
      });

      const elements = ref([]);
      const tiposAnuncios = ref([]);
      const menus = ref([]);

      const pagination = ref({ page: 1, rowCount: 10, total: 0, limitpage: 0 });

      const serverUrl = '/server/anuncios';

      // Arquivos múltiplos
      const files = {
        foto: ref(null),
        foto_mobile: ref(null),
        fotoexpandida: ref(null),
        foto_mobile_expandida: ref(null)
      };
      
      // Store for base64 strings and previews
      const previews = ref({});
      const filesBase64 = ref({});
      const fileInputs = ref({});

      const fileFields = [
        { key: 'foto', label: 'Foto' },
        { key: 'foto_mobile', label: 'Foto Mobile' },
        { key: 'fotoexpandida', label: 'Foto Expandida' },
        { key: 'foto_mobile_expandida', label: 'Foto Mobile Expandida' }
      ];

      const socials = ['facebook','youtube','instagram','whatsapp','endereco','telefone','e_mail','website','url'];

      // Auth helpers (iguais ao exemplo de menus)
      const generateToken = (length) => {
        const a = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890".split("");
        let b = [];
        for (let i = 0; i < length; i++) {
          const j = Math.floor(Math.random() * a.length);
          b[i] = a[j];
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
        errorMsg.value = ""; successMsg.value = ""; infoMsg.value = "";
      };

      const prepareNew = () => {
        clearMsg();
        elementCurrent.value = {
          id: '', nome: '', id_tipo_anuncio: '', id_menu: '', introducao: '', introducao2: '', descricao: '',
          ocultar: false, facebook: '', youtube: '', instagram: '', whatsapp: '', endereco: '', telefone: '',
          e_mail: '', website: '', url: ''
        };
        Object.keys(files).forEach(k => {
            files[k].value = null;
            previews.value[k] = '';
            filesBase64.value[k] = '';
            if (fileInputs.value[k]) fileInputs.value[k].value = '';
        });
        state.value = 'new';
      };

      const cancelAction = () => {
        clearMsg();
        state.value = 'default';
        prepareNew();
        elements.value = [];
        pagination.value = { page: 1, rowCount: 10, total: 0, limitpage: 0 };
        carregarTipos();
        carregarMenus();
      };

      const editItem = (element) => {
        clearMsg();
        elementCurrent.value = { ...element };
        elementCurrent.value.ocultar = (element.ocultar == 1 || element.ocultar == true);
        
        // Reset previews and inputs
        Object.keys(files).forEach(k => {
            files[k].value = null;
            previews.value[k] = '';
            filesBase64.value[k] = '';
            if (fileInputs.value[k]) fileInputs.value[k].value = '';
        });

        state.value = 'edit';
      };

      const handleFile = (e, key) => {
        const file = e.target.files[0];
        files[key].value = file;
        
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                const result = event.target.result;
                previews.value[key] = result;
                filesBase64.value[key] = result.split(',')[1];
            };
            reader.readAsDataURL(file);
        } else {
            previews.value[key] = '';
            filesBase64.value[key] = '';
        }
      };
      
      const removeImage = (key) => {
          // Clear server data for this field
          elementCurrent.value[key] = '';
          // Clear preview and base64
          previews.value[key] = '';
          filesBase64.value[key] = '';
          // Clear file object
          files[key].value = null;
          // Clear input
          if (fileInputs.value[key]) {
              fileInputs.value[key].value = '';
          }
      };
      
      // Helper to check if we have an image to show (preview or existing)
      const hasImage = (key) => {
          return !!(previews.value[key] || elementCurrent.value[key]);
      };
      
      // Helper to get the image source (preview or existing)
      const getImageSrc = (key) => {
          if (previews.value[key]) return previews.value[key];
          if (elementCurrent.value[key]) return `/uploads/anuncio/original/${elementCurrent.value[key]}`;
          return '';
      };
      
      // Function to bind refs inside v-for
      const setFileInputRef = (el, key) => {
          if (el) {
              fileInputs.value[key] = el;
          }
      };

      const carregarTipos = async () => {
        try {
          const response = await axios.get('/server/tiposAnuncios', { headers: getAuthHeader() });
          tiposAnuncios.value = response.data.elements || [];
        } catch (e) { console.error(e); }
      };

      const carregarMenus = async () => {
        try {
          const response = await axios.get('/server/menus', { headers: getAuthHeader() });
          menus.value = response.data.elements || [];
        } catch (e) { console.error(e); }
      };

      const getTipoName = (id) => {
        const t = tiposAnuncios.value.find(x => x.id == id);
        return t ? t.nome : id;
      };

      const getMenuName = (id) => {
        const m = menus.value.find(x => x.id == id);
        return m ? m.nome : id;
      };

      const processResponse = (data) => {
        clearMsg();
        if (typeof data === 'string') {
          try { data = JSON.parse(data); } catch (e) { return; }
        }
        if (data.mensagem_erro) errorMsg.value = data.mensagem_erro;
        else if (data.message && data.error) errorMsg.value = data.message;

        if (data.mensagem_sucesso) successMsg.value = data.mensagem_sucesso;
        else if (data.message && !data.error) successMsg.value = data.message;
        else if (data.mensagem_informacao) infoMsg.value = data.mensagem_informacao;

        if (data.elements) elements.value = data.elements;

        if (data.recordsCount !== undefined) pagination.value.total = parseInt(data.recordsCount);
        else if (data.numero_registros !== undefined) pagination.value.total = parseInt(data.numero_registros);

        if (pagination.value.total > 0) {
          pagination.value.limitpage = Math.ceil(pagination.value.total / pagination.value.rowCount);
          if (pagination.value.limitpage < 1) pagination.value.limitpage = 1;
        } else {
          pagination.value.limitpage = 1;
        }
      };

      const findById = (id) => {
        if (!id) return;
        loading.value = true;
        axios.get(`${serverUrl}/${id}`, { headers: getAuthHeader() })
          .then(response => {
            const data = response.data;
            processResponse(data);
            if (data.elements && data.elements.length > 0) {
              elementCurrent.value = { ...data.elements[0] };
              elementCurrent.value.ocultar = (elementCurrent.value.ocultar == 1 || elementCurrent.value.ocultar == true);
              state.value = 'findById';
            }
          })
          .catch(err => { errorMsg.value = "Erro: " + err; })
          .finally(() => loading.value = false);
      };

      const findAllElements = (page) => {
        if (page) pagination.value.page = page;
        if (pagination.value.page < 1) pagination.value.page = 1;
        if (pagination.value.limitpage > 0 && pagination.value.page > pagination.value.limitpage) pagination.value.page = pagination.value.limitpage;

        loading.value = true;
        let params = new URLSearchParams();
        params.append('page', pagination.value.page);
        params.append('row_count', pagination.value.rowCount);
        params.append('token', generateToken(256));
        if (elementCurrent.value.nome) params.append('nome', elementCurrent.value.nome);

        axios.get(`${serverUrl}?${params.toString()}`, { headers: getAuthHeader() })
          .then(response => processResponse(response.data))
          .catch(err => { errorMsg.value = "Erro na conexão: " + err; })
          .finally(() => loading.value = false);
      };

      const saveElement = async () => {
        loading.value = true;
        const data = { ...elementCurrent.value };

        // Add base64 fields if present
        for (const key in files) {
            if (filesBase64.value[key]) {
                data[key] = {
                    namefile: files[key].value ? files[key].value.name : 'image.jpg',
                    data: filesBase64.value[key]
                };
            } else if (!files[key].value && !elementCurrent.value[key]) {
                // Explicitly cleared image
                data[key] = ''; 
            }
        }

        data.ocultar = (data.ocultar === true || data.ocultar == 1);

        let url = serverUrl;
        let method = data.id ? 'put' : 'post';
        if (data.id) url = `${serverUrl}/${data.id}`;

        axios[method](url, data, { headers: getAuthHeaderJSON() })
          .then(response => {
            processResponse(response.data);
            if (successMsg.value) {
              state.value = 'default';
              prepareNew();
              findAllElements(pagination.value.page);
            }
          })
          .catch(err => { errorMsg.value = "Erro desconhecido: " + err; })
          .finally(() => loading.value = false);
      };

      const deleteElement = (id) => {
        loading.value = true;
        axios.delete(`${serverUrl}/${id}`, { headers: getAuthHeader() })
          .then(response => {
            processResponse(response.data);
            if (successMsg.value || !errorMsg.value) {
              prepareNew();
              state.value = 'default';
              findAllElements(pagination.value.page);
            }
          })
          .catch(err => { errorMsg.value = "Erro ao excluir: " + err; })
          .finally(() => loading.value = false);
      };

      const requestDelete = (element) => {
        itemToDelete.value = element;
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

      const openImageModal = (url) => { modalImage.value = url; };
      const closeImageModal = () => { modalImage.value = null; };

      onMounted(() => {
        carregarTipos();
        carregarMenus();
        findAllElements(1);
      });

      return {
        loading, state, showModal, itemToDelete, modalImage,
        errorMsg, successMsg, infoMsg,
        elementCurrent, elements, tiposAnuncios, menus,
        pagination, fileFields, socials,
        prepareNew, cancelAction, editItem, handleFile,
        saveElement, findById, findAllElements,
        requestDelete, confirmDelete, closeModal,
        openImageModal, closeImageModal,
        getTipoName, getMenuName,
        // New helpers
        removeImage, hasImage, getImageSrc, setFileInputRef
      };
    }
  }).mount('#app');
</script>

<?php include($_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/foot.php'); ?>