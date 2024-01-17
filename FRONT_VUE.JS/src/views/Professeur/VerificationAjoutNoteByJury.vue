<template>
    <section class="container p-5">
        <div v-if="Verification.getPending.fetch_ues">
            <Loading />
        </div>
        <ListUeParcour v-else-if="Verification.getUes.length" />
        <h4 v-else>
            Liste vide
        </h4>
    </section>
</template>

<script setup>
import Loading from '../../components/annimate/Loading.vue'
import ListUeParcour from '../../components/section/verification/ListUeParcour.vue'
import {useVerification} from '@/stores/verification'
import { onBeforeRouteUpdate, useRoute } from 'vue-router'
import { onMounted } from 'vue'

const Verification=useVerification()
const route=useRoute()

onMounted(()=>{
    Verification.fetchUes({parcour_id:route.params.parcour})   
})


onBeforeRouteUpdate((to,from)=>{
    if (to.params.parcour !== from.params.parcour)
        Verification.fetchUes({parcour_id:to.params.parcour}) 
})

</script>
