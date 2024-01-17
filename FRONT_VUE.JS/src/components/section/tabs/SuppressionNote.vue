<template>
<div class="px-3 mt-4">
    <div>
        <div class="mt-2">
            <div class="row">
                <AutoComplete v-model="parcours"  class="col-12"
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
            <div class="row mt-2" v-if="parcours && typeof parcours=='object'">
                <div class="col d-flex justify-content-center">
                    <AutoComplete v-model="matiere"  :disabled="!!tp?.id"
                        :suggestions="suggestionMatiere" 
                        @complete="searchMatiere($event)" 
                        placeholder="ECUE"
                        :dropdown="true" optionLabel="matiere" forceSelection>

                            <template #item="slotProps">
                                <div class="country-item">
                                    <div class="ml-2">{{slotProps.item.matiere}} {{'('+slotProps.item.parcour.parcour+')'}} {{ slotProps.item.semestre.semestre }}</div>
                                </div>
                            </template>

                    </AutoComplete>
                </div>
                <div class="col d-flex justify-content-center">
                    <AutoComplete v-model="tp"  :disabled="!!matiere?.id"
                        :suggestions="suggestionTP" 
                        @complete="searchTP($event)" 
                        placeholder="TP"
                        :dropdown="true" optionLabel="tp" forceSelection>

                            <template #item="slotProps">
                                <div class="country-item">
                                    <div class="ml-2">{{slotProps.item.tp}} {{'('+slotProps.item.matiere.parcour.parcour+')'}} {{ slotProps.item.matiere.semestre.semestre }}</div>
                                </div>
                            </template>

                    </AutoComplete>
                </div>
            </div>
        </div>
    </div>
    <div v-if="parcours?.id">
        <!-- ici -->
        <ViderNotes v-if="matiere?.id||tp?.id" :parcour="parcours?.id" :matiere="matiere?.id||0" :tp="tp?.id||0" />
    </div>
</div>
</template>

<script setup>
import ViderNotes from '../edit/ViderNotes.vue'
import AutoComplete from 'primevue/autocomplete'
import { useData } from '@/stores/data'
import { onMounted, ref, watch, watchEffect } from 'vue';


const Data=useData()
const parcours=ref('')
const matiere=ref('')
const tp=ref('')

const suggestionParcour=ref([])
const suggestionMatiere=ref([])
const suggestionTP=ref([])

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

const searchMatiere=(event)=>{
    setTimeout(() => {
        if (!event.query.trim().length) {

            suggestionMatiere.value = [...Data.matiers.filter(matiere_el=>{
                return (matiere_el.parcour.id==parcours.value.id) && (matiere_el.semestre_id==Data.en_cours?.semestre?.id)
            })];
        
        }
        else {

            suggestionMatiere.value = Data.matiers.filter(matiere_el=>{
                return (matiere_el.parcour.id==parcours.value.id) && (matiere_el.semestre_id==Data.en_cours?.semestre?.id)
            }).filter((matiere) => {
                return matiere.matiere.toLowerCase().includes(event.query.toLowerCase());
            });

        }
    }, 150);
}

const searchTP=(event)=>{
    setTimeout(() => {
        if (!event.query.trim().length) {

            suggestionTP.value = [...Data.tps.filter(tp_el=>{
                return (tp_el.matiere.parcour.id==parcours.value.id) && (tp_el.matiere.semestre_id==Data.en_cours?.semestre?.id)
            })];
        
        }
        else {

            suggestionTP.value = Data.tps.filter(tp_el=>{
                return (tp_el.matiere.parcour.id==parcours.value.id) && (tp_el.matiere.semestre_id==Data.en_cours?.semestre?.id)
            }).filter((tp_el) => {
                return tp_el.tp.toLowerCase().includes(event.query.toLowerCase());
            });

        }
    }, 150);
}

watchEffect(() => {

    if (!parcours.value) {
        matiere.value = ''
        tp.value=''
    }
    
    if(matiere.value && typeof matiere.value=='object')
        tp.value=''

    if(tp.value && typeof tp.value=='object')
        matiere.value=''
})


onMounted(() => {
    window.scrollTo(0, 0);
})

</script>
