<template>
    <div>
        <div class="my-3">
            <AutoComplete v-model="parcour" 
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

    </div>
</template>

<script setup>
import AutoComplete from 'primevue/autocomplete'
import { ref } from "vue"
import { useData } from '@/stores/data'

const parcour=ref('')
const suggestionParcour=ref([])
const Data=useData()

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