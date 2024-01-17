import { defineStore } from 'pinia'
import { ref,computed } from 'vue'
import { useToasting } from '@/stores/toasting'
import axios from 'axios'

export const useVerification = defineStore('verification', () => {
    
    const Toasting=useToasting()
    const ues=ref([])
    const pending=ref({})

    //getters
    
    const getUes=computed(()=>ues.value)
    const getPending=computed(()=>pending.value)

    //setters

    const fetchUes=(payload)=>{
        pending.value.fetch_ues=true
        axios.post('/verification-data/feth-ue-parcours',payload)
        .then((response)=>{
            pending.value.fetch_ues=false
            ues.value=response.data
        }).catch((error)=>{
            pending.value.fetch_ues=false
            Toasting.errorDefault('Liste ue',error)
        })

    }
  
  
    return { 
        getUes,
        getPending,
        fetchUes
    }
})
