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
    
    $titulo = $data['titulo'] ?? '';
    $subtitulo = $data['subtitulo'] ?? '';
    $conteudo = $data['conteudo'] ?? ''; // conteudo_anuncios_anexo
    $fonte = $data['fonte'] ?? '';
    $ocultar = $data['ocultar'] ?? 0;
    $id_anuncio = $data['id_anuncio'] ?? null;
    $id = $data['id'] ?? null;

    $pathArquivo = null;

    // 📷 Upload Anexo/Arquivo
    if (isset($data['arquivo']) && !empty($data['arquivo'])) {
        $namefile = $data['arquivo']['namefile'];
        $base64   = $data['arquivo']['data'];

        $conteudoArquivo = base64_decode($base64);
        $pathArquivo = 'uploads/anuncios_anexos/' . uniqid() . '_' . $namefile;
        
        if (!is_dir('uploads/anuncios_anexos/')) {
           mkdir('uploads/anuncios_anexos/', 0777, true);
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
        <h1>CADASTRO DE ANEXO ANÚNCIOS</h1>
        <br>
        <div class="row">
            <div class="sm-12">
                <!-- Dropdown de anuncios idealmente seria carregado aqui via outra API -->
                <input v-model="id_anuncio" class="form-control mb-2" placeholder="ID Anúncio (Temporário)" />
                
                <input v-model="titulo" class="form-control mb-2" placeholder="Título" />
                <input v-model="subtitulo" class="form-control mb-2" placeholder="Subtítulo" />
                <textarea v-model="conteudo" class="form-control mb-2" placeholder="Conteúdo"></textarea>
                <input v-model="fonte" class="form-control mb-2" placeholder="Fonte" />
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" v-model="ocultar" id="ocultar">
                    <label class="form-check-label" for="ocultar">Ocultar</label>
                </div>

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
            const subtitulo = ref('');
            const conteudo = ref('');
            const fonte = ref('');
            const ocultar = ref(false);
            const id_anuncio = ref('');
            const message = ref('');
            const file = ref(null);

            function onFileChange(e) {
                file.value = e.target.files[0];
            }

            async function enviarCadastro() {
                let arquivo = null;

                if (file.value) {
                    arquivo = {
                        namefile: file.value.name,
                        data: await fileToBase64(file.value)
                    };
                }

                const payload = {
                    titulo: titulo.value,
                    subtitulo: subtitulo.value,
                    conteudo: conteudo.value,
                    fonte: fonte.value,
                    ocultar: ocultar.value,
                    id_anuncio: id_anuncio.value,
                    arquivo: arquivo
                };

                const response = await postCadastro(
                    '/mvc/view/admin/system/cadastro_anuncio_anexos.php', 
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
                subtitulo,
                conteudo,
                fonte,
                ocultar,
                id_anuncio,
                file,
                message,
                onFileChange,
                enviarCadastro
            };
        }
    }).mount('#app');
</script>
<?php include($_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/foot.php');?>