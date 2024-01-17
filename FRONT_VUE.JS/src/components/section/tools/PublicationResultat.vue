<template>
    <section>

        <h5 class="my-2 text-center text-decoration-underline">Ue semestre : {{ Data.en_cours?.semestre.semestre }}</h5>
        

        <div v-if="!Operation.getPending.ue_jury">
            <DataTable filterDisplay="menu"
                    :filters="filters"
                    :paginator="true" :rows="10" :value="Operation.uesJury" responsiveLayout="scroll" 
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    :rowsPerPageOptions="[10,20,50]"
                >
                <template #header>
                    <div class="d-flex justify-content-between">
                        <span class="p-input-icon-left">
                            <i class="pi pi-search" />
                            <InputText v-model="filters['global'].value" placeholder="Recherche" />
                        </span>
                    </div>
                </template>

                <Column :sortable="true" field="ue" header="UE"></Column>

                <Column header="SESSION NORMALE">

                    <template #body="{data}">
                        <div>
                            <button @click="Operation.publicResult({ue:data.id})" class="btn btn-primary" :disabled="data.ue_publics.length">
                                Publier
                                <Spinner v-if="Operation.getPending.public_result?.[data.id]" />
                            </button>
                            <button class="btn ms-2" :class="[data.ue_publics.length?'btn-danger':'btn-secondary']" @click="Operation.cancelResult({ue:data.id})" :disabled="!data.ue_publics.length">
                                Annuler la publication
                                <Spinner v-if="Operation.getPending.cancel_result?.[data.id]" />
                            </button>
                        </div>
                    </template>

                </Column>

                <Column header="RATTRAPAGE" >

                    <template #body="{data}" >
                        <div v-if="data.ue_publics.length">

                            <button @click="Operation.publicResultRattrapage({ue:data.id})" class="btn btn-primary" :disabled="data.ue_publics[0].avec_ratrapage">
                                Publier
                                <Spinner v-if="Operation.getPending.public_result_rattrapage?.[data.id]" />
                            </button>
                            <button class="btn ms-2" :class="[data.ue_publics.length?'btn-danger':'btn-secondary']" @click="Operation.cancelResultRattrapage({ue:data.id})" :disabled="!data.ue_publics[0].avec_ratrapage">
                                Annuler la publication
                                <Spinner v-if="Operation.getPending.cancel_result_rattrapage?.[data.id]" />
                            </button>
                        </div>
                    </template>

                </Column>

                
            </DataTable>
        </div>

        <div v-else class="m-5">
            <Loading />
        </div>


    </section>
</template>
<script setup>
import Spinner from '../../annimate/Spinner.vue'
import Loading from '../../annimate/Loading.vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete'
import InputText from 'primevue/inputtext'
import { FilterMatchMode } from 'primevue/api'
import { ref } from "@vue/reactivity"
import { useData } from '@/stores/data'
import { useOperation } from '@/stores/operation'
import { onBeforeMount } from 'vue'

const Data=useData()
const Operation=useOperation()

const filters = ref({
        'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
    });


onBeforeMount(()=>{
    Operation.setUeJury()
})

</script>
