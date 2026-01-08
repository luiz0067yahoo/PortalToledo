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
        <h1>CADASTRO DE TIPOS DE ANÚNCIOS</h1>
        <br>
        <br>
        <div class="row">
            <div class="sm-12">
                <form class="cadastro" id="cadastro_noticia" method="POST" enctype="multipart/form-data" onsubmit="return false;">
                    <div class="form-row align-items-center">
                        <div class="input-group mb-3">
                            <label class="sr-only" for="inlineFormInputGroupcodigo">codigo</label>
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
                            <div class="input-group-prepend">
                                <div class="input-group-text h-100">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" v-model="elementCurrent.nome" id="inlineFormInputGroupnome" placeholder="Nome do Anuncio" name="nome">
                            <div class="input-group-append">								
                                <button name="buscar" @click="findAllElements(1)" type="button" class="btn btn-primary buscarcampo buscarnome d-none"><i class="fa fa-search" aria-hidden="true"></i></button>
                            </div>
                        </div>
                        
                        <div class="input-group mb-3">
                            <label class="sr-only" for="inlineFormInputGroupAltura">Altura</label>
                            <div class="input-group-prepend">
                                <div class="input-group-text h-100">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                </div>
                            </div>
                            <input type="number" class="form-control" v-model="elementCurrent.altura" id="inlineFormInputGroupAltura" placeholder="Altura" name="altura">
                        </div>
                        
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <div class="input-group-text h-100">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" v-model="elementCurrent.largura" id="inlineFormInputGroupLargura" placeholder="Largura" name="largura">
                        </div>
                        
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <div class="input-group-text h-100">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                </div>
                            </div>
                            <span class="form-check-label form-control" for="ocultar">
                                <input class="" type="checkbox" id="ocultar" name="ocultar" v-model="elementCurrent.ocultar" >
                                Ocultar
                            </span>
                        </div>

                        <button v-if="state=='default'" @click="prepareNew();" name="novo" type="button" class="btn btn-dark novo"><i class="fa fa-sticky-note" aria-hidden="true"></i> Novo</button>      
                        
                        <button v-if="state=='new'" @click="findAllElements(1); state='find'" name="buscar" type="button" class="btn btn-primary buscar"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
                    
                        <button v-if="state=='default'||state=='new'||state=='edit'||state=='find'" @click="saveElement()" name="salvar" class="btn btn-success salvar"><i class="fas fa-save"></i></i> Salvar</button>    
                    
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
                            <th>Nome</th>
                            <th>Altura</th>
                            <th>Largura</th>
                            <th class="text-center">Ocultar</th>
                            <th class="text-center" style="width: 120px;">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="element in elements" :key="element.id" @mouseover="hover=true" @mouseleave="hover=false">
                            <td>{{element.id}}</td>
                            <td>{{element.nome}}</td>
                            <td>{{element.altura}}</td>
                            <td>{{element.largura}}</td>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>		
<script type="module">
    import { postCadastro } from '/assets/js/services/api.js';
    import { fileToBase64 } from '/assets/js/utils/base64.js';
    var app = new Vue({
        el: '#app',
        data: {
            errorMsg: "",
            successMsg: "",
            infoMsg: "",
            state: 'default',
            serverUrl: '/server/tiposAnuncios',
            elements: [],
            elementCurrent: { id: "", nome: "", altura: "", largura: "", ocultar: false },
            pagination: {
                page: 1,
                rowCount: 10,
                total: 0,
                limitpage: 0
            },
            searchParams: {
                nome: "",
                id: ""
            },
            loading: false,
            showModal: false,
            itemToDelete: null
        },
        mounted: function() {
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
            getToken() {
                const userData = localStorage.getItem('portalToledoData');
                if (userData) {
                    try {
                        const parsed = JSON.parse(userData);
                        return parsed.token;
                    } catch (e) {
                        return '';
                    }
                }
                return '';
            },
            getAuthHeader() {
                return {
                    'Authorization': `Bearer ${this.getToken()}`
                };
            },
            getAuthHeaderJSON() {
                return {
                    'Authorization': `Bearer ${this.getToken()}`,
                    'Content-Type': 'application/json'
                };
            },
            prepareNew() {
                this.clearMsg();
                this.elementCurrent = { id: "", nome: "", altura: "", largura: "", ocultar: false };
                this.state = 'new';
                this.searchParams= {
                    nome: "",
                    id: ""
                };
            },
            cancelAction() {
                this.clearMsg();
                this.state = 'default';
                this.elementCurrent = { id: "", nome: "", altura: "", largura: "", ocultar: false };
                this.errorMsg = "";
                this.successMsg = "";
                this.infoMsg = "";
                this.state = 'default';
                this.serverUrl = '/server/tiposAnuncios';
                this.elements = [];
                this.pagination= {
                    page: 1,
                    rowCount: 10,
                    total: 0,
                    limitpage: 0
                };
                this.searchParams= {
                    nome: "",
                    id: ""
                };
                this.loading= false;
            },
            editItem(element) {
                this.clearMsg();
                this.elementCurrent = { ...element };
                this.elementCurrent.ocultar = (this.elementCurrent.ocultar == 1 || this.elementCurrent.ocultar == true);
                this.state = 'edit';
            },
            findById(id) {
                if ((id!=null) && (id!=undefined) && (id.length!=0)) {
                    this.loading = true;
                    this.loading = true;
                    axios.get(this.serverUrl+"/"+id, { headers: this.getAuthHeader() }).then(response => {
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

                axios.get(this.serverUrl+"?"+params.toString(), { headers: this.getAuthHeader() }).then(response => {
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
                const data = { ...this.elementCurrent };
                data.ocultar = (data.ocultar === true || data.ocultar == 1);
                
                if(this.elementCurrent?.id){
                    axios.put(this.serverUrl+"/"+this.elementCurrent.id, data, { headers: this.getAuthHeaderJSON() }).then(response => {
                        this.processResponse(response.data);
                        if (this.successMsg) {
                            this.state = 'default';
                            this.elementCurrent = {id:"", nome: "", altura: "", largura: "", ocultar: false};
                            this.findAllElements(this.pagination.page);
                        }
                    }).catch((error) => {
                        console.log(error);
                        this.errorMsg = "Erro desconhecido: " + error;
                    }).finally(() => {
                        this.loading = false;
                    });
                }else{
					axios.post(this.serverUrl, data, { headers: this.getAuthHeaderJSON() }).then(response => {
						this.processResponse(response.data);
						if (this.successMsg) {
							this.state = 'default';
							this.elementCurrent = {id:"", nome: "", altura: "", largura: "", ocultar: false};
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
            deleteElement(id) {
                this.loading = true;
                axios.delete(this.serverUrl+"/"+id, { headers: this.getAuthHeader() }).then(response => {
                    this.processResponse(response.data);
                    if (this.successMsg || !this.errorMsg) { 
                            this.elementCurrent = {id:"", nome: "", altura: "", largura: "", ocultar: false};
                            this.state = 'default';
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
