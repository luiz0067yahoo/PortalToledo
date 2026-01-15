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

<div id="app" class="container mt-4">
    <h3 class="mb-4">Cadastro de Fotos de Anúncios</h3>

    <form @submit.prevent="submitForm">
        <div class="row g-3">

            <!-- ID -->
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-key"></i></span>
                    <input type="number" class="form-control" v-model="formData.id" placeholder="Código" disabled>
                    <button v-if="formData.id" type="button" class="btn btn-primary" @click="fetchById">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>

            <!-- Anúncio -->
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-link"></i></span>
                    <select class="form-select" v-model="formData.adId">
                        <option value="">Selecione o anúncio</option>
                        <option v-for="ad in ads" :key="ad.id" :value="ad.id">
                            {{ ad.nome }}
                        </option>
                    </select>
                </div>
            </div>

            <!-- Foto -->
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-camera"></i></span>
                    <input type="file" class="form-control" @change="onFileChange">
                </div>
            </div>

            <!-- Nome -->
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-bars"></i></span>
                    <input type="text" class="form-control" v-model="formData.name" placeholder="Nome da foto">
                </div>
            </div>

            <!-- Ocultar -->
            <div class="col-md-12">
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" v-model="formData.hidden" id="hidden">
                    <label class="form-check-label" for="hidden">Ocultar foto</label>
                </div>
            </div>

            <!-- Botões -->
            <div class="col-12">
                <div class="btn-group mt-3">
                    <button type="button" class="btn btn-dark" @click="resetForm">
                        <i class="fa fa-file"></i> Novo
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Salvar
                    </button>
                    <button type="button" class="btn btn-primary" @click="fetchAll">
                        <i class="fa fa-search"></i> Buscar
                    </button>
                    <button v-if="formData.id" type="button" class="btn btn-danger" @click="deleteCurrent">
                        <i class="fa fa-trash"></i> Excluir
                    </button>
                </div>
            </div>

        </div>
    </form>

    <!-- Alert -->
    <div v-if="alertState.message" class="alert mt-4" :class="'alert-' + alertState.type">
        {{ alertState.message }}
    </div>

    <!-- Lista -->
    <table v-if="photoList.length" class="table table-striped table-bordered mt-4">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Anúncio</th>
                <th>Nome</th>
                <th>Foto</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in photoList" :key="item.id">
                <td>{{ item.id }}</td>
                <td>{{ getAdName(item.id_anuncios) }}</td>
                <td>{{ item.nome }}</td>
                <td>
                    <a v-if="item.foto"
                       :href="`/uploads/anuncio_fotos/${item.foto}`"
                       target="_blank"
                       class="btn btn-sm btn-info">
                        Ver
                    </a>
                </td>
                <td>
                    <button class="btn btn-sm btn-primary me-1" @click="editItem(item)">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" @click="deleteItem(item)">
                        <i class="fa fa-times"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script type="module">
import { createApp, ref, onMounted } from 'vue';
import { fileToBase64 } from '/assets/js/utils/base64.js';

createApp({
    setup() {
        const token = JSON.parse(localStorage.getItem('portalToledoData') || '{}').token || '';
        const apiBaseUrl = window.location.href;

        const formData = ref({
            id: '',
            adId: '',
            name: '',
            hidden: false,
            foto: null
        });

        const selectedFile = ref(null);
        const photoList = ref([]);
        const ads = ref([]);
        const alertState = ref({ message: '', type: '' });

        onMounted(() => {
            loadAds();
            fetchAll();
        });

        const loadAds = async () => {
            const res = await fetch('/server/anuncios', {
                headers: { Authorization: `Bearer ${token}` }
            });
            const data = await res.json();
            ads.value = data.elements || [];
        };

        const getAdName = id =>
            ads.value.find(ad => ad.id == id)?.nome || id;

        const onFileChange = e => selectedFile.value = e.target.files[0];

        const submitForm = async () => {
            let imagePayload = null;

            if (selectedFile.value) {
                imagePayload = {
                    namefile: selectedFile.value.name,
                    data: await fileToBase64(selectedFile.value)
                };
            }

            const payload = {
                id: formData.value.id,
                id_anuncios: formData.value.adId,
                nome: formData.value.name,
                ocultar: formData.value.hidden,
                foto: imagePayload
            };

            if (!payload.id) delete payload.id;

            const method = formData.value.id ? 'PUT' : 'POST';

            const res = await fetch(apiBaseUrl, {
                method,
                headers: {
                    Authorization: `Bearer ${token}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const data = await res.json();

            alertState.value = {
                message: data.error ? 'Erro ao salvar' : 'Salvo com sucesso',
                type: data.error ? 'danger' : 'success'
            };

            if (!data.error) {
                resetForm();
                fetchAll();
            }
        };

        const fetchAll = async () => {
            const res = await fetch(apiBaseUrl, {
                headers: { Authorization: `Bearer ${token}` }
            });
            const data = await res.json();
            photoList.value = data.elements || data || [];
        };

        const fetchById = async () => {
            const res = await fetch(`${apiBaseUrl}?id=${formData.value.id}`, {
                headers: { Authorization: `Bearer ${token}` }
            });
            const data = await res.json();
            editItem(data.elements?.[0] || data);
        };

        const deleteCurrent = () => deleteItem(formData.value);

        const deleteItem = async item => {
            if (!confirm('Deseja excluir?')) return;

            await fetch(apiBaseUrl, {
                method: 'DELETE',
                headers: {
                    Authorization: `Bearer ${token}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: item.id })
            });

            resetForm();
            fetchAll();
        };

        const editItem = item => {
            formData.value = {
                id: item.id,
                adId: item.id_anuncios,
                name: item.nome,
                hidden: !!item.ocultar,
                foto: null
            };
            window.scrollTo(0, 0);
        };

        const resetForm = () => {
            formData.value = { id: '', adId: '', name: '', hidden: false, foto: null };
            selectedFile.value = null;
        };

        return {
            formData,
            ads,
            photoList,
            alertState,
            getAdName,
            onFileChange,
            submitForm,
            fetchAll,
            fetchById,
            deleteCurrent,
            deleteItem,
            editItem,
            resetForm
        };
    }
}).mount('#app');
</script>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/mvc/view/admin/templates/foot.php'); ?>
