import { defineStore } from 'pinia'
import { ref,computed } from 'vue'
import { useRouter } from 'vue-router'
import { useToasting } from '@/stores/toasting'

import axios from 'axios'

export const useReinscription = defineStore('reinscription', () => {
    
    const Toasting=useToasting()

    const reinscrits=ref([])
    const items=ref({})
    const selectedParcour=ref('')
    const results= ref(null)

    const pending=ref({})


    const getPending=computed(()=>pending.value)
    const getReinscrit=computed(()=>reinscrits.value)
    const getItems=computed(()=>items.value)
    const getResults= computed(()=>results.value)

    const reinscriptionRedoublant=(payload)=>{

        pending.value.reinscription=true

        axios.post(`/reinscription/redoublant/${payload.historique_id}`,payload)
        .then((response)=>{

            pending.value.reinscription=false
            Toasting.success("Reinscription","réinscription succès")
            
        }).catch((error)=>{

            pending.value.reinscription=false

            Toasting.errorDefault('Réinscription',error)

        })

    }


    const reinscriptionPassant=(payload)=>{

        pending.value.reinscription=true

        axios.post(`/reinscription/passant/${payload.historique_id}`,payload)
        .then((response)=>{

            pending.value.reinscription=false
            Toasting.success("Reinscription","réinscription succès")


        }).catch((error)=>{

            pending.value.reinscription=false
            Toasting.errorDefault('Réinscription',error)


        })

    }

    const getListAdmis=(parcour)=>{
        pending.value.liste=true
        axios.get(`/reinscription/liste/admis/${parcour}`).then((response)=>{
            items.value.passant=response.data
            pending.value.liste=false
        }).catch((error)=>{
            Toasting.errorDefault('Liste admis',error)
            items.value={}
            pending.value.liste=false
        })
    }

    const getListRedoublants=(parcour)=>{
        pending.value.liste=true
        axios.get(`/reinscription/liste/redoublant/${parcour}`).then((response)=>{
            items.value.redoublant=response.data
            pending.value.liste=false
        }).catch((error)=>{
            Toasting.errorDefault('Liste Redoublant',error)
            items.value={}
            pending.value.liste=false
        })
    }


    const fetchResults=(data)=>{
        results.value=null
        let payload = {...data};
        payload.parcour_id = payload.parcour_id?.id
        pending.value.result=true
        axios.post(`/reinscription/results`,payload).then((response)=>{
            results.value=response.data
            pending.value.result=false
        }).catch((error)=>{
            Toasting.errorDefault('Resultats',error)
            results.value=[]
            pending.value.result=false
        })
    }

    const handle=(payload)=>{
        payload.parcour_id = payload.parcour_id?.id
        pending.value.reinscription=true
        axios.post(`/reinscription/handle`,payload).then((response)=>{
            pending.value.reinscription=false
            Toasting.success("Reinscription","réinscription succès")
        }).catch((error)=>{
            Toasting.errorDefault('Erreur réinscription',error)
            pending.value.reinscription=false
        })
    }

    return {
        getPending,
        getReinscrit,
        getItems,
        selectedParcour,
        getResults,
        reinscriptionRedoublant,
        reinscriptionPassant,
        getListAdmis,
        getListRedoublants,
        fetchResults,
        handle

    }

})
