<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

$headers = getallheaders();
$isApiRequest = isset($headers['Authorization']);

if ($isApiRequest) {
    header('Content-Type: application/json');
    require_once($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/controller/controllerFotos.php');

    $token = trim(str_replace('Bearer', '', $headers['Authorization']));
    
    $data = json_decode(file_get_contents('php://input'), true);
    if($data) $_POST = $data;

    if (isset($data['foto']) && is_array($data['foto'])) {
        $namefile = $data['foto']['namefile'];
        $base64   = $data['foto']['data'];
        
        $conteudo = base64_decode($base64);
        $directory = $_SERVER['DOCUMENT_ROOT'] . '/uploads/album/';
        if (!is_dir($directory)) mkdir($directory, 0777, true);
        
        $filename = uniqid() . '_' . $namefile;
        $pathArquivo = $directory . $filename;
        
        file_put_contents($pathArquivo, $conteudo);
        $_POST['foto'] = $filename; 
    }

    $controller = new controllerFotos();
    
    try {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
             $result = $controller->create();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
             $id = $data['id'] ?? ($_GET['id'] ?? null);
             $result = $controller->update($id);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
             $id = $data['id'] ?? ($_GET['id'] ?? null);
             $result = $controller->del($id);
        } else {
             if (isset($_GET['id'])) {
                 $result = $controller->findById($_GET['id']);
             } else {
                 $result = $controller->find();
             }
        }
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}
require ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
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

<div id="app" class="container">
    <h1>CADASTRO DE FOTOS</h1>
    <br>
    
    <div class="row">
        <div class="col-sm-12">
            <form @submit.prevent="enviarCadastro">
                <div class="input-group mb-3">
                     <span class="input-group-text"><i class="fa fa-key"></i></span>
                     <input type="number" class="form-control" v-model="form.id" placeholder="Código" disabled>
                     <button type="button" class="btn btn-primary" @click="buscarPorId" v-if="form.id"><i class="fa fa-search"></i></button>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-link"></i></span>
                    <select v-model="form.id_album" class="form-control">
                        <option value="">Selecione um Album</option>
                        <option v-for="album in albums" :value="album.id" :key="album.id">{{ album.nome }}</option>
                    </select>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-camera"></i></span>
                    <input class="form-control" type="file" @change="handleFile">
                </div>
                
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-bars"></i></span>
                    <input type="text" class="form-control" v-model="form.nome" placeholder="Nome da Foto">
                </div>
                
                 <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-eye-slash"></i></span>
                    <div class="form-control">
                        <input type="checkbox" v-model="form.ocultar" id="ocultar">
                        <label for="ocultar">Ocultar</label>
                    </div>
                </div>

                <div class="btn-group">
                    <button type="button" class="btn btn-dark" @click="resetForm"><i class="fa fa-sticky-note"></i> Novo</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Salvar</button>
                    <button type="button" class="btn btn-primary" @click="buscarTodos"><i class="fa fa-search"></i> Buscar</button>
                    <button type="button" class="btn btn-danger" @click="excluir" v-if="form.id"><i class="fa fa-times"></i> Excluir</button>
                </div>
            </form>

            <div v-if="msg.text" :class="'alert alert-'+msg.type" class="mt-3">{{ msg.text }}</div>

            <table class="table table-striped mt-4" v-if="lista.length > 0">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Album</th>
                        <th>Nome</th>
                        <th>Foto</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in lista" :key="item.id">
                        <td>{{ item.id }}</td>
                        <td>{{ getAlbumName(item.id_album) }}</td>
                        <td>{{ item.nome }}</td>
                        <td>
                            <a v-if="item.foto" :href="'/uploads/album/' + item.foto" target="_blank" class="btn btn-sm btn-info">Ver</a>
                        </td>
                        <td>
                            <button @click="editar(item)" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></button>
                            <button @click="excluirItem(item)" class="btn btn-sm btn-danger"><i class="fa fa-times"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="module">
import { createApp, ref, onMounted } from 'vue';
import { postCadastro } from '/assets/js/services/api.js';
import { fileToBase64 } from '/assets/js/utils/base64.js';

createApp({
    setup() {
        const token = localStorage.getItem('portalToledoData') ? JSON.parse(localStorage.getItem('portalToledoData')).token : '';
        const form = ref({
            id: '',
            id_album: '',
            nome: '',
            ocultar: false,
            foto: null
        });
        const file = ref(null);
        const lista = ref([]);
        const albums = ref([]);
        const msg = ref({ text: '', type: '' });
        const apiUrl = window.location.origin + window.location.pathname; 

        onMounted(() => {
            carregarAlbums();
            buscarTodos();
        });

        async function carregarAlbums() {
             try {
                // Existing used '/server/albumFotos'. Assuming it calls controllerAlbumFotos.
                const response = await fetch('/server/albumFotos', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const data = await response.json();
                if(data.elements) albums.value = data.elements;
            } catch (e) {
                console.error(e);
            }
        }
        
        function getAlbumName(id) {
            const a = albums.value.find(x => x.id == id);
            return a ? a.nome : id;
        }

        function handleFile(event) {
            file.value = event.target.files[0];
        }

        async function enviarCadastro() {
             let imagem = null;
            if (file.value) {
                imagem = {
                    namefile: file.value.name,
                    data: await fileToBase64(file.value)
                };
            }

            const payload = {
                ...form.value,
                foto: imagem
            };
            if(!payload.id) delete payload.id;

            try {
                const method = form.value.id ? 'PUT' : 'POST';
                const response = await fetch(apiUrl, {
                    method: method,
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                }).then(res => res.json());

                if (response.success || response.mensagem_sucesso || response.id) {
                    msg.value = { text: 'Salvo com sucesso!', type: 'success' };
                    resetForm();
                    buscarTodos();
                } else {
                     msg.value = { text: response.error || response.mensagem_erro || 'Erro ao salvar', type: 'danger' };
                }
            } catch (error) {
                msg.value = { text: 'Erro na requisição: ' + error.message, type: 'danger' };
            }
        }

        async function buscarTodos() {
             try {
                const response = await fetch(`${apiUrl}?all=true`, {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const data = await response.json();
                if(data.elements) lista.value = data.elements;
                else if(Array.isArray(data)) lista.value = data;
            } catch (e) {
                console.error(e);
            }
        }
        
        async function buscarPorId() {
            if(!form.value.id) return;
             try {
                const response = await fetch(`${apiUrl}?id=${form.value.id}`, {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const data = await response.json();
                const item = data.site && data.site[0] ? data.site[0] : (data.elements?.[0] || data);
                if(item) editar(item);
            } catch (e) {
                console.error(e);
            }
        }

        async function excluir() {
            if(!confirm("Deseja excluir?")) return;
            try {
                 const response = await fetch(apiUrl, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: form.value.id })
                }).then(res => res.json());
                
                if(response.success || !response.error) {
                    msg.value = { text: 'Excluído com sucesso', type: 'success' };
                    resetForm();
                    buscarTodos();
                }
            } catch(e) {
                 msg.value = { text: 'Erro ao excluir', type: 'danger' };
            }
        }

        async function excluirItem(item) {
            if(!confirm("Deseja excluir?")) return;
            try {
                 const response = await fetch(apiUrl, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: item.id })
                }).then(res => res.json());
                
                if(response.success || !response.error) {
                    msg.value = { text: 'Excluído com sucesso', type: 'success' };
                    if(form.value.id == item.id) resetForm();
                    buscarTodos();
                } else {
                     msg.value = { text: response.error || response.mensagem_erro || 'Erro ao excluir', type: 'danger' };
                }
            } catch(e) {
                 msg.value = { text: 'Erro ao excluir ' + e.message, type: 'danger' };
            }
        }

        function editar(item) {
            form.value = { ...item };
            form.value.ocultar = (item.ocultar == 1 || item.ocultar == true);
            window.scrollTo(0,0);
        }

        function resetForm() {
            form.value = {
                id: '',
                id_album: '',
                nome: '',
                ocultar: false,
                foto: null
            };
            file.value = null;
        }

        return {
            form,
            file,
            lista,
            albums,
            msg,
            getAlbumName,
            handleFile,
            enviarCadastro,
            buscarTodos,
            buscarPorId,
            excluir,
            excluirItem,
            editar,
            resetForm
        };
    }
}).mount('#app');
</script>
<?php include($_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/foot.php');?>
