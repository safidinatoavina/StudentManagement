<template>
    <div>

        <div class="my-5 ms-3">
            <button @click="$router.push({name:'add-admin'})"
            class="btn btn-primary">
                Ajout Admin
            </button>
        </div>
        <div class="my-4 mx-2" v-if="Admin.getAdmins.length">
            <DataTable filterDisplay="menu"
            :filters="filters1"
            :paginator="true" :rows="4" :value="Admin.getAdmins" responsiveLayout="scroll" 
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            :rowsPerPageOptions="[4,10,20,50]"
            >
                <template #header>
                    <div class="d-flex justify-content-between">
                        <span class="p-input-icon-left">
                            <i class="pi pi-search" />
                            <InputText v-model="filters1['global'].value" placeholder="Keyword Search" />
                        </span>
                        <label class="text-primary" style="cursor:pointer" >

                            <label for="import-user" class="btn btn-primary ms-1">
                                <IconUpload class="me-1"/> Importer
                            </label>

                            <input id="import-user" type="file" ref="uploaduser" @change="importUser" style="display:none" accept="text/csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />

                            <a class="dropdown-toggle template text-primary ms-2" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                             <button class="btn btn-primary">Template</button>
                            </a>
                            <ul class="dropdown-menu">
                                <li @click="DataIO.getProfHead('xlsx')">
                                    <a class="dropdown-item" href="#">En tete Excel</a>
                                </li>
                                <li @click="DataIO.getProfHead('csv')" >
                                    <a class="dropdown-item" href="#">En tete Excel CSV</a>
                                </li>
                            </ul>

                            <Spinner class-color="text-primary" v-if="DataIO.getPending.import_user"/>

                        </label>
                    </div>
                </template>

                <Column :sortable="true" field="id" header="ID"></Column>

                <Column :sortable="true" field="login" header="Login"></Column>

                <Column :sortable="true" header="Photo">
                    <template #body="{data}">
                        <div class="cont-image">
                            <div class="col-12">
                                <img  
                                :src="data.personne?.photo?.url||'/img/user.jpg'"
                                 >
                            </div>
                        </div>
                    </template>
                </Column>

                <Column :sortable="true" field="personne.nom" header="Nom">
                    <template #body="{data}">
                    {{ data.personne.nom }}
                    </template>
                </Column>
                <Column :sortable="true" field="personne.prenom" header="Prénom"></Column>
                <Column :sortable="true" field="personne.cin" header="Cin"></Column>
                <Column :sortable="true" header="Role">
                <template #body="{data}">
                    <div v-if="!data.roles.length">
                        <span class="badge text-bg-secondary">
                            Aucun
                        </span>
                    </div>
                    <div v-else>
                        <span v-for="user_role in data.roles" :key="`role_user_${user_role.id+data.id}`"
                        class="badge text-bg-primary m-1">
                        
                        {{ user_role.type }} 

                        <Spinner v-if="Admin.getPending.delete_role[user_role.id] && Admin.getPending.delete_role[data.id] && Admin.getPending.delete_role[data.personne.cin]"/>
                        <svg v-else
                        @click="Admin.deleteRole(data.id,user_role.id,data.personne.cin)"
                        class="bi bi-x-square-fill border border-2  text-danger rounded-circle p-0 ms-2"
                        style="top:0px;cursor:pointer"
                        xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z"/>
                        </svg>


                        </span>

                    </div>
                </template>
                </Column>
                <Column :sortable="true" header="Actions">
                    <template #body="{data}">
                        <div class="d-flex">
                            <div @click="$router.push({
                                                        name:'edit-admin',
                                                        params:{id:data.id}
                                                    })"
                            class="btn btn-sm me-2 p-1 text-primary">
                                <i class="fa fa-edit fs-5 "></i>
                            </div>
                            <Modal :id-modal="`modal-id-${data.id}`">
                                <template #btn>
                                    <div class="btn btn-sm me-2 p-1 text-danger"
                                    ><i class="fa fa-trash fs-5"></i>
                                    </div>
                                </template>
                                <template #body>
                                    <div class="pt-5 pb-2 text-center">
                                        <div>
                                            <icon-warning-triangle class="text-warning" />
                                        <span class="ms-2">Voulez-vous vraiment effacer définitivement</span> 
                                        </div>
                                        <div>
                                        <span class="me-1 text-uppercase">{{ data.personne.nom }}</span> 
                                        <span>{{ data.personne.prenom }} ?</span>
                                        </div>
                                        <div class="mt-3 d-flex justify-content-end">
                                            <button :id="`close-modal-${data.id}`" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal">Annuler</button>
                                            <button @click="Admin.deleteAdmin(data.id)"
                                            class="btn btn-primary btn-sm me-2"
                                            >
                                            <span>Confirmer</span>

                                            <div v-show="Admin.getPending.delete_pending"
                                            class="ms-2 spinner-border spinner-border-sm text-light fw-bolder" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>

                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </Modal>
                        </div>
                    </template>
                </Column>

            </DataTable>
        </div>
        <div v-else>
            <div v-if="Admin.getPending.list_admin" class="text-center my-5">
                <div>
                    <Loading />
                </div>
            </div>
            <h1 v-else 
            class="text-primary m-5 text-center">Liste vide</h1>
        </div>
    </div>

</template>

<script setup>
import Spinner from '../../components/annimate/Spinner.vue'
import IconUpload from '../../components/icons/IconUpload.vue'
import Loading from '../../components/annimate/Loading.vue'
import Toast from 'primevue/toast';
import IconDelete from '../../components/icons/IconDelete.vue'
import IconWarningTriangle from '../../components/icons/IconWarningTriangle.vue'
import Modal from '../../components/modal/Modal.vue'
import { ref, onMounted } from 'vue';
import { FilterMatchMode } from 'primevue/api'
import { useToast } from "primevue/usetoast";
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputText from 'primevue/inputtext'
import { useAdmin } from '@/stores/admin'
import { useDataIO } from '@/stores/data_io'
import axios from 'axios'


const filters1 = ref({
        'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
    });


const Admin=useAdmin()
const toast=useToast()
const DataIO=useDataIO()

const uploaduser=ref('')


const printToastCreated=()=>{
    if(Admin.printToast.created===true){

        Admin.setPrintToast('created',false)

        setTimeout(()=>{
            toast.add({
            severity:'success', summary: 'Ajout Admin', detail:'Ajout Admin succès', life: 3000
        })

        },500)

    }
}



const importUser=(event)=>{


    const file = event.target.files[0]
    if (!file) {
        alert('fichier invalide')
        return
    }

    var formData = new FormData()
    formData.append('file',file)

    DataIO.importUser(formData)

    uploaduser.value.value=''

}

 onMounted(()=>{
    printToastCreated()
 })


</script>   


<style  scoped>
 
 div.cont-image{
    height: 100px;
    width: 100px;
 }

</style>
