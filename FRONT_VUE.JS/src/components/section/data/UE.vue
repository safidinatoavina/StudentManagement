<template>
    <section>
        <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headinFive">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
                    <div class="text-primary">
                        Ajouter
                    </div>
                </button>
                </h2>
                <div id="flush-collapseFive" class="accordion-collapse collapse" aria-labelledby="flush-headingFive" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">

                        <div class="row mb-2">

                            <div class="col-6 col-lg-4 my-2">
                                <input v-model="postData.ue"
                                placeholder="Nom ue"
                                type="text" class="form-control">
                            </div>

                            <div class="col-4 col-lg-2 my-2">
                                <input v-model="postData.credit"
                                placeholder="Crédit"
                                type="number" class="form-control">
                            </div>

                            <div class="col-4 col-lg-2 my-2">
                                <AutoComplete v-model="semestre" 
                                :suggestions="suggestionSemestre" 
                                @complete="searchSemestre($event)" 
                                placeholder="Semestre"
                                :dropdown="true" optionLabel="semestre" forceSelection>
    
                                    <template #item="slotProps">
                                        <div class="country-item">
                                            <div class="ml-2"> {{ slotProps.item.semestre }} </div>
                                        </div>
                                    </template>
    
                                </AutoComplete>
                            </div>

                            <div class="col-4 col-lg-2 my-2">
                                <AutoComplete v-model="option" 
                                :suggestions="suggestionOptions" 
                                @complete="searchOption($event)" 
                                placeholder="Option"
                                :dropdown="true" optionLabel="valeur" forceSelection>
    
                                    <template #item="slotProps">
                                        <div class="country-item">
                                            <div class="ml-2"> {{ slotProps.item.valeur }} </div>
                                        </div>
                                    </template>
    
                                </AutoComplete>
                            </div>

     

                            <div class="col-6 col-lg-4 my-2" v-if="!is_responsable_parcour">
                                <AutoComplete v-model="parcours" 
                                    :multiple="false"
                                    :suggestions="suggestionParcour" 
                                    @complete="searchParcour($event)" 
                                    placeholder="Parcours"
                                    :dropdown="true" optionLabel="parcour" forceSelection>

                                    <template #item="slotProps">
                                        <div class="country-item">
                                            <div class="ml-2">
                                                {{slotProps.item.parcour}} {{slotProps.item.abreviation?'('+slotProps.item.abreviation+')':''}}
                                            </div>
                                        </div>
                                    </template>

                                </AutoComplete>
                            </div>



                            <Error :error="Data.error.ue" ></Error>


                        </div>

                        <button class="btn btn-primary btn-sm" @click="submite_ues">
                            <span>Enregistrer</span>
                            <Spinner v-if="Data.pendings.add_ue" />
                        </button>


                    </div>
                </div>
            </div>
        </div>


        <div class="mt-2">

            <DataTable filterDisplay="menu"
                :filters="filters"
                :paginator="true" :rows="4" :value="is_responsable_parcour?myParcoursUes:Data.ues" responsiveLayout="scroll" 
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                :rowsPerPageOptions="[4,10,20,50]"
            >

            <template #header>
                <div class="d-flex justify-content-between">
                    <span class="p-input-icon-left">
                        <i class="pi pi-search" />
                        <InputText v-model="filters['global'].value" placeholder="Keyword Search" />
                    </span>
                    <div v-if="false">
                            <label for="import-ue" class="btn btn-primary ms-1">
                                <IconUpload class="me-1"/> Importer
                            </label>
                            <input type="file" ref="uploadue" id="import-ue" @change="importUe" style="display:none" accept="text/csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                            <a class="dropdown-toggle template text-primary ms-2" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                             <button class="btn btn-primary">Template</button>
                            </a>
                            <ul class="dropdown-menu">
                                <li @click="DataIO.getUEHead('xlsx')">
                                    <a class="dropdown-item" href="#">En tete Excel</a>
                                </li>
                                <li @click="DataIO.getUEHead('csv')" >
                                    <a class="dropdown-item" href="#">En tete Excel CSV</a>
                                </li>
                            </ul>

                            <Spinner class-color="text-primary" v-if="DataIO.getPending.import_ue"/>
                    </div>
                </div>
            </template>

            <Column :sortable="true" field="id" header="ID"></Column>
            <Column :sortable="true" field="ue" header="UE"></Column>
            <Column :sortable="true" field="parcour.parcour" header="PARCOURS"></Column>
            <Column :sortable="true" field="semestre.semestre" header="SEMESTRE"></Column>
            <Column header="TYPE">
            <template #body="{data}">
                <div>
                    <span v-if="!data.option" class="badge text-bg-primary mx-1">OBLI</span>
                    <span v-else class="badge text-bg-secondary mx-1">FACULT</span>
                </div>
            </template>
            </Column>
            <Column :sortable="true" field="credit" header="CRÉDIT"></Column>
            <Column :sortable="true" header="ACTIONS" v-if="!is_responsable_parcour">
            <template #body="{data}">
                <div class="d-flex">
                    <Modal size-class="modal-lg" :id-modal="'modal-matiere-'+data.id" :is-center="false">
                        <template #btn>
                            <div class="btn btn-sm me-2 p-1 text-primary">
                                <i class="fa fa-edit fs-5 "></i>
                            </div>
                        </template>
                        <template #body>
                            <EditUe  :ue="data" />
                        </template>
                    </Modal>
                    <div :id="data.id" @click="Data.deleteUe(data.id,showSuccessDelete)" class="btn btn-sm me-2 p-1 text-danger">
                        <i v-if="!Data.pendings.del_ue || !Data.pendings.del_ue[data.id] " 
                        class="fa fa-trash fs-5 "></i>
                        <Spinner v-else classColor="text-danger" />
                    </div>
                </div>
            </template>
            </Column>

            </DataTable>

        </div>

    </section>
