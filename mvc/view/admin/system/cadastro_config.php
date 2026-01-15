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
    <h3 class="mb-4">Cadastro de Configurações</h3>

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

            <!-- Logo -->
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-camera"></i></span>
                    <input type="file" class="form-control" @change="onFileChange($event, 'logo')">
                </div>
            </div>

            <!-- Logo Mobile -->
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-camera"></i></span>
                    <input type="file" class="form-control" @change="onFileChange($event, 'logoMobile')">
                </div>
            </div>

            <!-- Mensagem -->
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-bars"></i></span>
                    <input type="text" class="form-control" v-model="formData.contactMessage" placeholder="Mensagem de contato">
                </div>
            </div>

            <!-- Ocultar -->
            <div class="col-md-4 d-flex align-items-center">
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" v-model="formData.hidden" id="hidden">
                    <label class="form-check-label" for="hidden">Ocultar</label>
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

    <!-- Tabela -->
    <table v-if="configList.length" class="table table-striped table-bordered mt-4">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Logo</th>
                <th>Logo Mobile</th>
                <th>Mensagem</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in configList" :key="item.id">
                <td>{{ item.id }}</td>
                <td>
                    <a v-if="item.logo" :href="`/uploads/logo/${item.logo}`" target="_blank" class="btn btn-sm btn-info">
                        Ver
                    </a>
                </td>
                <td>
                    <a v-if="item.logo_mobile" :href="`/uploads/logo_mobile/${item.logo_mobile}`" target="_blank" class="btn btn-sm btn-info">
                        Ver
                    </a>
                </td>
                <td>{{ item.mensagem_contato }}</td>
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
            contactMessage: '',
            hidden: false
        });

        const fileInputs = {
            logo: ref(null),
            logoMobile: ref(null)
        };

        const configList = ref([]);
        const alertState = ref({ message: '', type: '' });

        onMounted(fetchAll);

        const onFileChange = (event, field) => {
            fileInputs[field].value = event.target.files[0];
        };

        const submitForm = async () => {
            const payload = {
                id: formData.value.id,
                mensagem_contato: formData.value.contactMessage,
                ocultar: formData.value.hidden
            };

            if (fileInputs.logo.value) {
                payload.logo = {
                    namefile: fileInputs.logo.value.name,
                    data: await fileToBase64(fileInputs.logo.value)
                };
            }

            if (fileInputs.logoMobile.value) {
                payload.logo_mobile = {
                    namefile: fileInputs.logoMobile.value.name,
                    data: await fileToBase64(fileInputs.logoMobile.value)
                };
            }

            if (!payload.id) delete payload.id;

            const method = formData.value.id ? 'PUT' : 'POST';

            const response = await fetch(apiBaseUrl, {
                method,
                headers: {
                    Authorization: `Bearer ${token}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

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
            const response = await fetch(`${apiBaseUrl}?all=true`, {
                headers: { Authorization: `Bearer ${token}` }
            });
            const data = await response.json();
            configList.value = data.elements || data || [];
        };

        const fetchById = async () => {
            const response = await fetch(`${apiBaseUrl}?id=${formData.value.id}`, {
                headers: { Authorization: `Bearer ${token}` }
            });
            const data = await response.json();
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
                contactMessage: item.mensagem_contato,
                hidden: !!item.ocultar
            };
            window.scrollTo(0, 0);
        };

        const resetForm = () => {
            formData.value = { id: '', contactMessage: '', hidden: false };
            fileInputs.logo.value = null;
            fileInputs.logoMobile.value = null;
        };

        return {
            formData,
            configList,
            alertState,
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
