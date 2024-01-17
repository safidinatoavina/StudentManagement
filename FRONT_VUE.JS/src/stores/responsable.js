import { defineStore } from 'pinia'
import { ref,computed } from 'vue'
import { useToasting } from '@/stores/toasting'
import axios from 'axios'
import { useRoute } from 'vue-router'

export const useResponsable = defineStore('responsable', () => {
    
    const ue_facult=ref([])
    const Toasting=useToasting()
    const pending=ref({})
    const route=useRoute()

    //getters
    
    const getUeFacult=computed(()=>ue_facult.value)
    const getPending=computed(()=>pending.value)

    //setters


    const setUeFacult=()=>{

        pending.value.ue_facult=true
        axios.get(`/responsable/ue-facult/${route.params.parcour||''}`)
        .then((response)=>{
            pending.value.ue_facult=false
            ue_facult.value=response.data
        }).catch((error)=>{
            pending.value.ue_facult=false
            Toasting.errorDefault('Ue facultatif',error)
        })
        
    }

    const setUeOptionStudent=(payload)=>{

        pending.value.set_obli=true
        axios.post("/responsable/set-ue-obli",payload)
        .then((response)=>{
            pending.value.set_obli=false
            Toasting.success('Commutateur Ue facultatif',"succès")
        }).catch((error)=>{
            pending.value.set_obli=false
            Toasting.errorDefault('Commutateur Ue facultatif',error)
        })

    }

  
    return { 
        getUeFacult,
        getPending,
        setUeOptionStudent,
        setUeFacult,
    }
})
