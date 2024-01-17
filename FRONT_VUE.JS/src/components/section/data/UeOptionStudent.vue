<template>
    <section class="container">
        
        <h5 class="text-center my-3 text-primary text-decoration-underline">
            Veuillez selectioner les UE choisis pour chaque étudiants
        </h5>

        <h6 class="text-center text-warning">ceci est prioritaire par rapport au nombre d'ue optionel</h6>

        <DataTable filterDisplay="menu"
                :filters="filters"
                :paginator="true" :rows="4" :value="Responsable.getUeFacult.etudiants" responsiveLayout="scroll" 
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                :rowsPerPageOptions="[4,10,20,50]"
            >

            <Column :sortable="true" field="numeroInscription" header="N°Inscription"></Column>
            <Column :sortable="true" field="nom" header="Nom"></Column>
            <Column :sortable="true" field="prenom" header="Prénom"></Column>

            <Column v-for="option in Responsable.getUeFacult.options" 
                :key="`ue-option-${option.ue}`"
                :sortable="false" 
                :header="option.ue"
            >
            <template #body="{data}">
                <div class="d-flex justify-content-start">
                    <label class="p-2 ps-0 rounded">
                        <input  type="checkbox" style="width:30px;height:30px" @click="Responsable.setUeOptionStudent({
                            historique_id : data.historique_id,
                            ue_id         : option.ue_id
                            })" 

                            :checked="!!data.ue_options.find(el=>el.ue_id==option.ue_id)"
                        >
                    </label>
                </div>
            </template>
            </Column>

        </DataTable>
    </section>
</template>

<script setup>
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import { onBeforeMount, ref } from "vue";
import { FilterMatchMode } from 'primevue/api'
import { useResponsable }   from '@/stores/responsable'

const Responsable=useResponsable()

const filters = ref({
        'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
    });

onBeforeMount(()=>{
    Responsable.setUeFacult()
})

</script>

