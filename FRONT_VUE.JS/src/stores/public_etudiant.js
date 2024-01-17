import { defineStore } from 'pinia'
import { ref,computed } from 'vue'
import { useRouter } from 'vue-router'
import { useToasting } from '@/stores/toasting'
import { useData } from '@/stores/data'

import axios from 'axios'

export const usePublicFilterEtudiant = defineStore('filter-etudiant', () => {
    
    const router=useRouter()
    const Toasting=useToasting()

    const pending=ref({})
    const etudiants=ref([])
    const historiques=ref([])
    const validations=ref({
        resultat:[]
    })


    const Data=useData()

    const getPending=computed(()=>pending.value)
    const getEtudiants=computed(()=>etudiants.value)
    const getHistoriques=computed(()=>historiques.value)
    const getValidations=computed(()=>validations.value)

    const getFilterResult=(payload)=>{

        pending.value.filter=true

        axios.post('/public/etudiant-filter',payload)
            .then((response)=>{
                etudiants.value=response.data
                pending.value.filter=false
            })
            .catch((error)=>{
                Toasting.errorDefault('Filtre etudiant',error)
                pending.value.filter=false
            })

    }


    const setValidations=(historique)=>{
        pending.value.validation=true
        axios.post(`/public/resultat-validation/${historique}`)
            .then((response)=>{
                validations.value=response.data
                pending.value.validation=false
            })
            .catch((error)=>{
                pending.value.validation=false
                Toasting.errorDefault('Resultat Validation',error)
            })
    }


    const getHistoriqueEtudiant=(etudiant)=>{
        pending.value.historique=true
        axios.post('/public/historiques-etudiant/'+etudiant)
            .then((response)=>{
                historiques.value=response.data
                pending.value.historique=false
            })
            .catch((error)=>{
                Toasting.errorDefault('Liste Historique',error)
                pending.value.historique=false
            })
    }


    const setAnnee=()=>{

        axios.get('/public/annees')
        .then((response)=>{
            Data.setAnnees(response.data)
        })
        .catch((error)=>{
            Toasting.errorDefault('Annee',error)
        })

    }

    return {
        getPending,
        getEtudiants,
        getHistoriques,
        getValidations,
        setValidations,
        getHistoriqueEtudiant,
        setAnnee,
        getFilterResult
    }

})
