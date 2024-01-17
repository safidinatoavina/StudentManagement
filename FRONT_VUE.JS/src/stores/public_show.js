import { defineStore } from 'pinia'
import { ref,computed } from 'vue'
import { useRouter } from 'vue-router'
import { useToasting } from '@/stores/toasting'

import axios from 'axios'

export const usePublicEvenement = defineStore('public-evenement', () => {
    
    const router=useRouter()
    const Toasting=useToasting()

    const pendings=ref({})
    const evenements=ref([])

    const getEvenements=computed(()=>evenements.value)
    const getPendings=computed(()=>pendings.value)

    const fetch_active_evenements=()=>{

        pendings.value.fetch_active=true

        axios.get('/evenement/active-evenement')
        .then((response)=>{
            pendings.value.fetch_active=false
            evenements.value=response.data
        })
        .catch((error)=>{
            pendings.value.fetch_active=false
            Toasting.error('évènement',"erreur serveur")
        })

    }

    return {
        getEvenements,
        getPendings,
        fetch_active_evenements
    }

})
