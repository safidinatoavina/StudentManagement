<template>
    <section class="form-group m-2 content-tab">




        <div class="d-flex justify-content-center">

            <div class="col-12 col-md-6">
                <div class="mt-3 text-center">
                    <strong>Effectif des etudiants: {{ etudiants.length }}</strong>
                </div>
                <Doughnut
                    id="my-chart-id"
                    :options="chartOptions"
                    :data="chartData"
                />
            </div>

        </div>


    </section>
</template>

<script setup>

import { useNote } from '@/stores/note'
import { ref } from '@vue/reactivity'
import gsap from 'gsap'
import { computed, onBeforeMount, onMounted } from '@vue/runtime-core'

import { Doughnut } from 'vue-chartjs'
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js'

ChartJS.register(ArcElement, Tooltip, Legend)


const props=defineProps({
    parcour:Number
})


const chartData= ref( {
        labels: ['Pas de notes', 'Obtenu la moyenne', "N'ont pas obtenu la moyenne"],
      })

const chartOptions= ref({
        responsive: true,
      })


const Note=useNote()
const etudiants=Note.getEtudiants?.[props.parcour]||[]
const notes=computed(()=>Note.getNotes?.[props.parcour])

const getNote=(etudiant_id)=>{
    return Note.getNotes[props.parcour].find(e=>e.historique.etudiant.id===etudiant_id)
}

const student_notes = computed(()=>{
    var student_note=[]
    notes.value.forEach(element => {
        student_note.push({
            note: (!!element.is_set)?element.valeur:undefined
        })
    });

    return student_note

})



const moyenne=computed(()=>student_notes.value.filter(e=>e.note>=10))
const notMoyenne=computed(()=>student_notes.value.filter(e=>e.note<10))
const noNote=computed(()=>student_notes.value.filter(e=>e.note===undefined))



onBeforeMount(()=>{
    chartData.value.datasets=[
        {
        backgroundColor: ['#6c757d', '#198754', '#dc3545'],
        data: [noNote.value.length,moyenne.value.length,notMoyenne.value.length]
        }
    ]

})

onMounted(()=>{

    gsap.from('section.content-tab',{ duration: 0.5,ease: "power4.out",y:500})

})

</script>

