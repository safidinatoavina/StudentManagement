<template>
    <div>
        <div class="my-4" v-if="isAdmin">
            <AutoComplete id="select-percour" v-model="parcour" 
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

        <div class="my-3" v-if="parcour?.id || !isAdmin">
            <div v-if="Verification.getPending.fetch_ues">
                <Loading />
            </div>
            <ListUeParcour v-else-if="Verification.getUes.length" />
            <h4 v-else>
                Liste vide
            </h4>
        </div>

    </div>
</template>

<script setup>
import Loading from '../../annimate/Loading.vue'
import ListUeParcour from './ListUeParcour.vue'
import AutoComplete from 'primevue/autocomplete'
import { onMounted, ref, watchEffect } from "vue"
import { useData } from '@/stores/data'
import {useVerification} from '@/stores/verification'
import { onBeforeRouteUpdate } from 'vue-router'

const Verification=useVerification()
const parcour=ref('')
const suggestionParcour=ref([])
const Data=useData()

const props=defineProps({
    isAdmin:{
        type:Boolean,
        default:true
    },
    parcour:{
        type:Number,
    }
})

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

onMounted(()=>{
    if(props.parcour){
        Verification.fetchUes({parcour_id:props.parcour})   
    }
})

watchEffect(()=>{
    if(parcour.value?.id){
        Verification.fetchUes({parcour_id:parcour.value.id})   
    }
})

onBeforeRouteUpdate((to,from)=>{
    if(props.parcour){
        if (to.params.parcour !== from.params.parcour)
            Verification.fetchUes({parcour_id:to.params.parcour}) 
    }
})

</script>
