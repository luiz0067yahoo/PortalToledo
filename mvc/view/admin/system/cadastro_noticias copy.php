<?php
require ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
include($_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/top.php');
?>

<!-- Add Axios for consistency -->
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/47.3.0/ckeditor5.css">
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
                
                <!-- CKEditor aqui (você pode manter o container #editor) -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Conteúdo da notícia</label>
                    <textarea 
                        id="editor" 
                        v-model="elementCurrent.conteudo_noticia"
                        style="display:none;"
                        ></textarea>
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
<script src="https://cdn.ckeditor.com/ckeditor5/47.4.0/ckeditor5.umd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/47.4.0/ckeditor5.umd.js"></script>
		<script>
		const {
			ClassicEditor,
			Essentials,
			Bold,
			Italic,
			Font,
			Paragraph
		} = CKEDITOR;

		ClassicEditor
			.create( document.querySelector( '#editor' ), {
				licenseKey: '<YOUR_LICENSE_KEY>', // Create a free account on https://portal.ckeditor.com/checkout?plan=free
				plugins: [ Essentials, Bold, Italic, Font, Paragraph ],
				toolbar: [
					'undo', 'redo', '|', 'bold', 'italic', '|',
					'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
				]
			} )
				.then( editor => {
					window.editor = editor;
				} )
				.catch( error => {
					console.error( error );
				} );
    </script>
<script type="module">
    import { createApp, ref, onMounted, computed } from 'vue';
// import { fileToBase64 } from '/assets/js/utils/base64.js';  // se ainda usar

createApp({
    setup() {
        // State
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
            elementCurrent.value = { id: '', id_menu: '', nome: '', ocultar: false, foto_principal: '' };
            files.foto_principal.value = null;
            foto_principalPreview.value = '';
            foto_principal_base64.value = '';
            const fileInput = document.querySelector('input[type="file"]');
            if(fileInput) fileInput.value = '';
            state.value = 'new';
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
        };

        const editItem = (element) => {
            clearMsg();
            elementCurrent.value = { ...element };
            elementCurrent.value.ocultar = (element.ocultar == 1 || element.ocultar == true);
            
            // Reset new file selection on edit start
            files.foto_principal.value = null;
            foto_principalPreview.value = '';
            foto_principal_base64.value = '';
            const fileInput = document.querySelector('input[type="file"]');
            if(fileInput) fileInput.value = '';

            state.value = 'edit';
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
                     elementCurrent.value.ocultar = (elementCurrent.value.ocultar == 1 || elementCurrent.value.ocultar == true);
                } else if (elements.value && elements.value.length > 0) {
                     elementCurrent.value = elements.value[0];
                     state.value = 'findById';
                     elementCurrent.value.ocultar = (elementCurrent.value.ocultar == 1 || elementCurrent.value.ocultar == true);
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

        onMounted(async () => {
            carregarParentMenus();
            findAllElements(1);

            try {
                
                console.log("ClassicEditor importado com sucesso:", !!ClassicEditor);

                const editor = await ClassicEditor.create(document.querySelector('#editor-container'), {
                    language: 'pt-br',
                    placeholder: 'Digite o conteúdo da notícia aqui...',
                    toolbar: [
                        'heading', '|',
                        'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                        'outdent', 'indent', '|',
                        'imageUpload', 'blockQuote', 'insertTable', '|',
                        'undo', 'redo'
                    ]
                });

                console.log("Editor criado com sucesso!", editor);

                // Sincronização bidirecional
                editor.model.document.on('change:data', () => {
                    elementCurrent.value.conteudo_noticia = editor.getData();
                });

                // Quando o Vue atualizar o model → atualiza o editor
                watch(() => elementCurrent.value.conteudo_noticia, (newValue) => {
                    if (editor.getData() !== newValue) {
                        editor.setData(newValue || '');
                    }
                }, { immediate: true });

            } catch (error) {
                console.error("ERRO CRÍTICO AO CRIAR CKEDITOR:", error);
            }
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
            fileInput
        };
    }
}).mount('#app');
</script>
<?php include($_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/foot.php');?>