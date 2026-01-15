<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/library/functions.php');
include($_SERVER['DOCUMENT_ROOT'] . '/mvc/view/admin/templates/top.php');
?>
<script type="importmap">
{
  "imports": {
    "vue": "https://unpkg.com/vue@3/dist/vue.esm-browser.js"
  }
}
</script>

<div id="app" class="container">
    <h1>CADASTRO DE ANEXO NOTÍCIAS</h1>
    <br>

    <div class="row">
        <div class="col-sm-12">

            <form @submit.prevent="saveAttachment">

                <!-- ID -->
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-key"></i></span>
                    <input class="form-control" type="number" v-model="formData.id" placeholder="Código" disabled>
                    <button v-if="formData.id" type="button" class="btn btn-primary" @click="fetchAttachmentById">
                        <i class="fa fa-search"></i>
                    </button>
                </div>

                <!-- Notícia -->
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-link"></i></span>
                    <select class="form-control" v-model="formData.id_noticia">
                        <option value="">Selecione uma Notícia</option>
                        <option v-for="news in newsList" :key="news.id" :value="news.id">
                            {{ news.titulo }}
                        </option>
                    </select>
                </div>

                <!-- Foto -->
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-camera"></i></span>
                    <input type="file" class="form-control" @change="handleMainImage">
                </div>

                <!-- Título -->
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-bars"></i></span>
                    <input class="form-control" type="text" v-model="formData.titulo" placeholder="Título">
                </div>

                <!-- Subtítulo -->
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-bars"></i></span>
                    <input class="form-control" type="text" v-model="formData.subtitulo" placeholder="Subtítulo">
                </div>

                <!-- Conteúdo -->
                <div class="mb-3">
                    <textarea class="form-control" rows="5" v-model="formData.conteudo_noticia" placeholder="Conteúdo"></textarea>
                </div>

                <!-- Fonte -->
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-bars"></i></span>
                    <input class="form-control" type="text" v-model="formData.fonte" placeholder="Fonte">
                </div>

                <!-- Ocultar -->
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-eye-slash"></i></span>
                    <div class="form-control">
                        <input type="checkbox" id="ocultar" v-model="formData.ocultar">
                        <label for="ocultar"> Ocultar</label>
                    </div>
                </div>

                <!-- Botões -->
                <div class="btn-group">
                    <button type="button" class="btn btn-dark" @click="resetFormData">
                        <i class="fa fa-sticky-note"></i> Novo
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-floppy-o"></i> Salvar
                    </button>
                    <button type="button" class="btn btn-primary" @click="fetchAllAttachments">
                        <i class="fa fa-search"></i> Buscar
                    </button>
                    <button v-if="formData.id" type="button" class="btn btn-danger" @click="deleteAttachment">
                        <i class="fa fa-times"></i> Excluir
                    </button>
                </div>
            </form>

            <!-- ALERT -->
            <div v-if="alertMessage.text" class="mt-3" :class="'alert alert-' + alertMessage.type">
                {{ alertMessage.text }}
            </div>

            <!-- TABELA -->
            <table v-if="attachmentList.length" class="table table-striped mt-4">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Foto</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in attachmentList" :key="item.id">
                        <td>{{ item.id }}</td>
                        <td>{{ item.titulo }}</td>
                        <td>
                            <a v-if="item.foto_principal" class="btn btn-sm btn-info" target="_blank"
                               :href="'/uploads/noticias_anexos/' + item.foto_principal">
                                Ver
                            </a>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary" @click="editAttachment(item)">
                                <i class="fa fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>

<script type="module">
import { createApp, ref, onMounted } from 'vue'
import { fileToBase64 } from '/assets/js/utils/base64.js'

createApp({
    setup() {
        const tokenData = localStorage.getItem('portalToledoData')
        const token = tokenData ? JSON.parse(tokenData).token : ''
        const apiEndpoint = window.location.origin + window.location.pathname

        const formData = ref({
            id: '',
            id_noticia: '',
            titulo: '',
            subtitulo: '',
            conteudo_noticia: '',
            fonte: '',
            ocultar: false,
            foto_principal: null
        })

        const mainImageFile = ref(null)
        const attachmentList = ref([])
        const newsList = ref([])
        const alertMessage = ref({ text: '', type: '' })

        onMounted(() => {
            fetchNews()
            fetchAllAttachments()
        })

        function handleMainImage(event) {
            mainImageFile.value = event.target.files[0]
        }

        async function fetchNews() {
            const res = await fetch('/server/noticias', {
                headers: { Authorization: `Bearer ${token}` }
            })
            const data = await res.json()
            newsList.value = data.elements || []
        }

        async function fetchAllAttachments() {
            const res = await fetch(`${apiEndpoint}?all=true`, {
                headers: { Authorization: `Bearer ${token}` }
            })
            const data = await res.json()
            attachmentList.value = data.elements || data || []
        }

        async function fetchAttachmentById() {
            if (!formData.value.id) return
            const res = await fetch(`${apiEndpoint}?id=${formData.value.id}`, {
                headers: { Authorization: `Bearer ${token}` }
            })
            const data = await res.json()
            editAttachment(data.elements?.[0] || data)
        }

        async function saveAttachment() {
            let imagePayload = null

            if (mainImageFile.value) {
                imagePayload = {
                    namefile: mainImageFile.value.name,
                    data: await fileToBase64(mainImageFile.value)
                }
            }

            const payload = { ...formData.value, foto_principal: imagePayload }
            if (!payload.id) delete payload.id

            const res = await fetch(apiEndpoint, {
                method: formData.value.id ? 'PUT' : 'POST',
                headers: {
                    Authorization: `Bearer ${token}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            }).then(r => r.json())

            if (res.success || res.id) {
                alertMessage.value = { text: 'Salvo com sucesso!', type: 'success' }
                resetFormData()
                fetchAllAttachments()
            } else {
                alertMessage.value = { text: res.error || 'Erro ao salvar', type: 'danger' }
            }
        }

        async function deleteAttachment() {
            if (!confirm('Deseja excluir?')) return
            await fetch(apiEndpoint, {
                method: 'DELETE',
                headers: {
                    Authorization: `Bearer ${token}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: formData.value.id })
            })
            alertMessage.value = { text: 'Excluído com sucesso', type: 'success' }
            resetFormData()
            fetchAllAttachments()
        }

        function editAttachment(item) {
            formData.value = { ...item, ocultar: item.ocultar == 1 }
            window.scrollTo(0, 0)
        }

        function resetFormData() {
            formData.value = {
                id: '',
                id_noticia: '',
                titulo: '',
                subtitulo: '',
                conteudo_noticia: '',
                fonte: '',
                ocultar: false,
                foto_principal: null
            }
            mainImageFile.value = null
        }

        return {
            formData,
            attachmentList,
            newsList,
            alertMessage,
            handleMainImage,
            saveAttachment,
            fetchAllAttachments,
            fetchAttachmentById,
            deleteAttachment,
            editAttachment,
            resetFormData
        }
    }
}).mount('#app')
</script>

<?php include($_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/foot.php'); ?>
