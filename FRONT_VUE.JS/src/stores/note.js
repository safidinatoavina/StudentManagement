import { defineStore } from 'pinia'
import { ref,computed } from 'vue'
import axios from 'axios'
import { useRoute } from 'vue-router'
import { useToasting } from './toasting'
import { useData } from './data'
import { useDataIO } from '@/stores/data_io'
import { useEtudiantNoteFilter } from '@/stores/filtre_etudiant'

export const useNote = defineStore('notes', () => {
    
    const notes = ref({})
    const validation_ue_ecue=ref({})
    const pending=ref(false)
    const pending_obj=ref({})
    const pending_pdf=ref(false)
    const pending_note=ref(false)
    const errors=ref({})
    const etudiants=ref({})
    const notes_tp=ref({})
    const Toasting = useToasting()
    const EtudiantNoteFilter=useEtudiantNoteFilter()
    const Data= useData()

    const route=useRoute()
    const DataIO=useDataIO()


    //getters
    
    const getNotes=computed(()=>notes.value)

    const getNotesTP=computed(()=>notes_tp.value)

    const getPending=computed(()=>pending.value)

    const getPendingPdf=computed(()=>pending_pdf.value)

    const pendingObj=computed(()=>pending_obj.value)

    const getPendingNote=computed(()=>pending_note.value)

    const getValidationUeEcue=computed(()=>validation_ue_ecue.value)


    const getEtudiants=computed(()=>etudiants.value)


    const getErrors=computed(()=>errors.value)


    const getListEtudiantByParcours=(parcour=false)=>{

        let _parcour= route.params.parcour || parcour

        if(!_parcour)
            return

        pending.value=true

        axios.get(`/etudiant/get-by-parcour-and-anne/${_parcour}`)
        .then((response)=>{
            etudiants.value[_parcour]=response.data
            setFullName(_parcour)
            pending.value=false
        })
        
    }

    const setFullName=(parcour)=>{

        etudiants.value[parcour]?.forEach((element,index) => {
            etudiants.value[parcour][index].etudiant.personne.full_name=element.etudiant.personne.nom+' '+(element.etudiant.personne?.prenom||'')
        });

    }




    //action 


    const setNotes = (matiere, parcour = '') => {
        
        pending_obj.value.liste_note = true
        
        axios.get(`/matiere-notes/${matiere}`)
            .then((response)=>{
                pending_obj.value.liste_note = false
                notes.value[route.params.parcour||parcour]=response.data
            })
            .catch((error)=>{
                Toasting.errorDefault("Liste note", error)
                pending_obj.value.liste_note = false
            })
    }

    const setNotesTP=(tp,parcour='')=>{
        let _parcour=route.params.parcour || parcour
        pending_obj.value.liste_note_tp=true
        axios.get(`/matiere-notes-tp/${tp}`)
            .then((response)=>{
                notes_tp.value[_parcour]=response.data
                pending_obj.value.liste_note_tp=false
            })
            .catch((error)=>{
                pending_obj.value.liste_note_tp=false
                Toasting.errorDefault("Liste note",error)
            })
    }

    const setNoteParcour=(parcour_id,payload)=>{
        notes.value[parcour_id]=payload
    }

    const setNoteTPParcour=(parcour_id,payload)=>{
        notes_tp.value[parcour_id]=payload

    }

    const saveNote=(data,callback=()=>{},error_callback=()=>{})=>{

        if(data.valeur===''){
            errors.value.save_note='Le valeur du note est obligatoire'
            return
        }
        pending_note.value=true

        axios.post(`/save-note`,data)
        .then((response)=>{
            pending_note.value=false
            setNoteParcour(data.parcour_id,response.data)
            Toasting.success('Ajout note',"Note ajouté avec success")
            callback()
        })
        .catch((error)=>{
            pending_note.value=false
            errors.value.save_note=error.response.data.message
            Toasting.errorDefault("Ajout note",error)
            error_callback()
        })
    }

    const saveNoteTP=(data,callback=()=>{},error_callback=()=>{})=>{

        if(data.valeur===''){
            errors.value.save_note='Le valeur du note est obligatoire'
            return
        }
        pending_note.value=true

        axios.post(`/save-note-tp`,data)
        .then((response)=>{
            pending_note.value=false
            setNoteTPParcour(data.parcour_id,response.data)
            Toasting.success('Ajout note',"Note ajouté avec success")
            callback()
        })
        .catch((error)=>{
            pending_note.value=false
            errors.value.save_note=error.response.data.message
            Toasting.errorDefault('Ajout note',error)
        })
    }


    const UpdateNote=(data,callback=()=>{},error_callback=()=>{})=>{

        if(data.valeur===''){
            errors.value.update_note='Le valeur du note est obligatoire'
            return
        }

        if (!confirm("Voulez vous vraiment confirmer cette action?"))
                return

        pending_note.value=true

        axios.post(`/update-note/${data.id}`,data)
        .then((response)=>{
            pending_note.value = false
            EtudiantNoteFilter.updateDataNote({
                id: data.id,
                valeur: data.valeur
            })
            callback()
            Toasting.success('Modification note','Modification note succès')
        })
        .catch((error)=>{
            pending_note.value=false
            errors.value.update_note = error.response.data.message
            Toasting.errorDefault("Modification note",error)
        })
    }


    const searchEtudiantNumInsciption=(numInscription)=>{

        return etudiants.value[route.params.parcour]?.find(etudiant=>etudiant.numeroInscription==numInscription)

    }

    const GetPdf=(data,type='matiere')=>{

        if(type=='tp'){
            let tp=Data.tps.find(el=>el.id==(route.params.tp || data.tp))
            if(tp)
                data.matiere_id=tp?.matiere?.id
        }

        if(!data.annee_universitaire_id)
            data.annee_universitaire_id=Data.en_cours?.annee?.id

        pending_pdf.value=true
        axios.post('/pdf/generate-list-for-professeur',data,{
            responseType: 'blob' // Spécifie le type de réponse attendu comme un objet Blob
          })
            .then((response)=>{
                pending_pdf.value=false
                DataIO.download(response.data,"liste_pdf","pdf")
            })
            .catch((error)=>{
                //error
                pending_pdf.value=false

            })
    }

    const reinitNoteError=()=>{
        errors.value={}
    }

    const viderNoteMatiere = (data) => {

        if(!confirm('confirmer votre action'))
            return

        pending_obj.value.vider_note=true

        axios.post('/vider-note', data)
            .then((response) => {
                pending_obj.value.vider_note=false
                notes.value[data.parcour] = response.data
                Toasting.success('Vider note','succès')
            }).catch((error) => {
                pending_obj.value.vider_note=false
                Toasting.errorDefault('Vider note',error)
            })
    }

    const fetchValidationMatiereUe = (matiere) => {
        
        if (validation_ue_ecue.value[matiere])
                return

        pending_obj.value.validation_ue_ecue=true

        axios.get('/validation-ue-matiere/' + matiere)
            .then((response) => {
                pending_obj.value.validation_ue_ecue = false
                validation_ue_ecue.value[matiere]=response.data
            }).catch((error) => {
                pending_obj.value.validation_ue_ecue=false
                Toasting.errorDefault('Validation',error)  
            })
    }

    const downloadExcelValidationUeEcue = (matiere) => {

        pending_obj.value.dowload_validation_ue_ecue=true

        axios.get('/data/download-validation-ue-matiere/' + matiere,{
            responseType: 'blob' // Spécifie le type de réponse attendu comme un objet Blob
          })
            .then((response) => {
                pending_obj.value.dowload_validation_ue_ecue = false
                DataIO.download(response.data,"validation-ue","xlsx")
            }).catch((error) => {
                pending_obj.value.dowload_validation_ue_ecue=false
                Toasting.errorDefault('Téléchargement',error)  
            })
    }

    const viderNoteTp = (data) => {

        if(!confirm('confirmer votre action'))
            return
        
        pending_obj.value.vider_note=true
        axios.post('/vider-note-tp', data)
            .then((response) => {
                pending_obj.value.vider_note=false
                notes_tp.value[data.parcour] = response.data
                Toasting.success('Vider note','succès')
            }).catch((error) => {
                pending_obj.value.vider_note=false
                Toasting.errorDefault('Vider note',error)
            })
    }


    return { 
        getNotes,
        getNotesTP,
        getEtudiants,
        getPending,
        getPendingNote,
        getValidationUeEcue,
        getErrors,
        getPendingPdf,
        pendingObj,
        setNoteParcour,
        viderNoteMatiere,
        fetchValidationMatiereUe,
        downloadExcelValidationUeEcue,
        viderNoteTp,
        setNoteTPParcour,
        saveNote,
        saveNoteTP,
        UpdateNote,
        setNotes,
        setNotesTP,
        reinitNoteError,
        searchEtudiantNumInsciption,
        getListEtudiantByParcours,
        GetPdf
        
    }

})

