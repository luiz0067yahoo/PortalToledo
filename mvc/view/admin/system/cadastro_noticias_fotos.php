<?php 
	require ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
	include($_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/top.php');
?>
<div id="app">
    <div id="loader" v-if="loading">
        <div class="loader"></div>
    </div>
    <br>
    <div class="container">
        <h1>CADASTRO DE FOTOS DE NOTÍCIAS</h1>
        <br>
        <br>
        <div class="row">
            <div class="sm-12">
                <form class="cadastro" id="cadastro_noticia_foto" method="POST" enctype="multipart/form-data" onsubmit="return false;">
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
                            <label class="sr-only" for="inlineFormInputGroupNoticia">Notícia</label>
                            <div class="input-group-prepend">
                                <div class="input-group-text h-100">
                                    <i class="fa fa-link" aria-hidden="true"></i>
                                </div>
                            </div>
                            <select v-model="elementCurrent.id_noticias" class="form-control" id="inlineFormInputGroupNoticia" name="id_noticias">
                                <option value="">Selecione uma Notícia</option>
                                <option v-for="noticia in noticias" :value="noticia.id" :key="noticia.id">{{ noticia.titulo }}</option>
                            </select>
                        </div>
                        
                        <div class="input-group mb-3">
                            <label class="sr-only" for="inlineFormInputGroupnome">Nome</label>
                            <div class="input-group-prepend">
                                <div class="input-group-text h-100">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" v-model="elementCurrent.nome" id="inlineFormInputGroupnome" placeholder="Nome da foto" name="nome">
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
                            <input class="form-control" id="inlineFormInputGroupfoto" placeholder="Foto da notícia" ref="fileInput" name="foto[]" type="file" multiple accept="image/jpeg" @change="handleFileUpload">
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
                        
                        <button v-if="state=='edit'" @click="deleteElement(elementCurrent.id);" name="excluir" type="button" class="btn btn-danger excluir"><i class="fa fa-times" aria-hidden="true"></i> Excluir</button>
                        
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
                            <th>Notícia</th>
                            <th>Nome</th>
                            <th>Foto</th>
                            <th class="text-center">Ocultar</th>
                            <th class="text-center" style="width: 120px;">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="element in elements" :key="element.id" @mouseover="hover=true" @mouseleave="hover=false">
                            <td>{{element.id}}</td>
                            <td>{{ getNoticiaTitle(element.id_noticias) }}</td>
                            <td>{{element.nome}}</td>
                            <td>
                                <a v-if="element.foto" :href="'/uploads/noticias_fotos/1024x768/'+element.foto" target="_blank" class="btn btn-sm btn-info"><i class="fa fa-image"></i> Ver</a>
                            </td>
                            <td class="text-center">
                                <input type="checkbox" disabled :checked="element.ocultar == 1">
                            </td>
                            <td class="text-center">
                                <button @click="editItem(element)" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
                                <button @click="deleteElement(element.id)" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button>
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
            serverUrl: '/server/noticiasFotos',
            serverNoticiaUrl: '/server/noticias',
            elements: [],
            noticias: [],
            elementCurrent: { id: "", id_noticias: "", nome: "", ocultar: false },
            files: [],
            pagination: {
                page: 1,
                rowCount: 10,
                total: 0,
                limitpage: 0
            },
            loading: false
        },
        mounted: function() {
             this.loadNoticias();
             this.findAllElements(1); 
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
                this.elementCurrent = { id: "", id_noticias: this.elementCurrent.id_noticias, nome: "", ocultar: false };
                this.files = [];
                if(this.$refs.fileInput) this.$refs.fileInput.value = "";
                this.state = 'new';
            },
            cancelAction() {
                this.errorMsg= "";
                this.successMsg= "";
                this.infoMsg= "";
                this.state= 'default';
                this.serverUrl= '/server/noticiasFotos';
                this.serverNoticiaUrl= '/server/noticias';
                this.elements= [];
                this.noticias= [];
                this.elementCurrent= { id: "", id_noticias: this.elementCurrent.id_noticias, nome: "", ocultar: false };
                this.files= [];
                this.pagination= {
                    page: 1,
                    rowCount: 10,
                    total: 0,
                    limitpage: 0
                };
                this.loading= false;
                if(this.$refs.fileInput) this.$refs.fileInput.value = "";
            },
            editItem(element) {
                this.clearMsg();
                this.elementCurrent = { ...element };
                this.elementCurrent.ocultar = (this.elementCurrent.ocultar == 1 || this.elementCurrent.ocultar == true);
                this.files = [];
                if(this.$refs.fileInput) this.$refs.fileInput.value = "";
                this.state = 'edit';
            },
            getNoticiaTitle(id) {
                if(!id) return "";
                const noticia = this.noticias.find(n => n.id == id);
                return noticia ? noticia.titulo : id;
            },
            loadNoticias() {
                let params = new URLSearchParams();
                params.append('row_count', 1000); 
                params.append('token', this.generateToken(256));
                
                axios.get(this.serverNoticiaUrl+"?"+params.toString()).then(response => {
                   if(response.data.elements) {
                       this.noticias = response.data.elements;
                   }
                }).catch(error => {
                    console.error("Error loading noticias", error);
                });
            },
            handleFileUpload(event) {
                this.files = event.target.files;
            },
            findById(id) {
                if ((id!=null) && (id!=undefined) && (id.length!=0)) {
                    this.loading = true;
                    axios.get(this.serverUrl+"/"+id).then(response => {
                        this.processResponse(response.data);
                        if (this.elements && this.elements.length > 0) {
                             this.elementCurrent = this.elements[0];
                             this.state = 'findById';
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
                
                for( var i = 0; i < this.files.length; i++ ){
                    let file = this.files[i];
                    formData.append('foto[' + i + ']', file);
                }

                formData.append('foto_formats', '160x120,320x240,480x640,800x600,1024x768,1366x768');
                formData.append('foto_path', 'noticia_fotos');
                
                if(this.elementCurrent?.id){
                    axios.put(this.serverUrl+"/"+this.elementCurrent.id, formData).then(response => {
                        this.processResponse(response.data);
                        if (this.successMsg) {
                            this.state = 'default';
                             this.elementCurrent = { id: "", id_noticias: "", nome: "", ocultar: false };
                             this.files = [];
                             if(this.$refs.fileInput) this.$refs.fileInput.value = "";
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
							 this.elementCurrent = { id: "", id_noticias: "", nome: "", ocultar: false };
                             this.files = [];
                             if(this.$refs.fileInput) this.$refs.fileInput.value = "";
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
            deleteElement(id) {
                if(confirm("Tem certeza que deseja excluir?")) {
                    this.loading = true;
                    axios.delete(this.serverUrl+"/"+id).then(response => {
                        this.processResponse(response.data);
                        if (this.successMsg || !this.errorMsg) { 
                             this.elementCurrent = {id:"", id_noticias: "", nome: "", ocultar: false};
                             this.state = 'default';
                             this.findAllElements(this.pagination.page);
                        }
                    }).catch(error => {
                        this.errorMsg = "Erro ao excluir: " + error;
                    }).finally(() => {
                        this.loading = false;
                    });
                }
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