</template>

<script setup>
import EditUe from '../edit/EditUe.vue'
import Modal from '../../modal/Modal.vue'
import IconUpload from '../../icons/IconUpload.vue'
import Error from '../error/Error.vue'
import Spinner from '../../annimate/Spinner.vue'
import { FilterMatchMode } from 'primevue/api'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete'
import { useToast } from "primevue/usetoast";
import Toast from 'primevue/toast';
import InputText from 'primevue/inputtext'
import { ref } from '@vue/reactivity';
import { useData } from '@/stores/data'
import { computed, watchEffect } from 'vue'
import { useDataIO } from '@/stores/data_io'
import { useAuthStore } from '@/stores/auth'
import { useRoute } from 'vue-router'



const Data=useData()
const DataIO=useDataIO()
const AuthStore=useAuthStore()
const route=useRoute()

const parcours=ref('')
const semestre=ref('')
const option=ref('')
const suggestionParcour=ref([])
const suggestionSemestre=ref([])
const suggestionOptions=ref([{id:0,valeur:"obligatoire"},{id:1,valeur:"facult"}])
const uploadue=ref('')

const postData=ref({
    parcour_id:'',
    ue:'',
    credit: ''
})

defineProps({
    is_responsable_parcour: {
        type: Boolean,
        default: false
    }
})


const filters = ref({
        'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
    });

const myParcoursUes=computed(()=>{
    return Data.ues?.filter((el)=>el.parcour.id==route.params.parcour)
})

const toast=useToast()



const showSuccessDelete = () => {
    toast.add({
        severity:'success', summary: "Suppression unité d'enseignement", detail:"Suppréssion unité d'enseignement succès", life: 3000
    });
}


const showSuccessAdd = () => {

    postData.value.parcour_id=''
    postData.value.ue=''
    postData.value.credit=''


    toast.add({
        severity:'success', summary: "Ajout unité d'enseignement", detail:"ajout unité d'enseignement succès", life: 3000
    });

}

const searchSemestre=(event)=>{

    setTimeout(() => {
        
        if (!event.query.trim().length) {
            suggestionSemestre.value = [...Data.semestres];
        }
        else {
            suggestionSemestre.value = Data.semestres.filter((semestre) => {

                return semestre.semestre.toLowerCase().includes(event.query.toLowerCase());

            });
        }
    }, 150);
}

const searchOption=(event)=>{
    setTimeout(() => {
        
        suggestionOptions.value = suggestionOptions.value.filter((option) => {

            return option.valeur.toLowerCase().includes(event.query.toLowerCase());

        });
    }, 150);
}

const searchParcour=(event)=>{
        setTimeout(() => {
            if (!event.query.trim().length) {
                suggestionParcour.value = [...Data.parcours];
            }
            else {
                suggestionParcour.value = Data.parcours.filter((parcour) => {
                    return `${parcour.parcour} ${parcour.abreviation}`.toLowerCase().includes(event.query.toLowerCase());
                });
            }
        }, 150);
    }

    const submite_ues=()=>{
        postData.value.parcour_id=parcours.value?.id||route.params.parcour
        postData.value.semestre_id=semestre.value?.id
        postData.value.option=option.value?.id
        Data.addUe(postData.value,showSuccessAdd)
    }

    const importUe=(event)=>{


        const file = event.target.files[0]
        if (!file) {
            alert('fichier invalide')
            return
        }

        var formData = new FormData()
        formData.append('file',file)

        DataIO.importUe(formData)

        uploadue.value.value=''

    }

</script>

