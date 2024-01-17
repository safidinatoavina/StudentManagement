<template>
    <section>

        <h5 class="text-center text-info text-decoration-underline mt-3 " >
            TP : "{{ Data.tps.find(el=>el.id==tp)?.tp }}", Semestre : "{{ Data.tps.find(el=>el.id==tp)?.matiere.semestre.semestre }}"  
        </h5>

        <AddNoteTP :parcour="parcour" :tp="tp" v-if="!Note.getPending" />
        <div v-else class="my-5">
            <Loading />
        </div>
    </section>
</template>


<script setup>
import Loading from '../../annimate/Loading.vue'
import AddNoteTP from './AddNoteTP.vue'
import { useNote } from '@/stores/note'
import { onMounted } from 'vue'
import { useData } from '@/stores/data'

const Note=useNote()
const Data=useData()

const props=defineProps({
    parcour: Number,
    tp:Number
})

onMounted(()=>{
    Note.getListEtudiantByParcours(props.parcour) //
    Note.setNotesTP(props.tp,props.parcour) //params TP
})
</script>
