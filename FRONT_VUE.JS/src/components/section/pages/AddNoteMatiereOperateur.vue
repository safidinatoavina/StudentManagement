<template>
    <section>

        <h5 class="text-center text-info text-decoration-underline mt-3 " >
            ECUE : "{{ Data.matiers.find(el=>el.id==matiere)?.matiere }}", Semestre : "{{ Data.matiers.find(el=>el.id==matiere)?.semestre.semestre }}"  
        </h5>

        <GestionNote :parcour="parcour" :matiere="matiere" v-if="!Note.getPending" />
        <div v-else class="my-5">
            <Loading />
        </div>
    </section>
</template>


<script setup>
import GestionNote from '../../../views/Professeur/GestionNote.vue';
import Loading from '../../annimate/Loading.vue'
import AddNoteTP from './AddNoteTP.vue'
import { useNote } from '@/stores/note'
import { watchEffect } from 'vue'
import { useData } from '@/stores/data'

const Note=useNote()
const Data=useData()


const props=defineProps({
    parcour: Number,
    matiere:Number
})

watchEffect(()=>{
    Note.getListEtudiantByParcours(props.parcour) //
    Note.setNotes(props.matiere,props.parcour) //params matiere
})
</script>