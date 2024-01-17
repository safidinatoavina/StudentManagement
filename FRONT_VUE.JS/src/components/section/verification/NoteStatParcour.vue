<template> 
    <div> 
        <h5 class="text-center my-3 text-primary text-deoration-underline">
            Statistique des étudiants noté chaque parcour en semestre {{ Data.en_cours?.semestre.semestre }} {{ Data.en_cours?.session.session }}
        </h5>
        <div v-if="!Statistique.getpending.get_etudiant_has_note" style="height:300px;"> 
          <Line :data="Statistique.getEtudiantHasNote" :options="options" /> 
        </div> 
        <div v-else> 
          <Loading /> 
        </div> 
    </div> 
</template> 
  
<script setup> 
import Loading from '../../annimate/Loading.vue'
import { useData } from '@/stores/data'
  import { 
    Chart as ChartJS, 
    CategoryScale, LinearScale, 
    PointElement, LineElement, 
    Title, Tooltip, Legend 
  } from 'chart.js' 
  import { onBeforeMount, ref, watchEffect } from 'vue' 
  import { Line } from 'vue-chartjs' 
  import { useStatistique } from '@/stores/statistique' 
  
  
  const Statistique=useStatistique() 
  const Data=useData()

  
  watchEffect(()=>{
  
    if(!Statistique.getpending.get_etudiant_has_note && Statistique.getEtudiantHasNote!==false )
      ChartJS.register( CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend ) 
  
  })
  
  const options = ref({ responsive: true, maintainAspectRatio: false }) 
  
  onBeforeMount(()=>{ Statistique.setEtudiantHasNote() }) 
  
</script>

  