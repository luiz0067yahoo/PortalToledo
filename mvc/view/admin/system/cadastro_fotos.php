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

    <h1>CADASTRO DE FOTOS</h1>
    <br>

    <div class="row">
        <div class="col-sm-12">
            <form class="cadastro" id="cadastro_foto" method="POST" enctype="multipart/form-data" onsubmit="return false;">

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

                <!-- Nome da Foto -->
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-bars"></i></span>
                    <input type="text" class="form-control" v-model="elementCurrent.nome" placeholder="Nome da Foto">
                </div>

                <!-- Preview da imagem atual ou nova -->
                <div class="mb-3" v-if="temImagemParaMostrar">
                    <!-- Se houver mútiplas fotos selecionadas (nova seleção) -->
                    <div v-if="fotos_struct && fotos_struct.length > 0" class="d-flex flex-wrap gap-2">
                        <div v-for="(foto, index) in fotos_struct" :key="index" class="position-relative" style="display: inline-block;">
                             <img :src="foto.preview" style="height:120px; width:auto; border:1px solid #ccc; border-radius:4px;">
                             <button type="button" @click="removeIndividualImage(index)" class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                     style="transform: translate(50%, -50%); border-radius: 50%; padding: 2px 6px; font-size: 10px;">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Se não houver seleção nova, mas houver foto salva e apenas 1 (legacy ou edit mode sem nova seleção) -->
                    <div v-else class="position-relative" style="display: inline-block;">
                        <img :src="imagemPreviewOuAtual" style="height:120px; width:auto; border:1px solid #ccc; border-radius:4px;">
                        <button type="button" @click="removeImage" class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                style="transform: translate(50%, -50%); border-radius: 50%;">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Upload da Foto -->
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-camera"></i></span>
                    <input type="file" ref="fileInput" class="form-control" accept="image/*" @change="handleFile" multiple>
                </div>

                <!-- Ocultar -->
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-eye-slash"></i></span>
                    <div class="form-control">
                        <input type="checkbox" v-model="elementCurrent.ocultar" id="ocultar">
                        <label for="ocultar">Ocultar</label>
                    </div>
                </div>

                <!-- Botões (mesma lógica do código de AlbumFotos) -->
                <div class="row">
                    <div class="col-md-12 d-flex gap-2">
                
                        <button v-if="state=='default'" @click="prepareNew" name="novo" type="button" class="btn btn-dark novo"><i class="fa fa-sticky-note" aria-hidden="true"></i> Novo</button>      
                        
                        <button v-if="state=='new'" @click="findAllElements(1); state='find'" name="buscar" type="button" class="btn btn-primary buscar"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
                    
                        <button v-if="state=='default'||state=='new'||state=='edit'||state=='find'" @click="saveElement" name="salvar" class="btn btn-success salvar"><i class="fas fa-save"></i> Salvar</button>    
                    
                        <button v-if="state=='findById'" @click="state='edit'" name="editar" type="button" class="btn btn-primary editar"><i class="fa fa-edit" aria-hidden="true"></i> Editar</button>
                        
                        <button v-if="state=='edit'" @click="requestDelete(elementCurrent)" name="excluir" type="button" class="btn btn-danger excluir"><i class="fa fa-times" aria-hidden="true"></i> Excluir</button>
                        
                        <button v-if="state=='new'||state=='edit'||state=='find'" @click="cancelAction" name="cancelar" type="button" class="btn btn-danger cancelar"><i class="fa fa-ban" aria-hidden="true"></i> Cancelar</button>

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
                        <th>Álbum</th>
                        <th>Foto</th>
                        <th>Nome</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="element in elements" :key="element.id">
                        <td>{{ element.id }}</td>
                        <td>{{ element.album }}</td>
                        <td>
                            <img v-if="element.foto" :src="`/uploads/album/original/${element.foto}`"
                                 style="height:60px; width:auto; cursor:pointer; border-radius:4px;"
                                 @click="openImageModal(`/uploads/album/1024x768/${element.foto}`)">
                        </td>
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

            <!-- Modal Zoom Imagem -->
            <div v-if="modalImage" class="modal-overlay" @click="closeImageModal" style="z-index: 10000;">
                <div class="modal-content-custom" style="background:transparent; box-shadow:none;">
                    <img :src="modalImage" style="max-width: 90vw; max-height: 90vh; border: 2px solid white; box-shadow: 0 0 15px rgba(0,0,0,0.5);">
                </div>
            </div>
        </div>
    </div>
