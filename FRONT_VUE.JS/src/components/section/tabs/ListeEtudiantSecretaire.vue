<template>
    <div>

        <div class="p-4">
            <div class="mb-1">Veuillez mentioner le parcours </div>
            <AutoComplete v-model="parcour" 
                :suggestions="suggestionParcour" 
                @complete="searchParcour($event)" 
                placeholder="Parcours"
                :dropdown="true" optionLabel="parcour" forceSelection>

                    <template #item="slotProps">
                        <div class="country-item">
                            <div class="ml-2">{{slotProps.item.parcour}} {{slotProps.item.abreviation?'('+slotProps.item.abreviation+')':''}}</div>
                        </div>
                    </template>

            </AutoComplete>
        </div>

        <div class="my-2 px-4">
            <button @click="Secretaire.getFichePresence(parcour)" class="btn btn-primary" :disabled="!parcour?.id">
                Télécharger pdf <Spinner v-if="Secretaire.getPending.fiche_pdf" />
            </button>
        </div>
        
    </div>
</template>

<script setup>
import Spinner from '../../annimate/Spinner.vue'
import { ref } from "vue"
import AutoComplete from 'primevue/autocomplete'
import { useData } from '@/stores/data'
import { useSecretaire } from '@/stores/secretaire'


const Secretaire=useSecretaire()
const Data=useData()
const parcour=ref('')
const suggestionParcour = ref([])


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

</script>
