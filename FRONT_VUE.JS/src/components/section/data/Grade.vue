<template>
    <section>
        <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                    <div class="text-primary">
                        Ajouter
                    </div>
                </button>
                </h2>
                <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">

                        <div class="row mb-2">
                            <div class="col">
                                <input v-model="postData.grade"
                                type="text" class="form-control" placeholder="Grade">
                            </div>
                            <div class="col">
                                <input v-model="postData.abreviation"
                                type="text" class="form-control" placeholder="Abreviation">
                            </div>
                            <div class="col">
                                <select v-model="postData.niveau"
                                 class="form-control" placeholder="Niveau"  >
                                    <option value="" selected disabled>Niveau</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>

                                </select>
                            </div>

                            <Error :error="Data.error.grade"></Error>
                        </div>
                        <button class="btn btn-primary btn-sm" @click="Data.addGrade(postData,showSuccessAdd)">
                            <span>Enregistrer</span>
                            <Spinner v-if="Data.pendings.add_grade" />
                        </button>


                    </div>
                </div>
            </div>
        </div>


        <div class="mt-2">

            <DataTable filterDisplay="menu"
                :filters="filters"
                :paginator="true" :rows="4" :value="Data.grades" responsiveLayout="scroll" 
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                :rowsPerPageOptions="[4,10,20,50]"
            >

            <template #header>
                <div class="flex justify-content-between">
                    <span class="p-input-icon-left">
                        <i class="pi pi-search" />
                        <InputText v-model="filters['global'].value" placeholder="Keyword Search" />
                    </span>
                </div>
            </template>

            <Column :sortable="true" field="id" header="ID"></Column>
            <Column :sortable="true" field="grade" header="Grade"></Column>
            <Column :sortable="true" field="abreviation" header="Abreviation"></Column>
            <Column :sortable="true" field="niveau" header="Niveau"></Column>
            <Column :sortable="true" header="Actions">
            <template #body="{data}">
                <div class="d-flex">
                    <div class="btn btn-sm me-2 p-1 text-primary d-none">
                        <i class="fa fa-edit fs-5 "></i>
                    </div>
                    <div :id="data.id" @click="Data.deleteGrade(data.id,showSuccessDelete)" class="btn btn-sm me-2 p-1 text-danger">
                        <i v-if="!Data.pendings.del_grade || !Data.pendings.del_grade[data.id] " 
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
import Error from '../error/Error.vue'
import Spinner from '../../annimate/Spinner.vue'
import { FilterMatchMode } from 'primevue/api'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import { useToast } from "primevue/usetoast";
import Toast from 'primevue/toast';
import InputText from 'primevue/inputtext'
import { ref } from '@vue/reactivity';
import { useData } from '@/stores/data'

const Data=useData()

const postData=ref({
    grade:'',
    abreviation:'',
    niveau:''
})

const filters = ref({
        'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
    });
const toast=useToast()


const showSuccessDelete = () => {
    toast.add({
        severity:'success', summary: 'Suppression grade', detail:'Suppréssion de grade succès', life: 3000
    });
}

const showSuccessAdd = () => {

    postData.value.grade=''
    postData.value.abreviation=''
    postData.value.niveau=''

    toast.add({
        severity:'success', summary: 'ajout grade', detail:'ajout grade succès', life: 3000
    });
}

</script>