</div>
<script type="module">
import { createApp, ref, onMounted, computed } from 'vue';
import { fileToBase64 } from '/assets/js/utils/base64.js';

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
            nome: '',
            ocultar: false,
            foto: ''
        });
        const elements = ref([]);
        const pagination = ref({
            page: 1,
            rowCount: 10,
            total: 0,
            limitpage: 0
        });
        const parentAlbumFotos = ref([]);
        
        // Config
        const serverUrl = '/server/fotos';
        
        // File handling variables
        const files = {
            foto: ref(null)
        };
        const foto_base64 = ref('');
        const fotoPreview = ref('');
        const fotos_struct = ref([]);

        // Computed properties for Image Preview
        const imagemPreviewOuAtual = computed(() => {
            if (fotoPreview.value) {
                return fotoPreview.value;
            }
            if (elementCurrent.value.foto) {
                return `/uploads/album/original/${elementCurrent.value.foto}`;
            }
            return '';
        });

        const temImagemParaMostrar = computed(() => {
            return !!imagemPreviewOuAtual.value;
        });

        // Refs
        const fileInput = ref(null);

        // Method to handle file selection
        // Method to handle file selection
        function handleFile(e) {
            const selectedFiles = e.target.files;
            fotos_struct.value = [];
            
            if (!selectedFiles || selectedFiles.length === 0) {
                 fotoPreview.value = '';
                 foto_base64.value = '';
                 files.foto.value = null;
                 return;
            }

            files.foto.value = selectedFiles;
            const fileArray = Array.from(selectedFiles);
            
            // Generate promises for all files
            const promises = fileArray.map(file => {
                return new Promise((resolve) => {
                     const reader = new FileReader();
                     reader.onload = (evt) => {
                         const b64 = evt.target.result.split(',')[1];
                         resolve({
                             namefile: file.name,
                             data: b64,
                             preview: evt.target.result,
                             fileObject: file // Store original file for input manipulation
                         });
                     };
                     reader.readAsDataURL(file);
                });
            });

            Promise.all(promises).then(results => {
                fotos_struct.value = results;
                
                // Set preview and legacy base64 for the first image
                if (results.length > 0) {
                    fotoPreview.value = results[0].preview;
                    foto_base64.value = results[0].data;
                }
            });
        }

        // Method to remove image
        function removeImage() {
            // Clear current server image
            elementCurrent.value.foto = '';
            
            // Clear preview
            fotoPreview.value = '';
            foto_base64.value = '';
            fotos_struct.value = [];
            
            // Clear file object
            files.foto.value = null;

            // Clear input DOM element
            if (fileInput.value) {
                fileInput.value.value = '';
            }
             const fileInputEl = document.querySelector('input[type="file"]');
             if(fileInputEl) fileInputEl.value = '';
        }

        function removeIndividualImage(index) {
            fotos_struct.value.splice(index, 1);
            
            // Reconstruct the FileList for the input
            const dataTransfer = new DataTransfer();
            fotos_struct.value.forEach(item => {
                if(item.fileObject) {
                    dataTransfer.items.add(item.fileObject);
                }
            });

            // Update the input element
            const fileInputEl = document.querySelector('input[type="file"]');
            if (fileInputEl) {
                fileInputEl.files = dataTransfer.files;
                files.foto.value = fileInputEl.files; // Update vue ref used elsewhere if needed
            }

            // Update legacy/single refs to reflect the first image of the remaining set (or clear if empty)
            if (fotos_struct.value.length > 0) {
                fotoPreview.value = fotos_struct.value[0].preview;
                foto_base64.value = fotos_struct.value[0].data;
            } else {
                // If all removed, clear everything
                fotoPreview.value = '';
                foto_base64.value = '';
                files.foto.value = null;
                if(fileInputEl) fileInputEl.value = '';
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
            elementCurrent.value = { id: '', id_menu: '', nome: '', ocultar: false, foto: '' };
            files.foto.value = null;
            fotoPreview.value = '';
            foto_base64.value = '';
            fotos_struct.value = [];
            const fileInput = document.querySelector('input[type="file"]');
            if(fileInput) fileInput.value = '';
            state.value = 'new';
        };

        const cancelAction = () => {
            clearMsg();
            state.value = 'default';
            loading.value = false;
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
                nome: '',
                ocultar: false,
                foto: ''
            };
            elements.value = [];
            pagination.value = {
                page: 1,
                rowCount: 10,
                total: 0,
                limitpage: 0
            };
            parentAlbumFotos.value = [];
            
            // Config
            
            // File handling variables
            files.foto.value = null;
            foto_base64.value = '';
            fotos_struct.value = [];
            fotoPreview.value    = '';
        };

        const editItem = (element) => {
            clearMsg();
            elementCurrent.value = { ...element };
            elementCurrent.value.ocultar = (element.ocultar == 1 || element.ocultar == true);
            
            // Reset new file selection on edit start
            files.foto.value = null;
            fotoPreview.value = '';
            foto_base64.value = '';
            fotos_struct.value = [];
            const fileInput = document.querySelector('input[type="file"]');
            if(fileInput) fileInput.value = '';

            state.value = 'edit';
        };

        const carregarParentAlbumFotos = async () => {
             try {
                const response = await axios.get(`/server/albumFotos`, { headers: getAuthHeader() });
                const data = response.data;
                if(data.elements) parentAlbumFotos.value = data.elements;
            } catch (e) {
                console.error(e);
            }
        };
        
        const getMenuName = (id) => {
            const m = parentAlbumFotos.value.find(x => x.id == id);
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
            
            // Sending base64 as requested - taking directly from thumbnails (fotos_struct)
            if (fotos_struct.value && fotos_struct.value.length > 0) {
                 const mappedPhotos = fotos_struct.value.map(f => ({
                    namefile: f.namefile,
                    data: f.data
                 }));

                 if (mappedPhotos.length === 1) {
                     data.foto = mappedPhotos[0];
                 } else {
                     data.foto = mappedPhotos;
                 }
                 // Set legacy field if backend still relies on it for something, 
                 // but primary payload is data.foto
                 data.foto_base64 = mappedPhotos[0].data;
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

        onMounted(() => {
            carregarParentAlbumFotos();
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
            pagination,
            parentAlbumFotos,
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
            fotos_struct,
            imagemPreviewOuAtual,
            temImagemParaMostrar,
            imagemPreviewOuAtual,
            temImagemParaMostrar,
            removeImage,
            removeIndividualImage,
            fileInput
        };
    }
}).mount('#app');
</script>
<?php include($_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/foot.php'); ?>