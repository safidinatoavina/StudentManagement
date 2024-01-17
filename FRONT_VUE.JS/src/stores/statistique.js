import { defineStore } from 'pinia'
import { ref,computed } from 'vue'
import { useToasting } from '@/stores/toasting'
import { useAuthStore } from '@/stores/auth'

import axios from 'axios'

export const useStatistique = defineStore('statistique', () => {
    
    const Auth=useAuthStore()
    const Toasting=useToasting()
    const pending=ref({})
    const ues_matieres=ref(false)
    const etudiant_has_notes = ref(false)
    const stats_notes = ref(null);

    //getters
    
    const getUesMatieres=computed(()=>ues_matieres.value)
    const getpending=computed(()=>pending.value)
    const getEtudiantHasNote = computed(() => etudiant_has_notes.value)
    const getStatNotes=computed(()=>stats_notes.value||[])

    //setters

    const setUesMatiere=()=>{
        pending.value.get_ue_matiere=true
        axios.get('/statistique/ue-matieres').then((response)=>{
            pending.value.get_ue_matiere=false
            ues_matieres.value=response.data
        }).catch((error)=>{
            pending.value.get_ue_matiere=false
            Toasting.errorDefault('statistique ue',error)
        })
    }

    const setEtudiantHasNote=()=>{
        pending.value.get_etudiant_has_note=true
        axios.get('/statistique/etudiant-has-note').then((response)=>{
            pending.value.get_etudiant_has_note=false
            etudiant_has_notes.value=response.data
        }).catch((error)=>{
            pending.value.get_etudiant_has_note=false
            Toasting.errorDefault('statistique note',error)
        })
    }

    const getStatistiqueAjoutNote = () => {

        if (stats_notes.value && !Auth.isAdmin)
                return

        pending.value.get_stat_ajout_note=true
        axios.get('/statistique/note-percent').then((response)=>{
            pending.value.get_stat_ajout_note=false
            stats_notes.value=response.data
        }).catch((error)=>{
            pending.value.get_stat_ajout_note=false
            Toasting.errorDefault('statistique note',error)
        })
    }

  
    return { 
        getUesMatieres,
        getpending,
        getEtudiantHasNote,
        getStatNotes,
        setUesMatiere,
        getStatistiqueAjoutNote,
        setEtudiantHasNote
    }

})
