<template> 
  <div> 
      <h5 class="text-center text-primary text-decoration-underline  my-3"> 
        Statistique de nombre d'UE et Matiers chaque parcour 
      </h5> 
      <div v-if="!Statistique.getpending.get_ue_matiere" style="height:300px;"> 
        <Line :data="Statistique.getUesMatieres" :options="options" /> 
      </div> 
      <div v-else> 
        <Loading /> 
      </div> 
  </div> 
</template> 

<script setup> 
import Loading from '../../../annimate/Loading.vue' 
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

const Data=ref({
  labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
  datasets: [
    {
      label: 'Nombre de matiere chaque parcour',
      backgroundColor: '#f87979',
      data: [40, 39, 10, 40, 39, 80, 40]
    }
  ]
})

watchEffect(()=>{

  if(!Statistique.getpending.get_ue_matiere && Statistique.getUesMatieres!==false )
    ChartJS.register( CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend ) 

})

const options = ref({ responsive: true, maintainAspectRatio: false }) 

onBeforeMount(()=>{ Statistique.setUesMatiere() }) 

</script>



