import { defineStore } from 'pinia'
import { ref,computed } from 'vue'
import { useToasting } from '@/stores/toasting'

import axios from 'axios'
import { useDataIO } from './data_io'

export const useSecretaire = defineStore('secretaire', () => {

    const Toasting = useToasting()
    const DataIO=useDataIO()

    const pendings=ref({
        releve:{}
    })

    const etudiants=ref([])

    const getPending=computed(()=>pendings.value)
    const getEtudiants=computed(()=>etudiants.value)

    const secretaireFilterEtudiant=(data)=>{
        pendings.value.filter=true
        axios.post('/secretaire/filtre-etudiant',data)
            .then((response)=>{

                pendings.value.filter=false
                etudiants.value=response.data

            }).catch((error)=>{

                pendings.value.filter=false
                Toasting.errorDefault('Filtre etudiant',error)

            })
    }

    const getNoteStatement=(historique)=>{

        pendings.value.releve[historique]=true

        axios.post('/secretaire/releve/note/'+historique,{},{
            responseType: 'blob' // Spécifie le type de réponse attendu comme un objet Blob
          })

            .then((response)=>{

                pendings.value.releve[historique]=false
                DataIO.download(response.data,"releve_note","pdf")

            }).catch((error)=>{

                pendings.value.releve[historique]=false
                Toasting.errorDefault('Relevé notes',error)

            })
    }


    const getFichePresence = (parcour) => {
        pendings.value.fiche_pdf=true
        axios.get(`/secretaire/fiche-presence/${parcour.id}`,{
            responseType: 'blob' // Spécifie le type de réponse attendu comme un objet Blob
        })
            .then((response) => {
                pendings.value.fiche_pdf=false
                DataIO.download(response.data,"Fiche-de-presence-"+parcour.abreviation,'pdf');
            }).catch((error) => {
                pendings.value.fiche_pdf = false
                Toasting.errorDefault('Fiche',error)
            })
    }

    return {
        getPending,
        getEtudiants,
        getNoteStatement,
        getFichePresence,
        secretaireFilterEtudiant
    }

})
