<?php 
	require ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
	include($_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/top.php');
?>

<style>
.modal-overlay {
    position: fixed!important;
    top: 0!important;
    left: 0!important;
    width: 100vw!important;
    height: 100vh!important;
    background: rgba(0, 0, 0, 0.8);
    display: block;
    justify-content: center;
    align-items: center;
    z-index: 99999!important;
}
.modal-content-custom {
    background: white;
    padding: 30px;
    border-radius: 8px;
    min-width: 300px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
}
.modal-content-image {
    background: transparent;
    padding: 10px;
    text-align: center;
    position: relative;
}
.modal-actions {
    display: flex;
    justify-content: center;
    gap: 20px;
}
.close-image-modal{
    top: 10px;
    right: 10px;
    position: fixed;

}
</style>
<div id="app">
    <div id="loader" v-if="loading">
        <div class="loader"></div>
    </div>
    <br>
    <div class="container">
        <h1>CADASTRO DE ANÚNCIOS</h1>
        <br>
        <br>
        <div class="row">
            <div class="sm-12">
                <form class="cadastro" id="cadastro_anuncio" method="POST" enctype="multipart/form-data" onsubmit="return false;">
                    <div class="form-row align-items-center">
                        <div class="input-group mb-3">
                            <label class="sr-only" for="inlineFormInputGroupcodigo">Código</label>
                            <div class="input-group-prepend">
                                <div class="input-group-text h-100">
                                    <i class="fa fa-key" aria-hidden="true"></i>
                                </div>
                            </div>
                            <input type="number" disabled v-model="elementCurrent.id" class="form-control" id="inlineFormInputGroupcodigo" placeholder="Código" name="id">
                            <div class="input-group-append">								
                                <button name="buscar" @click="findById(elementCurrent.id)" type="button" class="btn btn-primary buscarcampo buscarcodigo d-none"><i class="fa fa-search" aria-hidden="true"></i></button>
                            </div>
                        </div>
                        
                        <div class="input-group mb-3">
                            <label class="sr-only" for="inlineFormInputGroupId_tipo_anuncio">Tipo Anúncio</label>
                            <div class="input-group-prepend">
                                <div class="input-group-text h-100">
                                    <i class="fa fa-link" aria-hidden="true"></i>
                                </div>
                            </div>
                             <select v-model="elementCurrent.id_tipo_anuncio" class="form-control" id="inlineFormInputGroupId_tipo_anuncio" name="id_tipo_anuncio">
                                <option value="">Selecione o Tipo de Anúncio</option>
                                <option v-for="tipo in tiposAnuncios" :value="tipo.id" :key="tipo.id">{{ tipo.nome }}</option>
                            </select>
                        </div>

                        <div class="input-group mb-3">
                            <label class="sr-only" for="inlineFormInputGroupId_menu">Menu</label>
                            <div class="input-group-prepend">
                                <div class="input-group-text h-100">
                                    <i class="fa fa-list" aria-hidden="true"></i>
                                </div>
                            </div>
                             <select v-model="elementCurrent.id_menu" class="form-control" id="inlineFormInputGroupId_menu" name="id_menu">
                                <option value="">Selecione o Menu (Opcional)</option>
                                <option v-for="menu in menus" :value="menu.id" :key="menu.id">{{ menu.nome }}</option>
                            </select>
                        </div>
                        
                        <div class="input-group mb-3">
                            <label class="sr-only" for="inlineFormInputGroupnome">Nome</label>
                            <div class="input-group-prepend">
                                <div class="input-group-text h-100">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" v-model="elementCurrent.nome" id="inlineFormInputGroupnome" placeholder="Nome do anúncio" name="nome">
                            <div class="input-group-append">								
                                <button name="buscar" @click="findAllElements(1)" type="button" class="btn btn-primary buscarcampo buscarnome d-none"><i class="fa fa-search" aria-hidden="true"></i></button>
                            </div>
                        </div>
                        
                        <div class="input-group mb-3">
                            <label class="sr-only" for="inlineFormInputGroupfoto">Foto</label>
                             <div class="input-group-prepend">
                                <div class="input-group-text h-100">
                                    <i class="fa fa-camera" aria-hidden="true"></i>
                                </div>
                            </div>
                            <input class="form-control" id="inlineFormInputGroupfoto" placeholder="Foto do Anuncio" ref="fileInputFoto" name="foto[]" type="file" multiple accept="image/jpeg" @change="handleFileUploadFoto">
                        </div>

                        <div class="input-group mb-3">
                            <label class="sr-only" for="inlineFormInputGroupfotomobile">Foto Mobile</label>
                             <div class="input-group-prepend">
                                <div class="input-group-text h-100">
                                    <i class="fa fa-mobile" aria-hidden="true"></i>
                                </div>
                            </div>
                            <input class="form-control" id="inlineFormInputGroupfotomobile" placeholder="Foto Mobile" ref="fileInputFotoMobile" name="foto_mobile[]" type="file" multiple accept="image/jpeg" @change="handleFileUploadFotoMobile">
                        </div>

                         <div class="input-group mb-3">
                            <label class="sr-only" for="inlineFormInputGroupfotoexpandida">Foto Expandida</label>
                             <div class="input-group-prepend">
                                <div class="input-group-text h-100">
                                    <i class="fa fa-expand" aria-hidden="true"></i>
                                </div>
                            </div>
                            <input class="form-control" id="inlineFormInputGroupfotoexpandida" placeholder="Foto do Anuncio Expandido" ref="fileInputFotoExpandida" name="fotoexpandida[]" type="file" multiple accept="image/jpeg" @change="handleFileUploadFotoExpandida">
                        </div>

                         <div class="input-group mb-3">
                            <label class="sr-only" for="inlineFormInputGroupfotomobileexpandida">Foto Mobile Expandida</label>
                             <div class="input-group-prepend">
                                <div class="input-group-text h-100">
                                    <i class="fa fa-expand" aria-hidden="true"></i>
                                </div>
                            </div>
                            <input class="form-control" id="inlineFormInputGroupfotomobileexpandida" placeholder="Foto Mobile Expandida" ref="fileInputFotoMobileExpandida" name="foto_mobile_expandida[]" type="file" multiple accept="image/jpeg" @change="handleFileUploadFotoMobileExpandida">
                        </div>

                        <!-- Text Areas -->
                        <div class="input-group mb-3">
                             <div class="input-group-prepend">
                                <div class="input-group-text h-100">Introdução</div>
                            </div>
                            <textarea class="form-control" v-model="elementCurrent.introducao" aria-label="Introdução"></textarea>
                        </div>
                         <div class="input-group mb-3">
                             <div class="input-group-prepend">
                                <div class="input-group-text h-100">Introdução 2</div>
                            </div>
                            <textarea class="form-control" v-model="elementCurrent.introducao2" aria-label="Introdução 2"></textarea>
                        </div>
                        <div class="input-group mb-3">
                             <div class="input-group-prepend">
                                <div class="input-group-text h-100">Descrição</div>
                            </div>
                            <!-- Added ID for CKEditor -->
                            <textarea class="form-control" id="editor_descricao" v-model="elementCurrent.descricao" aria-label="Descrição" rows="5"></textarea>
                        </div>

                        <!-- Social and Contact -->
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control" v-model="elementCurrent.facebook" placeholder="Facebook">
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control" v-model="elementCurrent.youtube" placeholder="Youtube">
                            </div>
                        </div>
                         <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control" v-model="elementCurrent.instagram" placeholder="Instagram">
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control" v-model="elementCurrent.whatsapp" placeholder="Whatsapp">
                            </div>
                        </div>
                        
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Endereço</span>
                            </div>
                            <input type="text" class="form-control" v-model="elementCurrent.endereco">
                        </div>

                         <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control" v-model="elementCurrent.telefone" placeholder="Telefone">
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control" v-model="elementCurrent.e_mail" placeholder="E-mail">
                            </div>
                        </div>
                        
                         <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control" v-model="elementCurrent.website" placeholder="Website">
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control" v-model="elementCurrent.url" placeholder="URL">
                            </div>
                        </div>
                        
                        <div class="input-group mb-3">
                            <label class="sr-only" for="ocultar">Ocultar</label>
                            <div class="input-group-prepend">
                                <div class="input-group-text h-100">
                                    <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                </div>
                            </div>
                             <span class="form-check-label form-control" for="ocultar">
                                <input type="checkbox" id="ocultar" name="ocultar" v-model="elementCurrent.ocultar">
                                Ocultar
                            </span>
                        </div>
                        
                        <button v-if="state=='default'" @click="prepareNew();" name="novo" type="button" class="btn btn-dark novo"><i class="fa fa-sticky-note" aria-hidden="true"></i> Novo</button>      
                        
                        <button v-if="state=='new'" @click="findAllElements(1); state='find'" name="buscar" type="button" class="btn btn-primary buscar"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
                    
                        <button v-if="state=='default'||state=='new'||state=='edit'||state=='find'" @click="saveElement()" name="salvar" class="btn btn-success salvar"><i class="fa fa-floppy-o" aria-hidden="true"></i> Salvar</button>    
                    
                        <button v-if="state=='findById'" @click="state='edit';" name="editar" type="button" class="btn btn-primary editar"><i class="fa fa-edit" aria-hidden="true"></i> Editar</button>
                        
                        <button v-if="state=='edit'" @click="requestDelete(elementCurrent);" name="excluir" type="button" class="btn btn-danger excluir"><i class="fa fa-times" aria-hidden="true"></i> Excluir</button>
                        
                        <button v-if="state=='new'||state=='edit'||state=='find'" @click="cancelAction();" name="cancelar" type="button" class="btn btn-danger cancelar"><i class="fa fa-ban" aria-hidden="true"></i> Cancelar</button>
                    </div>
                </form>
                <br>
                
                <div class="alert alert-success" v-if="successMsg" role="alert" v-html="successMsg"></div>
                <div class="alert alert-danger" v-if="errorMsg" role="alert" v-html="errorMsg"></div>
                <div class="alert alert-info" v-if="infoMsg" role="alert" v-html="infoMsg"></div>
                
                <br>
                <table class="table table-striped resultado_busca" v-if="elements.length > 0">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Tipo Anúncio</th>
                            <th>Nome</th>
                            <th>Foto</th>
                            <th>Foto Exp.</th>
                            <th class="text-center">Ocultar</th>
                            <th class="text-center" style="width: 120px;">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="element in elements" :key="element.id" @mouseover="hover=true" @mouseleave="hover=false">
                            <td>{{element.id}}</td>
                            <td>{{ getTipoAnuncioName(element.id_tipo_anuncio) }}</td>
                            <td>{{element.nome}}</td>
                            <td>
                                <img v-if="element.foto" :src="'/uploads/anuncio/160x120/'+element.foto" style="max-width: 80px; cursor: pointer;" @click="showImagePreview('/uploads/anuncio/1024x768/'+element.foto)" class="img-thumbnail" alt="Foto">
                            </td>
                            <td>
                                <img v-if="element.fotoexpandida" :src="'/uploads/anuncio/160x120/'+element.fotoexpandida" style="max-width: 80px; cursor: pointer;" @click="showImagePreview('/uploads/anuncio/1024x768/'+element.fotoexpandida)" class="img-thumbnail" alt="Foto Expandida">
                            </td>
                            <td class="text-center">
                                <input type="checkbox" disabled :checked="element.ocultar == 1">
                            </td>
                            <td class="text-center">
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
    
    <!-- Modal Image Preview -->
    <div class="modal-overlay" v-if="showImageModal" @click="closeImageModal">
        <div class="modal-content-image">
            <img :src="currentImageUrl" class="img-fluid" style="max-height: 90vh;">
            <button class="btn btn-secondary mt-2 close-image-modal" @click="closeImageModal"><i class="fa fa-times"></i></button>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>		
<script>
    var app = new Vue({
        el: '#app',
        data: {
            errorMsg: "",
            successMsg: "",
            infoMsg: "",
            state: 'default',
            serverUrl: '/server/anuncios',
            serverTipoAnuncioUrl: '/server/tiposAnuncios',
            serverMenusUrl: '/server/menus',
            elements: [],
            tiposAnuncios: [],
            menus: [],
            elementCurrent: { 
                id: "", id_tipo_anuncio: "", id_menu: "", nome: "", ocultar: false,
                introducao: "", introducao2: "", descricao: "",
                facebook: "", youtube: "", instagram: "", whatsapp: "",
                endereco: "", telefone: "", e_mail: "", website: "", url: ""
            },
            filesFoto: [],
            filesFotoMobile: [],
            filesFotoExpandida: [],
            filesFotoMobileExpandida: [],
            pagination: {
                page: 1,
                rowCount: 10,
                total: 0,
                limitpage: 0
            },
            loading: false,
            showModal: false,
            itemToDelete: null,
            showImageModal: false,
            currentImageUrl: ""
        },
        mounted: function() {
             this.loadTiposAnuncios();
             this.loadMenus();
             this.findAllElements(1); 
             
             // Initialize CKEditor
             CKEDITOR.replace( 'editor_descricao', {
                filebrowserUploadUrl: '/admin/explorer?functions=ckeditor_upload&url=anuncio',
                filebrowserBrowseUrl: '/admin/apps/explorer'
             });
             
             // Update Vue model when CKEditor data changes
             CKEDITOR.instances.editor_descricao.on('change', () => {
                 this.elementCurrent.descricao = CKEDITOR.instances.editor_descricao.getData();
             });
        },
        methods: {
            generateToken(length) {
                var a = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890".split("");
                var b = [];
                for (var i = 0; i < length; i++) {
                    var j = (Math.random() * (a.length - 1)).toFixed(0);
                    b[i] = a[j];
                }
                return b.join("");
            },
            prepareNew() {
                this.clearMsg();
                this.elementCurrent = { 
                    id: "", id_tipo_anuncio: "", id_menu: "", nome: "", ocultar: false,
                    introducao: "", introducao2: "", descricao: "",
                    facebook: "", youtube: "", instagram: "", whatsapp: "",
                    endereco: "", telefone: "", e_mail: "", website: "", url: ""
                };
                this.filesFoto = [];
                this.filesFotoMobile = [];
                this.filesFotoExpandida = [];
                this.filesFotoMobileExpandida = [];
                if(this.$refs.fileInputFoto) this.$refs.fileInputFoto.value = "";
                if(this.$refs.fileInputFotoMobile) this.$refs.fileInputFotoMobile.value = "";
                if(this.$refs.fileInputFotoExpandida) this.$refs.fileInputFotoExpandida.value = "";
                if(this.$refs.fileInputFotoMobileExpandida) this.$refs.fileInputFotoMobileExpandida.value = "";
                if(this.$refs.fileInputFotoMobileExpandida) this.$refs.fileInputFotoMobileExpandida.value = "";
                this.state = 'new';
                
                // Clear CKEditor
                if (CKEDITOR.instances.editor_descricao) {
                    CKEDITOR.instances.editor_descricao.setData("");
                }
            },
            cancelAction() {
                this.errorMsg= "";
                this.successMsg= "";
                this.infoMsg= "";
                this.state= 'default';
                this.serverUrl= '/server/anuncios';
                this.serverTipoAnuncioUrl= '/server/tiposAnuncios';
                this.serverMenusUrl= '/server/menus';
                this.elements= [];
                this.tiposAnuncios= [];
                this.menus= [];
                this.elementCurrent= { 
                    id: "", id_tipo_anuncio: "", id_menu: "", nome: "", ocultar: false,
                    introducao: "", introducao2: "", descricao: "",
                    facebook: "", youtube: "", instagram: "", whatsapp: "",
                    endereco: "", telefone: "", e_mail: "", website: "", url: ""
                };
                this.filesFoto= [];
                this.filesFotoMobile= [];
                this.filesFotoExpandida= [];
                this.filesFotoMobileExpandida= [];
                this.pagination= {
                    page: 1,
                    rowCount: 10,
                    total: 0,
                    limitpage: 0
                },
                this.loading= false;
            },
            editItem(element) {
                this.clearMsg();
                this.elementCurrent = { ...element };
                this.elementCurrent.ocultar = (this.elementCurrent.ocultar == 1 || this.elementCurrent.ocultar == true);
                this.filesFoto = [];
                this.filesFotoMobile = [];
                this.filesFotoExpandida = [];
                this.filesFotoMobileExpandida = [];
                if(this.$refs.fileInputFoto) this.$refs.fileInputFoto.value = "";
                if(this.$refs.fileInputFotoMobile) this.$refs.fileInputFotoMobile.value = "";
                if(this.$refs.fileInputFotoExpandida) this.$refs.fileInputFotoExpandida.value = "";
                if(this.$refs.fileInputFotoMobileExpandida) this.$refs.fileInputFotoMobileExpandida.value = "";
                if(this.$refs.fileInputFotoMobileExpandida) this.$refs.fileInputFotoMobileExpandida.value = "";
                this.state = 'edit';
                
                // Set CKEditor data
                if (CKEDITOR.instances.editor_descricao) {
                    CKEDITOR.instances.editor_descricao.setData(this.elementCurrent.descricao);
                }
            },
            getTipoAnuncioName(id) {
                if(!id) return "";
                const tipo = this.tiposAnuncios.find(t => t.id == id);
                return tipo ? tipo.nome : id;
            },
            loadTiposAnuncios() {
                let params = new URLSearchParams();
                params.append('row_count', 1000); 
                params.append('token', this.generateToken(256));
                
                axios.get(this.serverTipoAnuncioUrl+"?"+params.toString()).then(response => {
                   if(response.data.elements) {
                       this.tiposAnuncios = response.data.elements;
                   }
                }).catch(error => {
                    console.error("Error loading tipos anuncios", error);
                });
            },
            loadMenus() {
                let params = new URLSearchParams();
                params.append('row_count', 1000); 
                params.append('token', this.generateToken(256));
                
                axios.get(this.serverMenusUrl+"?"+params.toString()).then(response => {
                   if(response.data.elements) {
                       this.menus = response.data.elements;
                   }
                }).catch(error => {
                    console.error("Error loading menus", error);
                });
            },
            handleFileUploadFoto(event) {
                this.filesFoto = event.target.files;
            },
            handleFileUploadFotoMobile(event) {
                this.filesFotoMobile = event.target.files;
            },
             handleFileUploadFotoExpandida(event) {
                this.filesFotoExpandida = event.target.files;
            },
            handleFileUploadFotoMobileExpandida(event) {
                this.filesFotoMobileExpandida = event.target.files;
            },
            findById(id) {
                if ((id!=null) && (id!=undefined) && (id.length!=0)) {
                    this.loading = true;
                    axios.get(this.serverUrl+"/"+id).then(response => {
                        this.processResponse(response.data);
                        if (this.elements && this.elements.length > 0) {
                             this.elementCurrent = this.elements[0];
                             this.elementCurrent = this.elements[0];
                             this.state = 'findById';
                             
                             // Set CKEditor data
                             if (CKEDITOR.instances.editor_descricao) {
                                 CKEDITOR.instances.editor_descricao.setData(this.elementCurrent.descricao);
                             }
                        }
                    }).catch(error => {
                        this.errorMsg = "Erro: " + error;
                    }).finally(() => {
                        this.loading = false;
                    });
                }
            },
            findAllElements(page) {
                if (page) this.pagination.page = page;
                if (this.pagination.page < 1) this.pagination.page = 1;
                if (this.pagination.limitpage > 0 && this.pagination.page > this.pagination.limitpage) this.pagination.page = this.pagination.limitpage;

                this.loading = true;
                let serverpage = this.pagination.page;
                if (serverpage < 1) serverpage = 1;

                let params = new URLSearchParams();
                params.append('page', serverpage);
                params.append('row_count', this.pagination.rowCount);
                params.append('token', this.generateToken(256));
                
                if (this.elementCurrent.nome) params.append('nome', this.elementCurrent.nome);
                if (this.elementCurrent.id) params.append('id', this.elementCurrent.id);

                axios.get(this.serverUrl+"?"+params.toString()).then(response => {
                    this.processResponse(response.data);
                }).catch(error => {
                    console.log(error);
                    this.errorMsg = "Erro na conexão: " + error;
                }).finally(() => {
                    this.loading = false;
                });
            },
            saveElement() {
                this.loading = true;
                var formData = new FormData();
                for (var i in this.elementCurrent) {
                    if (i === 'ocultar') {
                         formData.append(i, this.elementCurrent[i] ? 1 : 0);
                    } else if (this.elementCurrent[i] !== null && this.elementCurrent[i] !== "") {
                         formData.append(i, this.elementCurrent[i]);
                    }
                }
                
                for( var i = 0; i < this.filesFoto.length; i++ ){
                    let file = this.filesFoto[i];
                    formData.append('foto[' + i + ']', file);
                }
                for( var i = 0; i < this.filesFotoMobile.length; i++ ){
                    let file = this.filesFotoMobile[i];
                    formData.append('foto_mobile[' + i + ']', file);
                }
                 for( var i = 0; i < this.filesFotoExpandida.length; i++ ){
                    let file = this.filesFotoExpandida[i];
                    formData.append('fotoexpandida[' + i + ']', file);
                }
                 for( var i = 0; i < this.filesFotoMobileExpandida.length; i++ ){
                    let file = this.filesFotoMobileExpandida[i];
                    formData.append('foto_mobile_expandida[' + i + ']', file);
                }

                formData.append('foto_formats', '160x120,320x240,640x480,480x640,800x600,1024x768,1366x768');
                formData.append('foto_path', 'anuncio');

                formData.append('foto_mobile_formats', '160x120,320x240,640x480,480x640,800x600,1024x768,1366x768');
                formData.append('foto_mobile_path', 'anuncio');

                formData.append('fotoexpandida_formats', '160x120,320x240,640x480,480x640,800x600,1024x768,1366x768');
                formData.append('fotoexpandida_path', 'anuncio');

                formData.append('foto_mobile_expandida_formats', '160x120,320x240,640x480,480x640,800x600,1024x768,1366x768');
                formData.append('foto_mobile_expandida_path', 'anuncio');
                
                if(this.elementCurrent?.id){
                     axios.put(this.serverUrl+"/"+this.elementCurrent.id, formData).then(response => {
                        this.processResponse(response.data);
                        if (this.successMsg) {
                            this.state = 'default';
                            this.state = 'default';
                             this.elementCurrent = { 
                                 id: "", id_tipo_anuncio: "", id_menu: "", nome: "", ocultar: false,
                                 introducao: "", introducao2: "", descricao: "",
                                 facebook: "", youtube: "", instagram: "", whatsapp: "",
                                 endereco: "", telefone: "", e_mail: "", website: "", url: ""
                             };
                             this.filesFoto = [];
                             this.filesFotoMobile = [];
                             this.filesFotoExpandida = [];
                             this.filesFotoMobileExpandida = [];
                             if(this.$refs.fileInputFoto) this.$refs.fileInputFoto.value = "";
                             if(this.$refs.fileInputFotoMobile) this.$refs.fileInputFotoMobile.value = "";
                             if(this.$refs.fileInputFotoExpandida) this.$refs.fileInputFotoExpandida.value = "";
                             if(this.$refs.fileInputFotoExpandida) this.$refs.fileInputFotoExpandida.value = "";
                             if(this.$refs.fileInputFotoMobileExpandida) this.$refs.fileInputFotoMobileExpandida.value = "";
                             
                             // Clear CKEditor
                             if (CKEDITOR.instances.editor_descricao) {
                                 CKEDITOR.instances.editor_descricao.setData("");
                             }
                             
                            this.findAllElements(this.pagination.page);
                        }
                    }).catch((error) => {
                        console.log(error);
                        this.errorMsg = "Erro desconhecido: " + error;
                    }).finally(() => {
                        this.loading = false;
                    });
                }else{
					axios.post(this.serverUrl, formData).then(response => {
						this.processResponse(response.data);
						if (this.successMsg) {
							this.state = 'default';
							 this.elementCurrent = { 
                                 id: "", id_tipo_anuncio: "", id_menu: "", nome: "", ocultar: false,
                                 introducao: "", introducao2: "", descricao: "",
                                 facebook: "", youtube: "", instagram: "", whatsapp: "",
                                 endereco: "", telefone: "", e_mail: "", website: "", url: ""
                             };
                             this.filesFoto = [];
                             this.filesFotoMobile = [];
                             this.filesFotoExpandida = [];
                             this.filesFotoMobileExpandida = [];
                             if(this.$refs.fileInputFoto) this.$refs.fileInputFoto.value = "";
                             if(this.$refs.fileInputFotoMobile) this.$refs.fileInputFotoMobile.value = "";
                             if(this.$refs.fileInputFotoExpandida) this.$refs.fileInputFotoExpandida.value = "";
                             if(this.$refs.fileInputFotoExpandida) this.$refs.fileInputFotoExpandida.value = "";
                             if(this.$refs.fileInputFotoMobileExpandida) this.$refs.fileInputFotoMobileExpandida.value = "";
                             
                             // Clear CKEditor
                             if (CKEDITOR.instances.editor_descricao) {
                                 CKEDITOR.instances.editor_descricao.setData("");
                             }
                             
							this.findAllElements(this.pagination.page);
						}
					}).catch((error) => {
						console.log(error);
						this.errorMsg = "Erro desconhecido: " + error;
					}).finally(() => {
						this.loading = false;
					});
				}
            },
            requestDelete(element) {
                this.itemToDelete = element;
                this.showModal = true;
            },
            confirmDelete() {
                if (this.itemToDelete) {
                    this.deleteElement(this.itemToDelete.id);
                }
                this.closeModal();
            },
            closeModal() {
                this.showModal = false;
                this.itemToDelete = null;
            },
            showImagePreview(url) {
                this.currentImageUrl = url;
                this.showImageModal = true;
            },
            closeImageModal() {
                this.showImageModal = false;
                this.currentImageUrl = "";
            },
            deleteElement(id) {
                this.loading = true;
                axios.delete(this.serverUrl+"/"+id).then(response => {
                    this.processResponse(response.data);
                    if (this.successMsg || !this.errorMsg) { 
                            this.elementCurrent = {
                                id: "", id_tipo_anuncio: "", id_menu: "", nome: "", ocultar: false,
                                introducao: "", introducao2: "", descricao: "",
                                facebook: "", youtube: "", instagram: "", whatsapp: "",
                                endereco: "", telefone: "", e_mail: "", website: "", url: ""
                            };
                            this.state = 'default';
                            this.state = 'default';
                            
                             // Clear CKEditor
                             if (CKEDITOR.instances.editor_descricao) {
                                 CKEDITOR.instances.editor_descricao.setData("");
                             }
                             
                            this.findAllElements(this.pagination.page);
                    }
                }).catch(error => {
                    this.errorMsg = "Erro ao excluir: " + error;
                }).finally(() => {
                    this.loading = false;
                });
            },
            processResponse(data) {
                this.clearMsg();
                if (typeof data === 'string') {
                    try {
                        data = JSON.parse(data);
                    } catch (e) {
                         return;
                    }
                }
                
                if (data.mensagem_erro) {
                    this.errorMsg = data.mensagem_erro;
                } else if (data.message && data.error) {
                     this.errorMsg = data.message;
                }
                
                if (data.mensagem_sucesso) {
                    this.successMsg = data.mensagem_sucesso;
                } else if (data.message && !data.error) {
                    this.successMsg = data.message;
                } else if (data.mensagem_informacao) {
                    this.infoMsg = data.mensagem_informacao;
                }

                if (data.elements) {
                    this.elements = data.elements;
                }

                if (data.recordsCount !== undefined) {
                    this.pagination.total = parseInt(data.recordsCount);
                } else if (data.numero_registros !== undefined) {
                    this.pagination.total = parseInt(data.numero_registros);
                }

                if (this.pagination.total > 0) {
                     this.pagination.limitpage = Math.ceil(this.pagination.total / this.pagination.rowCount);
                     if (this.pagination.limitpage < 1) this.pagination.limitpage = 1;
                } else {
                    this.pagination.limitpage = 1; 
                }
            },
            clearMsg() {
                this.errorMsg = "";
                this.successMsg = "";
                this.infoMsg = "";
            }
        }
    });
</script>
<?php include($_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/foot.php');?>