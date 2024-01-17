<template>
    <section class="px-4">
        <InformationExam :NoteInfo="matiereSelected" :is-tp="true" class="mt-3"/>

        <div class="m-3" v-if="!Note.getPending">
            <AddNoteTP />
        </div>
        <div v-else class="text-center my-5">
            <Loading />
        </div>
        
    </section>
</template>


<script setup>
import Loading from '../../components/annimate/Loading.vue'
import AddNoteTP from '../../components/section/pages/AddNoteTP.vue'
import InformationExam from '../../components/section/tools/InformationExam.vue'
import { useAuthStore } from '@/stores/auth'
import { computed, watchEffect } from 'vue'
import { useNote } from '@/stores/note'
import { useRoute } from 'vue-router'

const AuthStore=useAuthStore()
const Note=useNote()
const route = useRoute()
const matiereSelected=computed(()=> AuthStore.getMatieresTP.find((element)=>element.id==route.params.tp))


watchEffect(()=>{
    Note.getListEtudiantByParcours()
})

</script>

