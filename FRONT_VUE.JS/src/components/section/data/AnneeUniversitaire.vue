<template>
    <section>
        <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingFor">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFor" aria-expanded="false" aria-controls="flush-collapseFor">
                    <div class="text-primary">
                        Ajouter
                    </div>
                </button>
                </h2>
                <div id="flush-collapseFor" class="accordion-collapse collapse" aria-labelledby="flush-headingFor" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">

                        <div class="row mb-2">

                            <div class="col col-sm-5">
                                <select v-model="postData.valeur"
                                 class="form-control" placeholder="Niveau"  >
                                    <option value="" selected disabled>Année universitaire</option>
                                    <option v-for="(date,index) in listDate" :key="`option-${index}`" :value="date">{{ date }}</option>

                                </select>
                            </div>

                            <Error :error="Data.error.annee" ></Error>

                        </div>
                        <button class="btn btn-primary btn-sm" @click="Data.addAnnee(postData,showSuccessAdd)">
                            <span>Enregistrer</span>
                            <Spinner v-if="Data.pendings.add_annee" />
                        </button>


                    </div>
                </div>
            </div>
        </div>


        <div class="mt-2">

            <DataTable filterDisplay="menu"
                :filters="filters"
                :paginator="true" :rows="4" :value="Data.annees" responsiveLayout="scroll" 
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
            <Column :sortable="true" field="valeur" header="Annee universitaire"></Column>
            <Column :sortable="true" header="Statut">
                <template #body="{data}">
                    <div class="p-1">
                        <select class="form-control" v-model="statut_model[data.id]" @change="changeStatut(data.id)">
                            <option value="0">En attente</option>
                            <option value="1">Actif</option>
                            <option value="-1">Terminé</option>
                        </select>
                    </div>
                </template>
            </Column>
            <Column header="Actions">
            <template #body="{data}">
                <div class="d-flex">
                    <div :id="data.id" @click="Data.deleteAnnee(data.id,showSuccessDelete)" class="btn btn-sm me-2 p-1 text-danger">
                        <i v-if="!Data.pendings.del_annee || !Data.pendings.del_annee[data.id] " 
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
import { onBeforeMount } from '@vue/runtime-core';

const Data=useData()

const listDate=ref([])
const statut_model=ref({})

const postData=ref({
    valeur:'',
})

const filters = ref({
        'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
    });
const toast=useToast()


const showSuccessDelete = () => {
    toast.add({
        severity:'success', summary: 'Suppression année universitaire', detail:'Suppréssion année universitaire succès', life: 3000
    });
}

const setListDate=()=>{

    const year = new Date().getFullYear();
    const first=parseInt(eval(`${year}-8`))
    const last=parseInt(eval(`${year}`))
    const diff=parseInt(last-first)

    for (let index = 0; index < diff ; index++){
        listDate.value.push(`${last-index-1}-${last-index}`)
    }


}

const showSuccessAdd = () => {

    postData.value.valeur=''

    toast.add({
        severity:'success', summary: 'Ajout année universitaire', detail:'ajout année universitaire succès', life: 3000
    });
}

const showSuccessUpdateAnnee = () => {

    postData.value.valeur=''

    toast.add({
        severity:'success', summary: 'Mis à jour année universitaire', detail:'Mis à jour année universitaire succès', life: 3000
    });

    setModelStatut()

}

const changeStatut=(id)=>{

    var payload={
        id,
        statut:statut_model.value[id]
    }

    if(Data.updateAnnee(payload,showSuccessUpdateAnnee)===false)
        statut_model.value[id]=1

}

const setModelStatut=()=>{
    Data.annees.forEach(element => {
        statut_model.value[element.id]=element.statut
    });
}

onBeforeMount(()=>{
    setListDate()
    setModelStatut()
})

</script>
