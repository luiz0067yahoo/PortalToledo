<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
include($_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/top.php');
?>


<script type="importmap">
{
  "imports": {
    "vue": "https://unpkg.com/vue@3/dist/vue.esm-browser.js"
  }
}
</script>

<div id="app" class="container">
    <h1>CADASTRO DE ANEXO DE ANÚNCIOS</h1>
    <br>

    <div id="loader" v-if="loading">
        <div class="loader"></div>
    </div>

    <form @submit.prevent>
        <div class="row">
            <div class="col-sm-12">

                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-bullhorn"></i></span>
                    <input v-model="elementCurrent.id_anuncio"
                           class="form-control"
                           placeholder="ID do Anúncio">
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-heading"></i></span>
                    <input v-model="elementCurrent.titulo"
                           class="form-control"
                           placeholder="Título">
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-align-left"></i></span>
                    <input v-model="elementCurrent.subtitulo"
                           class="form-control"
                           placeholder="Subtítulo">
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-file-alt"></i></span>
                    <textarea v-model="elementCurrent.conteudo"
                              class="form-control"
                              placeholder="Conteúdo"></textarea>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-link"></i></span>
                    <input v-model="elementCurrent.fonte"
                           class="form-control"
                           placeholder="Fonte">
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-eye-slash"></i></span>
                    <div class="form-control">
                        <input type="checkbox" v-model="elementCurrent.ocultar" id="ocultar">
                        <label for="ocultar"> Ocultar</label>
                    </div>
                </div>

                <!-- Preview do arquivo -->
                <div v-if="temArquivoParaMostrar" class="mb-3">
                    <span class="badge bg-secondary">{{ arquivoNome }}</span>
                    <button class="btn btn-danger btn-sm ms-2" @click="removeFile">
                        <i class="fa fa-times"></i>
                    </button>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-paperclip"></i></span>
                    <input type="file"
                           class="form-control"
                           @change="handleFile">
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-success" @click="saveElement">
                        <i class="fa fa-save"></i> Salvar
                    </button>
                    <button class="btn btn-danger" @click="cancelAction">
                        <i class="fa fa-ban"></i> Cancelar
                    </button>
                </div>

            </div>
        </div>
    </form>

    <div v-if="successMsg" class="alert alert-success mt-3">{{ successMsg }}</div>
    <div v-if="errorMsg" class="alert alert-danger mt-3">{{ errorMsg }}</div>
</div>

<script type="module">
import { createApp, ref, computed } from 'vue';
import { fileToBase64 } from '/assets/js/utils/base64.js';

createApp({
    setup() {
        const loading = ref(false);
        const successMsg = ref('');
        const errorMsg = ref('');

        const elementCurrent = ref({
            titulo: '',
            subtitulo: '',
            conteudo: '',
            fonte: '',
            id_anuncio: '',
            ocultar: false
        });

        const arquivoBase64 = ref('');
        const arquivoNome = ref('');

        const temArquivoParaMostrar = computed(() => !!arquivoNome.value);

        const getToken = () => {
            const data = localStorage.getItem('portalToledoData');
            return data ? JSON.parse(data).token : '';
        };

        const handleFile = async (e) => {
            const file = e.target.files[0];
            if (!file) return;

            arquivoNome.value = file.name;
            const base64 = await fileToBase64(file);
            arquivoBase64.value = base64.split(',')[1];
        };

        const removeFile = () => {
            arquivoNome.value = '';
            arquivoBase64.value = '';
        };

        const saveElement = async () => {
            loading.value = true;
            successMsg.value = '';
            errorMsg.value = '';

            const payload = {
                ...elementCurrent.value,
                arquivo: arquivoBase64.value ? {
                    namefile: arquivoNome.value,
                    data: arquivoBase64.value
                } : null
            };

            try {
                const response = await fetch(location.href, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + getToken()
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (data.success) {
                    successMsg.value = data.message;
                    prepareNew();
                } else {
                    errorMsg.value = data.error || 'Erro ao salvar';
                }
            } catch (e) {
                errorMsg.value = 'Erro de conexão';
            } finally {
                loading.value = false;
            }
        };

        const prepareNew = () => {
            elementCurrent.value = {
                titulo: '',
                subtitulo: '',
                conteudo: '',
                fonte: '',
                id_anuncio: '',
                ocultar: false
            };
            removeFile();
        };

        const cancelAction = () => {
            prepareNew();
        };

        return {
            loading,
            elementCurrent,
            arquivoNome,
            temArquivoParaMostrar,
            handleFile,
            removeFile,
            saveElement,
            cancelAction,
            successMsg,
            errorMsg
        };
    }
}).mount('#app');
</script>

<?php include($_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/foot.php'); ?>
