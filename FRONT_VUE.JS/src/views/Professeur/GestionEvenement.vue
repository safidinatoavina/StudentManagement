<template>
    <section class="mx-3 mt-3">
        <h3 class="text-decoration-underline text-center mb-4">Gestion évènement en public </h3>

        <div class="d-flex justify-content-end my-2">
            <router-link class="btn btn-primary" :to="{name:'create-evenement'}">
                <IconAdd />
            </router-link>
        </div>

        <div v-if="!Evenement.getPending.fetch">
            <DataTable filterDisplay="menu"
                :filters="filters" 
                :paginator="true" :rows="4" :value="Evenement.getEvenements" responsiveLayout="scroll" 
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                :rowsPerPageOptions="[4,10,20,50]"
            >
    
                <template #header>
                    <div class="d-flex justify-content-between">
                        <span class="p-input-icon-left">
                            <i class="pi pi-search" />
                            <InputText v-model="filters['global'].value" placeholder="Keyword Search" />
                        </span>
                    </div>
                </template>
        
                <Column  header="Titre" :sortable="true" field="titre"> </Column>
                <Column  header="Date modification" :sortable="true" field="last_update"> </Column>
                <Column  header="Statut" :sortable="true" field="is_active">
                    <template #body="{data}">
                        <div>
                            <select v-model="data.is_active" @change="Evenement.updateStatus(data.id,data.is_active)">
                                <option value="1">Actif</option>
                                <option value="0">Désactivé</option>
                            </select>
                        </div>
                    </template>
                </Column>
                <Column  header="Actions" >

                <template #body="{data}">
                    <div class="d-flex" >
                        <router-link :to="{
                            name:'edit-evenement',params:{evenement:data.id}
                            }" class="btn btn-sm me-2 p-1 text-primary">
                            <i class="fa fa-edit fs-5 "></i>
                        </router-link>
                        <div @click="Evenement.deleteEvenement(data.id)" class="btn btn-sm me-2 p-1 text-danger">
                            <i v-if="!Evenement.getPending.delete[data.id]" class="fa fa-trash fs-5 "></i>
                            <Spinner v-else classColor="text-danger" />
                        </div>
                    </div>
                </template>
                </Column>
        
            </DataTable>
        </div>
        <div v-else class="my-5" >
            <Loading />
        </div>

    </section>
</template>


<script setup>
import Loading from '../../components/annimate/Loading.vue'
import IconAdd from '../../components/icons/IconAdd.vue'
import Spinner from '../../components/annimate/Spinner.vue'
import { FilterMatchMode } from 'primevue/api'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete'
import InputText from 'primevue/inputtext'
import { onBeforeMount, ref } from 'vue';
import { useEvenement } from "@/stores/evenement"

const Evenement=useEvenement()

const filters = ref({
        'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
    });


onBeforeMount(()=>{
    Evenement.fetchEvenement()
})

</script>
