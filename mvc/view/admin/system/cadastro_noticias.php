<?php
require ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
include($_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/top.php');
?>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill-table-better@1/dist/quill-better-table.css">
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
    }
</style>
<style>
   /* Tire ou ajuste muito esse valor negativo — causa problemas sérios */
    .ql-table-menus-container,
    .ql-table-properties-form {
        z-index: 2147483647 !important;
        background-color: #fff;
        margin-top: -300px;
    }

    /* Melhor controle de posicionamento do popover/menus */
    .ql-table-better-menu {
        z-index: 2147483647 !important;
    }
</style>
<!-- ... cabeçalho e estilos semelhantes ao do noticias ... -->

<div id="app" class="container">
    <div id="loader" v-if="loading">
        <div class="loader"></div>
    </div>
    
    <h1>CADASTRO DE NOTÍCIAS</h1>
    <br>
    
    <div class="row">
        <div class="col-sm-12">
            <form class="cadastro" id="cadastro_noticia" method="POST" enctype="multipart/form-data" @submit.prevent>
                
                <!-- Código -->
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-key"></i></span>
                    <input type="number" class="form-control" v-model="elementCurrent.id" placeholder="Código" disabled>
                    <button type="button" class="btn btn-primary" @click="findById(elementCurrent.id)" v-if="elementCurrent.id">
                        <i class="fa fa-search"></i>
                    </button>
                </div>

                <!-- Menu / Categoria -->
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-link"></i></span>
                    <select v-model="elementCurrent.id_menu" class="form-control" required>
                        <option value="">Selecione o Menu/Categoria</option>
                        <option v-for="menu in parentMenus" :value="menu.id" :key="menu.id">{{ menu.nome }}</option>
                    </select>
                </div>
                
                <!-- Título -->
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-heading"></i></span>
                    <input type="text" class="form-control" v-model="elementCurrent.titulo" placeholder="Título da notícia" required>
                </div>

                <!-- Subtítulo -->
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-align-left"></i></span>
                    <input type="text" class="form-control" v-model="elementCurrent.subtitulo" placeholder="Subtítulo (opcional)">
                </div>

                <!-- Foto principal / capa -->
                <div class="mb-3">
                    <div v-if="temImagemParaMostrar" class="mb-3 position-relative" style="display: inline-block;">
                        <img :src="imagemPreviewOuAtual" style="max-height:160px; width:auto; border:1px solid #ccc; border-radius:4px;">
                        <button type="button" @click="removeImage" class="btn btn-danger btn-sm position-absolute top-0 end-0" style="transform: translate(50%, -50%); border-radius: 50%;">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>

                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-image"></i></span>
                        <input type="file" ref="fileInput" class="form-control" accept="image/jpeg,image/png" @change="handleFile">
                    </div>
                    <small class="text-muted">Tamanhos sugeridos: 1200×630, 800×600, 1024×768</small>
                </div>
                
                <!-- initQuill aqui (você pode manter o container #editor) -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Conteúdo da notícia</label>
                </div>
                <div class="mb-3">
                    <div id="conteudo_noticia" name="conteudo_noticia"  class="form-control" style="height:400px;"></div>
                </div>
                    
                <!-- Fonte -->
                <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-newspaper"></i></span>
                        <input type="text" class="form-control" v-model="elementCurrent.fonte" placeholder="Fonte (opcional)">
                </div>
                
                <!-- Flags -->
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-eye-slash"></i></span>
                    <div class="form-control d-flex flex-wrap gap-4">
                        <div class="form-check">
                            <input type="checkbox" v-model="elementCurrent.slide_show" id="slide_show">
                            <label for="slide_show">Slide Show</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" v-model="elementCurrent.destaque" id="destaque">
                            <label for="destaque">Destaque</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" v-model="elementCurrent.ocultar" id="ocultar">
                            <label for="ocultar">Ocultar</label>
                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div class="row">
                    <div class="col-md-12 d-flex gap-2 flex-wrap">
                        <button v-if="state==='default'" @click="prepareNew" type="button" class="btn btn-dark novo">
                            <i class="fa fa-sticky-note"></i> Novo
                        </button>
                        
                        <button v-if="state==='new'" @click="findAllElements(1); state='find'" type="button" class="btn btn-primary buscar">
                            <i class="fa fa-search"></i> Buscar
                        </button>
                        
                        <button v-if="['default','new','edit','find'].includes(state)" @click="saveElement" class="btn btn-success salvar">
                            <i class="fas fa-save"></i> Salvar
                        </button>
                        
                        <button v-if="state==='findById'" @click="state='edit'" type="button" class="btn btn-primary editar">
                            <i class="fa fa-edit"></i> Editar
                        </button>

                        <button v-if="state==='edit'" @click="requestDelete(elementCurrent)" class="btn btn-danger excluir">
                            <i class="fa fa-times"></i> Excluir
                        </button>
                        
                        <button v-if="['new','edit','find'].includes(state)" @click="cancelAction" class="btn btn-danger cancelar">
                            <i class="fa fa-ban"></i> Cancelar
                        </button>



                        <a target="_blank" :href="'/admin/noticiasFotos/' + elementCurrent.id"><button v-if="state=='edit'" type="button" class="btn btn-primary "><i class="fa fa-times"></i> Cadastrar Fotos</button></a>
                        <a target="_blank" :href="'/admin/noticiasAnexos/' + elementCurrent.id"><button v-if="state=='edit'" type="button" class="btn btn-primary "><i class="fa fa-file"></i> Cadastrar Anexos</button></a>
                    </div>
                </div>
            </form>

            <div class="alert alert-success mt-3" v-if="successMsg" role="alert" v-html="successMsg"></div>
            <div class="alert alert-danger mt-3" v-if="errorMsg" role="alert" v-html="errorMsg"></div>
            <div class="alert alert-info mt-3" v-if="infoMsg" role="alert" v-html="infoMsg"></div>
            
            <table class="table table-striped mt-4" v-if="elements.length > 0">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Menu Pai</th>
                        <th>Titulo/Subtitulo</th>
                        <th>Imagem</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="element in elements" :key="element.id">
                        <td>{{ element.id }}</td>
                        <td>{{ getMenuName(element.id_menu) }}</td>
                        <td>
                            <b>{{ element.titulo }}</b>
                            <br>
                            {{ element.subtitulo }}
                        </td>
                        <td>
                             <img v-if="element.foto_principal" :src="`/uploads/noticias/original/${element.foto_principal}`" style="height:40px;width:auto;cursor:pointer;" @click="openImageModal(`/uploads/noticias/1024x768/${element.foto_principal}`)">
                        </td>
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
    </div>
    <!-- Modal Delete -->
    <div class="modal-overlay" v-if="showModal">
        <div class="modal-content-custom">
            <h4 v-if="itemToDelete" class="mb-4">{{ itemToDelete.titulo }}</h4>
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



    <!-- ==================== MODAL INSERIR TABELA ==================== -->
    <div class="modal-overlay" v-if="showModalTable">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
            <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Inserir Nova Tabela</h5>
                <button 
                type="button" 
                class="btn-close" 
                @click="closeModalTable()"
                aria-label="Fechar"
                ></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                <div class="col-6">
                    <label for="tableRowsInput" class="form-label">Linhas</label>
                    <input 
                    id="tableRowsInput"
                    type="number" 
                    v-model.number="tableRows" 
                    class="form-control text-center"
                    min="1"
                    step="1"
                    placeholder="ex: 4"
                    :class="{ 'is-invalid': tableRows < 1 && tableRows !== '' }"
                    required
                    >
                </div>

                <div class="col-6">
                    <label for="tableColumnsInput" class="form-label">Colunas</label>
                    <input 
                    id="tableColumnsInput"
                    type="number" 
                    v-model.number="tableColumns" 
                    class="form-control text-center"
                    min="1"
                    step="1"
                    placeholder="ex: 5"
                    :class="{ 'is-invalid': tableColumns < 1 && tableColumns !== '' }"
                    required
                    >
                </div>
                </div>
            </div>

            <div class="modal-footer bg-light">
                <button 
                type="button" 
                class="btn btn-outline-secondary px-4" 
                @click="closeModalTable()"
                >
                Cancelar
                </button>
                
                <button 
                type="button" 
                class="btn btn-primary px-5"
                :disabled="tableRows < 1 || tableColumns < 1"
                @click="insertTableQuill"
                >
                Inserir Tabela
                </button>
            </div>
            </div>
        </div>
    </div>

</div>

<script type="importmap">
{
  "imports": {
    "vue": "https://unpkg.com/vue@3/dist/vue.esm-browser.js"
  }
}
</script>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quill-table-better@1/dist/quill-table-better.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.27.2/axios.min.js"></script>
<script type="module">
    import { createApp, ref, onMounted, computed, watch } from 'vue';
    // import { fileToBase64 } from '/assets/js/utils/base64.js';  // se ainda usar

    let quillConteudoNoticia=null;

    createApp({
        setup() {
            // State

            // Variáveis reativas
            const showModalTable = ref(false);
            const tableRows = ref(3);       // valor inicial mais amigável
            const tableColumns = ref(4);

            // Data
            const loading = ref(false);
            const state = ref('default'); 
            const showModal = ref(false);
            const itemToDelete = ref(null);
            const modalImage = ref(null);
            
            // Data
            const errorMsg = ref("");
            const successMsg = ref("");
            const infoMsg = ref("");
            const elementCurrent = ref({
                id: '',
                id_menu: '',
                titulo: '',
                subtitulo: '',
                conteudo_noticia: '',
                fonte: '',
                slide_show: false,
                destaque: false,
                ocultar: false,
                foto_principal: '',           // ou foto_principal, mas mantendo compatibilidade com nome do noticias
            });
            

            const initQuill = () => {
                if (quillConteudoNoticia) return;

                // Registro do módulo (obrigatório antes de instanciar Quill)
                Quill.register({ 'modules/table-better': QuillTableBetter }, true);

                quillConteudoNoticia = new Quill('#conteudo_noticia', {
                    theme: 'snow',
                    modules: {
                        toolbar: {
                            container: [
                                ['bold', 'italic', 'underline', 'strike'],
                                ['blockquote', 'code-block'],
                                [{ 'header': [1, 2, 3, false] }],
                                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                [{ 'color': [] }, { 'background': [] }],
                                ['link', 'image'],
                                ['table-better'],           // ← Botão de inserir tabela aparece aqui!
                                ['clean']
                            ],
                            handlers: {
                                'image': imageHandler
                            }
                        },
                        table: false,                   // Desativa o table nativo do Quill
                        'table-better': {
                            language: 'pt_BR',          // tenta pt_BR; se não existir, cai em en_US
                            menus: ['column', 'row', 'merge', 'table', 'cell', 'wrap', 'copy', 'delete'],
                            toolbarTable: true,         // Ativa o botão de inserir tabela na toolbar
                            operationMenu: {
                                items: {
                                    unmergeCells: {
                                        text: 'Dividir células (Unmerge)'
                                    }
                                },
                                color: {
                                    colors: ['#fff', '#000', '#f00', '#0f0', '#00f'],
                                    text: 'Cor de fundo:'
                                }
                            }
                        },
                        keyboard: {
                            bindings: QuillTableBetter.keyboardBindings
                        }
                    },
                    placeholder: 'Escreva sua notícia aqui...'
                });

                console.log('Quill inicializado com tabelas avançadas (quill-table-better)');
            };

        // Near the top of setup()
            const editorInstance = ref(null);
            
            const insertTableQuill = () => {
                const rows = Number(tableRows.value);
                const cols = Number(tableColumns.value);

                if (!Number.isInteger(rows) || !Number.isInteger(cols) || rows < 1 || cols < 1) {
                    alert('Informe valores inteiros válidos maiores que zero.');
                    return;
                }

                if (!quillConteudoNoticia) {
                    console.error('Editor Quill não está inicializado');
                    return;
                }

                const tableModule = quillConteudoNoticia.getModule('table-better');
                
                if (!tableModule?.insertTable) {
                    alert('Módulo table-better não foi carregado corretamente.');
                    console.error('tableModule:', tableModule);
                    return;
                }

                try {
                    tableModule.insertTable(rows, cols);
                    console.log(`Tabela ${rows}×${cols} inserida com sucesso`);
                } catch (err) {
                    console.error('Erro ao inserir tabela:', err);
                    alert('Não foi possível inserir a tabela. Veja o console.');
                }

                // Fecha modal e limpa campos
                closeModalTable();
            };

            const openShowModalTable = () => {
                if (!quillConteudoNoticia) {
                    alert('Editor não está pronto ainda.');
                    return;
                }
                tableRows.value = 4;
                tableColumns.value = 5;
                // Abre o modal do Bootstrap
                showModalTable.value = true;
                console.log(tableRows.value, tableColumns.value);
            };

            const closeModalTable = () => {
                showModalTable.value = false;
                tableRows.value = 3;
                tableColumns.value = 4;
            };

            const cleanQuillConteudoNoticia = () => {
                quillConteudoNoticia.setText('');
            }

            const setQuillConteudoNoticia = (html='') => {
                try{
                    const delta = quillConteudoNoticia.clipboard.convert({ html: html });
                    //quillConteudoNoticia.setContents(delta, 'silent');
                    quillConteudoNoticia.setText(html);
                }
                catch(e){
                    console.log(e);
                }

            };

            const imageHandler = () => {
                const input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.click();

                input.onchange = async () => {
                    const file = input.files[0];
                    if (!file) return;

                    // Range atual (onde inserir a imagem)
                    const range = quillConteudoNoticia.getSelection(true) || { index: 0 };

                    // Placeholder enquanto carrega
                    quillConteudoNoticia.insertEmbed(range.index, 'image', 
                        'https://placehold.co/400x200?text=Carregando...');
                    quillConteudoNoticia.setSelection(range.index + 1);

                    try {
                        const formData = new FormData();
                        formData.append('image', file);  // ← nome que seu PHP espera

                        const response = await axios.post(
                            '/server/noticias/quillUpload', 
                            formData,
                            {
                                headers: {
                                    ...getAuthHeader(),           // seu token de autenticação
                                    'Content-Type': 'multipart/form-data',
                                    'Authorization': `Bearer ${getToken()}`
                                }
                            }
                        );

                        const data = response.data;

                        if (data.success && data.url) {
                            // Remove placeholder
                            quillConteudoNoticia.deleteText(range.index, 1);
                            // Insere a imagem real
                            quillConteudoNoticia.insertEmbed(range.index, 'image', data.url);
                            quillConteudoNoticia.setSelection(range.index + 1);
                        } else {
                            alert('Falha no upload: ' + (data.message || 'Erro desconhecido'));
                            quillConteudoNoticia.deleteText(range.index, 1);
                        }
                    } catch (error) {
                        console.error('Erro no upload da imagem:', error);
                        alert('Erro ao enviar imagem. Verifique sua conexão ou tente novamente.');
                        quillConteudoNoticia.deleteText(range.index, 1);
                    }
                };
            };

            const elements = ref([]);
            const pagination = ref({
                page: 1,
                rowCount: 10,
                total: 0,
                limitpage: 0
            });
            const parentMenus = ref([]);
            
            // Config
            const serverUrl = '/server/noticias';
        // File handling variables
            const files = {
                foto_principal: ref(null)
            };
            const foto_principal_base64 = ref('');
            const foto_principalPreview = ref('');

            // Computed properties for Image Preview


            const imagemPreviewOuAtual = computed(() => {
                if (foto_principalPreview.value) {
                    return foto_principalPreview.value;
                }
                if (elementCurrent.value.foto_principal) {
                    return `/uploads/noticias/original/${elementCurrent.value.foto_principal}`;
                }
                return '';
            });

            const temImagemParaMostrar = computed(() => {
                return !!imagemPreviewOuAtual.value;
            });

            // Refs
            const fileInput = ref(null);

            // Method to handle file selection
            function handleFile(e) {
                const file = e.target.files[0];
                files.foto_principal.value = file;

                if (file) {
                    // FileReader to generate preview and base64 immediately
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        const result = event.target.result;
                        // Set preview (DataURL)
                        foto_principalPreview.value = result;
                        
                        // Extract Base64 string (remove "data:image/png;base64,")
                        const base64String = result.split(',')[1];
                        foto_principal_base64.value = base64String;
                    };
                    reader.readAsDataURL(file);
                } else {
                    foto_principalPreview.value = '';
                    foto_principal_base64.value = '';
                }
            }

            // Method to remove image
            function removeImage() {
                // Clear current server image
                elementCurrent.value.foto_principal = '';
                
                // Clear preview
                foto_principalPreview.value = '';
                foto_principal_base64.value = '';
                
                // Clear file object
                files.foto_principal.value = null;

                // Clear input DOM element
                if (fileInput.value) {
                    fileInput.value.value = '';
                }
            }

            // Methods
            const generateToken = (length) => {
                var a = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890".split("");
                var b = [];
                for (var i = 0; i < length; i++) {
                    var j = (Math.random() * (a.length - 1)).toFixed(0);
                    b[i] = a[j];
                }
                return b.join("");
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

            const getAuthHeader = () => {
                return {
                    'Authorization': `Bearer ${getToken()}`
                };
            };
            
            const getAuthHeaderJSON = () => {
                return {
                    'Authorization': `Bearer ${getToken()}`,
                    'Content-Type': 'application/json'
                };
            };

            const clearMsg = () => {
                errorMsg.value = "";
                successMsg.value = "";
                infoMsg.value = "";
            };

            const prepareNew = () => {
                clearMsg();
                elementCurrent.value = { id: '', id_menu: '',foto_principal: '', titulo: '', subtitulo: '', conteudo_noticia: '', fonte: '', acesso: '', ocultar: false, slide_show: false, destaque: false };
                files.foto_principal.value = null;
                foto_principalPreview.value = '';
                foto_principal_base64.value = '';
                const fileInput = document.querySelector('input[type="file"]');
                if(fileInput) fileInput.value = '';
                state.value = 'new';
                cleanQuillConteudoNoticia();
            };

            const cancelAction = () => {
                clearMsg();
                // State
                loading.value = false;
                state.value = 'default'; 
                showModal.value = false;
                itemToDelete.value = null;
                modalImage.value = null;

                // Data
                errorMsg.value = "";
                successMsg.value = "";
                infoMsg.value = "";
                elementCurrent.value = { 
                    id: '',
                    id_menu: '',
                    foto_principal: '',
                    titulo: '',
                    subtitulo: '',
                    fonte: '',
                    slide_show: false,
                    destaque: false,
                    ocultar: false,
                };
                elements.value = [];
                pagination.value = {
                    page: 1,
                    rowCount: 10,
                    total: 0,
                    limitpage: 0
                };
                parentMenus.value = [];
                
                // File handling variables
                files.foto_principal.value = null;
                foto_principal_base64.value = '';
                foto_principalPreview.value = '';

                cleanQuillConteudoNoticia();
            };

            const editItem = (element) => {
                clearMsg();
                elementCurrent.value = { ...element };
                elementCurrent.value.ocultar = (element.ocultar == 1 || element.ocultar == true);
                elementCurrent.value.destaque    = (element.destaque == 1 || element.destaque == true);
                elementCurrent.value.slide_show    = (element.slide_show == 1 || element.slide_show == true);

                setTimeout(() => setQuillConteudoNoticia(element.conteudo_noticia), 200);
                // Reset new file selection on edit start
                files.foto_principal.value = null;
                foto_principalPreview.value = '';
                foto_principal_base64.value = '';
                const fileInput = document.querySelector('input[type="file"]');
                if(fileInput) fileInput.value = '';

                state.value = 'edit';
        
                cleanQuillConteudoNoticia();
            };

            const carregarParentMenus = async () => {
                    try {
                    const response = await axios.get(`/server/site/menusHierarchy`, { headers: getAuthHeader() });
                    const data = response.data;
                    if(data.elements) parentMenus.value = data.elements;
                } catch (e) {
                    console.error(e);
                }
            };
            
            const getMenuName = (id) => {
                const m = parentMenus.value.find(x => x.id == id);
                return m ? m.nome : id;
            };

            const processResponse = (data) => {
                clearMsg();
                if (typeof data === 'string') {
                    try {
                        data = JSON.parse(data);
                    } catch (e) {
                        return;
                    }
                }
                
                if (data.mensagem_erro) {
                    errorMsg.value = data.mensagem_erro;
                } else if (data.message && data.error) {
                    errorMsg.value = data.message;
                }
                
                if (data.mensagem_sucesso) {
                    successMsg.value = data.mensagem_sucesso;
                } else if (data.message && !data.error) {
                    successMsg.value = data.message;
                } else if (data.mensagem_informacao) {
                    infoMsg.value = data.mensagem_informacao;
                }

                if (data.elements) {
                    elements.value = data.elements;
                }

                if (data.recordsCount !== undefined) {
                    pagination.value.total = parseInt(data.recordsCount);
                } else if (data.numero_registros !== undefined) {
                    pagination.value.total = parseInt(data.numero_registros);
                }

                if (pagination.value.total > 0) {
                    pagination.value.limitpage = Math.ceil(pagination.value.total / pagination.value.rowCount);
                    if (pagination.value.limitpage < 1) pagination.value.limitpage = 1;
                } else {
                    pagination.value.limitpage = 1; 
                }
            };

            const findById = (id) => {
                if(!id) return;
                loading.value = true;
                axios.get(`${serverUrl}/${id}`, { headers: getAuthHeader() }).then(response => {
                    const data = response.data;
                    processResponse(data);
                    // Handle different response structures for single item if needed
                    if(data.site && data.site.length > 0) {
                        elementCurrent.value = data.site[0];
                        state.value = 'findById';
                        elementCurrent.value.destaque    = (elementCurrent.value.destaque == 1 || elementCurrent.value.destaque == true);
                        elementCurrent.value.slide_show    = (elementCurrent.value.slide_show == 1 || elementCurrent.value.slide_show == true);
                        elementCurrent.value.ocultar =     (elementCurrent.value.ocultar == 1 || elementCurrent.value.ocultar == true);
                    } else if (elements.value && elements.value.length > 0) {
                        elementCurrent.value = elements.value[0];
                        state.value = 'findById';
                        elementCurrent.value.destaque    = (elementCurrent.value.destaque == 1 || elementCurrent.value.destaque == true);
                        elementCurrent.value.slide_show    = (elementCurrent.value.slide_show == 1 || elementCurrent.value.slide_show == true);
                        elementCurrent.value.ocultar =     (elementCurrent.value.ocultar == 1 || elementCurrent.value.ocultar == true);
                    }
                }).catch(error => {
                    errorMsg.value = "Erro: " + error;
                }).finally(() => {
                    loading.value = false;
                });
            };

            const findAllElements = (page) => {
                if (page) pagination.value.page = page;
                if (pagination.value.page < 1) pagination.value.page = 1;
                if (pagination.value.limitpage > 0 && pagination.value.page > pagination.value.limitpage) pagination.value.page = pagination.value.limitpage;

                loading.value = true;
                let serverpage = pagination.value.page;
                if (serverpage < 1) serverpage = 1;

                let params = new URLSearchParams();
                params.append('page', serverpage);
                params.append('row_count', pagination.value.rowCount);
                params.append('token', generateToken(256));
                
                if (elementCurrent.value.nome) params.append('nome', elementCurrent.value.nome);

                axios.get(`${serverUrl}?${params.toString()}`, { headers: getAuthHeader() }).then(response => {
                    processResponse(response.data);
                }).catch(error => {
                    console.log(error);
                    errorMsg.value = "Erro na conexão: " + error;
                }).finally(() => {
                    loading.value = false;
                });
            };

            const saveElement = async () => {
                loading.value = true;
                elementCurrent.value.conteudo_noticia = quillConteudoNoticia.root.innerHTML;
                const data = { ...elementCurrent.value };
                
                // Sending base64 as requested
                if (foto_principal_base64.value) {
                    // Requested field
                    data.foto_principal_base64 = foto_principal_base64.value;
                    
                    // Compatibility with existing backend (controllerMenus expects 'foto_principal' array for saveBase64)
                    data.foto_principal = {
                        namefile: files.foto_principal.value ? files.foto_principal.value.name : 'image.jpg',
                        data: foto_principal_base64.value
                    };
                }

                data.ocultar = (data.ocultar === true || data.ocultar == 1);

                let url = serverUrl;
                let method = 'post'; // axios methods are lower case

                if (data.id) {
                    url = `${serverUrl}/${data.id}`;
                    method = 'put';
                }
                
                // Axios dynamic method call
                axios[method](url, data, { headers: getAuthHeaderJSON() }).then(response => {
                    processResponse(response.data);
                    if (successMsg.value) {
                        // Reset to default state on success
                        prepareNew(); 
                        state.value = 'default';
                        findAllElements(pagination.value.page);
                    }
                }).catch((error) => {
                    console.log(error);
                    errorMsg.value = "Erro desconhecido: " + error;
                }).finally(() => {
                    loading.value = false;
                });
            };

            const deleteElement = (id) => {
                loading.value = true;
                axios.delete(serverUrl+"/"+id, { headers: getAuthHeader() }).then(response => {
                    processResponse(response.data);
                    if (successMsg.value || !errorMsg.value) { 
                            prepareNew();
                            state.value = 'default';
                            findAllElements(pagination.value.page);
                    }
                }).catch(error => {
                    errorMsg.value = "Erro ao excluir: " + error;
                }).finally(() => {
                    loading.value = false;
                });
            };
            
            const requestDelete = (element) => {
                itemToDelete.value = element;
                showModal.value = true;
            };

            const confirmDelete = () => {
                if (itemToDelete.value) {
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

            watch(() => state.value, (v) => {
                if (v === 'new' || v === 'edit') {
                    //setTimeout(initQuill, 200);
                }
            });

            onMounted(async () => {
                await carregarParentMenus();
                await findAllElements(1);            
                // Verifique se o elemento existe antes de chamar
                setTimeout(() => {  initQuill(); }, 200);
                setTimeout(() => {                                  
                    // Botão para inserir tabela com tamanho personalizado
                    const btnInsertTable = document.querySelector('.ql-table-better');
                    if (btnInsertTable) {
                        btnInsertTable.addEventListener('click', () => {
                            openShowModalTable();
                        });
                    }                
                }, 400);
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
                pagination,
                parentMenus,
                handleFile,
                prepareNew,
                cancelAction,
                editItem,
                saveElement,
                requestDelete,
                confirmDelete,
                closeModal,
                openImageModal,
                closeImageModal,
                findById,
                findAllElements,
                getMenuName,
                // Exposed for Template
                imagemPreviewOuAtual,
                temImagemParaMostrar,
                removeImage,
                fileInput,

                // Os que faltam e causam o erro atual:
                showModalTable,
                tableRows,
                tableColumns,

                // Métodos relacionados ao modal de tabela
                closeModalTable,
                insertTableQuill,
                openShowModalTable
            };
        }
    }).mount('#app');
</script>
<?php include($_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/foot.php');?>