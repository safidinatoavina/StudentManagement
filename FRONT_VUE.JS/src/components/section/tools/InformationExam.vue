<template>
    <div class="border">
        <div class="row text-center">
            <h4 class="text-primary my-3 ">
                <strong class="me-1 text-dark text-decoration-underline">Matière:</strong>
                <strong class="ms-3 text-primary">{{ isTp?'(TP) '+NoteInfo?.tp :NoteInfo?.matiere   }}</strong>
            </h4>
            <div class="col-12 col-md-6 py-2">
                <p>
                    <strong class="text-decoration-underline">Parcours:</strong> 
                    <strong class="ms-3 text-primary">{{ isTp?NoteInfo?.matiere?.parcour?.parcour:NoteInfo?.parcour?.parcour   }}</strong>
                </p>
                <p>
                    <strong class="text-decoration-underline">Année universitaire:</strong> 
                    <strong class="ms-3 text-primary">{{ Data.en_cours?.annee?.valeur }}</strong>
                </p>
            </div>
            <div class="col-12 col-md-6 py-2">
                <p ref="semestre_el">
                    <strong class="text-decoration-underline">Semestre:</strong> 
                    <strong class="ms-3 text-primary" >{{ Data.en_cours?.semestre.semestre   }}</strong>
                </p>
                <p ref="session_el">
                    <strong class="text-decoration-underline">Session:</strong> 
                    <strong class="ms-3 text-primary">{{ Data.en_cours?.session.session   }}</strong>
                </p>
            </div>
        </div>
    </div>
</template>


<script setup>

import {useData} from '@/stores/data'
import { onMounted, ref } from '@vue/runtime-core'
import gsap from 'gsap'

const Data=useData()

defineProps({
    NoteInfo:Object,
    isTp:{
        type:Boolean,
        default:false
    }
})

const semestre_el=ref('')
const session_el=ref('')

onMounted(()=>{

    gsap.from(semestre_el.value,{ duration: 2.5,ease: "elastic",x:-500})
    gsap.from(session_el.value,{duration: 2.5,ease: "elastic",x:-500})

})

</script>
