<template>
    <section>    

        <div class="my-3 mx-4">
            <router-link :to="{name: 'ajout-etudiant'}" 
            class="btn btn-primary btn-sm">
                Ajout etudiant
            </router-link>
        </div>
    
        <div class="mt-2 mx-1" v-if="!Etudiant.pendingGetEtudiant">


    
            <DataTable filterDisplay="menu"
                :filters="filters"
                :paginator="true" :rows="4" :value="Etudiant.getEtudiants" responsiveLayout="scroll" 
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                :rowsPerPageOptions="[4,10,20,50]"
            >
    
            <template #header>
                <div class="d-flex justify-content-between">
                        <span class="p-input-icon-left">
                            <i class="pi pi-search" />
                            <InputText v-model="filters['global'].value" placeholder="Keyword Search" />
                        </span>
                        <label class="text-primary" style="cursor:pointer" >

                            <label for="import-etudiant" class="btn btn-primary ms-1">
                                <IconUpload class="me-1"/> Importer
                            </label>
                            <input id="import-etudiant" type="file" ref="uploadEtudiant" @change="importEtudiant" style="display:none" accept="text/csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />

                            <a class="dropdown-toggle template text-primary ms-2" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                <button class="btn btn-primary">Template</button>
                            </a>
                            <ul class="dropdown-menu">
                                <li @click="DataIO.getEtudiantHead('xlsx')">
                                    <a class="dropdown-item" href="#">En tete Excel</a>
                                </li>
                                <li @click="DataIO.getEtudiantHead('csv')" >
                                    <a class="dropdown-item" href="#">En tete Excel CSV</a>
                                </li>
                            </ul>

                            <Spinner class-color="text-primary" v-if="DataIO.getPending.import_etudiant"/>

                            
                        </label>
                    </div>
            </template>
    
            <Column :sortable="true" field="id" header="ID"></Column>
            <Column :sortable="true" field="personne.nom" header="Nom"></Column>
            <Column :sortable="true" field="personne.prenom" header="Prénom(s)"></Column>
            <Column :sortable="true" filterField="parcour_list" header="Parcours">
            <template #body="{data}">
                <div>
                    <span v-for="historique in data.historiques" :key="`liste-historique-${historique.id}-parcour-${historique.parcour.id}`" class="badge text-bg-info mx-1">
                        {{ historique.parcour.abreviation }}
                    </span>
                </div>
            </template>
            </Column>

            <Column  header="Actions">
            <template #body="{data}">
                <div class="d-flex" >
                    <div @click="$router.push({name:'edit-etudiant',params:{id:data.id},})"
                    class="btn btn-sm me-2 p-1 text-primary">
                        <i class="fa fa-edit fs-5 "></i>
                    </div>
                    <div @click="Etudiant.deleteEtudiant(data.personne.id,printToastDelete)"
                    class="btn btn-sm me-2 p-1 text-danger">
                        <i  v-if="!Etudiant.pending_action[data.personne?.id]"
                        class="fa fa-trash fs-5 "></i>
                        <Spinner v-else classColor="text-danger" />
                    </div>
                </div>
            </template>
            </Column>
    
            </DataTable>
    
        </div>
        <div v-else class="my-5">
            <Loading />
        </div>
    </section>
</template>


<script setup>
import Loading from '../../components/annimate/Loading.vue'
import IconUpload from '../../components/icons/IconUpload.vue'

import Spinner from '../../components/annimate/Spinner.vue'
import Error from '../../components/section/error/Error.vue'
import { FilterMatchMode } from 'primevue/api'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete'
import InputText from 'primevue/inputtext'
import { useToast } from "primevue/usetoast";
import Toast from 'primevue/toast';
import { ref } from '@vue/reactivity'
import { useData } from '@/stores/data'
import { useEtudiant } from '@/stores/etudiant'
import { computed, onMounted } from '@vue/runtime-core'
import { useRoute,useRouter } from 'vue-router'
import { useDataIO } from '@/stores/data_io'


const Data=useData()  
const toast=useToast()
const route=useRoute()
const router=useRouter()

const DataIO=useDataIO()
const Etudiant=useEtudiant()  



const filters = ref({
        'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
    });

const uploadEtudiant=ref('')

const printToastCreated=()=>{
    if(Etudiant.printToast.created===true){

        Etudiant.setPrintToast('created',false)

        setTimeout(()=>{
            toast.add({
            severity:'success', summary: 'Ajout étudiant', detail:'Ajout étudiant succès', life: 3000
        })

        },500)

    }
}

const printToastDelete=(etudiant)=>{
    toast.add({
    severity:'success', summary: 'suppréssion étudiant', detail:`Suppréssion '${etudiant}' succès`, life: 3000
    })

}

const importEtudiant=(event)=>{


    const file = event.target.files[0]
    if (!file) {
        alert('fichier invalide')
        return
    }

    var formData = new FormData()
    formData.append('file',file)

    DataIO.importEtudiant(formData)

    uploadEtudiant.value.value=''

}

onMounted(()=>{

    printToastCreated()

})

</script>
