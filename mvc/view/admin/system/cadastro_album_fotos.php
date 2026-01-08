<?php
header('Access-Control-Allow-Origin: *');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    // 🔐 Authorization Bearer
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Authorization não enviado']);
        exit;
    }

    $token = trim(str_replace('Bearer', '', $headers['Authorization']));

    // 👉 validarToken($token);

    // 📥 JSON
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) {
        http_response_code(400);
        echo json_encode(['error' => 'JSON inválido']);
        exit;
    }

    // 📌 Campos comuns
    require_once($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
    
    $nome = $data['nome'] ?? '';
    // Mapeando 'titulo' para 'nome'
    $titulo = $data['titulo'] ?? $nome; 

    $ocultar = $data['ocultar'] ?? 0;
    $id_menu = $data['id_menu'] ?? null;
    $id = $data['id'] ?? null;

    $pathArquivo = null;

    // 📷 Upload Imagem
    if (isset($data['imagem']) && !empty($data['imagem'])) {
        $namefile = $data['imagem']['namefile'];
        $base64   = $data['imagem']['data'];

        $conteudoArquivo = base64_decode($base64);
        $pathArquivo = 'uploads/album_fotos/' . uniqid() . '_' . $namefile;
        
        if (!is_dir('uploads/album_fotos/')) {
           mkdir('uploads/album_fotos/', 0777, true);
        }

        file_put_contents($pathArquivo, $conteudoArquivo);
        $pathArquivo = basename($pathArquivo);
    }

    // 💾 salvarNoBanco(...);

    echo json_encode([
        'success' => true,
        'message' => 'Cadastro realizado com sucesso'
    ]);
    exit;
}
include($_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/top.php');
?>
<div id="app">
    <div class="container">
        <h1>CADASTRO DE ALBUNS DE FOTOS</h1>
        <br>
        <div class="row">
            <div class="sm-12">
                <input v-model="id_menu" class="form-control mb-2" placeholder="ID Menu" />
                <input v-model="titulo" class="form-control mb-2" placeholder="Nome do Album" />
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" v-model="ocultar" id="ocultar">
                    <label class="form-check-label" for="ocultar">Ocultar</label>
                </div>

                <label>Capa do Album (Opcional):</label>
                <input type="file" @change="onFileChange" class="form-control mb-2" />
                <button @click="enviarCadastro" class="btn btn-success">Salvar</button>
            </div>
        </div>
        <br>
        <div v-if="message" class="alert alert-info">{{ message }}</div>
    </div>
</div>

<script type="module">
    import { createApp, ref } from 'https://unpkg.com/vue@3/dist/vue.esm-browser.js';
    import { postCadastro } from '/assets/js/services/api.js';
    import { fileToBase64 } from '/assets/js/utils/base64.js';

    createApp({
        setup() {
            const token = localStorage.getItem('portalToledoData') ? JSON.parse(localStorage.getItem('portalToledoData')).token : '';
            
            const titulo = ref('');
            const id_menu = ref('');
            const ocultar = ref(false);
            const message = ref('');
            const file = ref(null);

            function onFileChange(e) {
                file.value = e.target.files[0];
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
                    nome: titulo.value,
                    titulo: titulo.value,
                    id_menu: id_menu.value,
                    ocultar: ocultar.value,
                    imagem: imagem
                };

                const response = await postCadastro(
                    '/mvc/view/admin/system/cadastro_album_fotos.php', 
                    payload,
                    token
                );

                console.log(response);
                if(response.success) {
                    message.value = response.message;
                } else {
                    message.value = response.error || 'Erro ao salvar';
                }
            }

            return {
                titulo,
                id_menu,
                ocultar,
                file,
                message,
                onFileChange,
                enviarCadastro
            };
        }
    }).mount('#app');
</script>
<?php include($_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/foot.php');?>
