import { defineStore } from 'pinia'
import { ref,computed } from 'vue'
import { useToasting } from '@/stores/toasting'
import { useDataIO } from '@/stores/data_io'

import axios from 'axios'
import { useRoute } from 'vue-router'

export const useEtudiantNoteFilter = defineStore('filtre_etudiant', () => {

    const Toasting=useToasting()

    const results=ref([]);
    const resultsJury=ref([]);
    const result_etudiants=ref([])
    const etudiantListParcoursJury=ref([])
    const pending=ref({})
    const tools=ref({})
    const show_result=ref(false)
    const show_pdf=ref(false)
    const pdf=ref({})
    const paramsEtudiant=ref([])
    const route=useRoute()
    const DataIO=useDataIO()

    //getters

    const getResults=computed(()=>results.value)
    const getResultsJury=computed(()=>resultsJury.value)
    const getPending=computed(()=>pending.value)
    const getShowResult=computed(()=>show_result.value)
    const getShowPdf=computed(()=>show_pdf.value)
    const getPdf=computed(()=>pdf.value)
    const getResultEtudiants=computed(()=>result_etudiants.value)
    const getListeEtudiantJury=computed(()=>etudiantListParcoursJury.value)

    //setter

    const setResults=()=>{

        pending.value.result=true
        show_pdf.value=false


        axios.post('/filter/etudiant-with-note',tools.value)
            .then((response)=>{
                results.value=response.data
                pending.value.result=false
                show_result.value=true

            })
            .catch((error)=>{
                pending.value.result=false
                Toasting.errorDefault('Etudiants',error)
            })
    }


    const setResultsJury=()=>{

        pending.value.result=true
        show_pdf.value=false

        axios.post('/filter/jury-filter-etudiant',tools.value)
            .then((response)=>{
                resultsJury.value=response.data
                pending.value.result=false
                show_result.value=true

            })
            .catch((error)=>{
                pending.value.result=false
                Toasting.errorDefault('Etudiants',error)
            })
    }

    const setListeEtudiantJury=()=>{

        pending.value.result_jury=true

        axios.get('/etudiant/jury/etudiant-parcour/'+route.params.parcour)
        .then((response)=>{
            etudiantListParcoursJury.value=response.data
            pending.value.result_jury=false

        })
        .catch((error)=>{
            pending.value.result_jury=false
            Toasting.errorDefault('Etudiants',error)
        })

    }

    const setParamsEtudiant=(query)=>{
        paramsEtudiant.value=query
    }

    const setResultEtudiants=()=>{

        pending.value.result_etudiant=true

        axios.post('/filter/etudiant-filter',paramsEtudiant.value)
            .then((response)=>{
                result_etudiants.value=response.data
                pending.value.result_etudiant=false
            })
            .catch((error)=>{
                pending.value.result_etudiant=false
                Toasting.errorDefault('Etudiants',error)
            })
    }

    const setTools=(payload)=>{
        tools.value=payload
    }

    const setShowResult=(payload)=>{
        show_result.value=payload
    }

    //action

    const generatePdf=()=>{

        show_pdf.value=false
        pending.value.pdf=true



        axios.post('/pdf/generate-list-etudiant-with-note',tools.value,{
            responseType: 'blob' // Spécifie le type de réponse attendu comme un objet Blob
          })
            .then((response)=>{

                pdf.value=response.data
                pending.value.pdf=false
                show_pdf.value=true
                DataIO.download(response.data,"liste_etudiant_avec_note","pdf")

            })
            .catch((error)=>{
                pending.value.pdf=false
                Toasting.errorDefault('Etudiants',error)
            })
    }

    const updateDataNote=(data)=>{
        results.value.forEach((el,index)=>{
            if(el.id===data.id){
                results.value[index].valeur=data.valeur
                results.value[index].is_set=1
            }
        })
    }


    return {
        getResults,
        getResultsJury,
        getPending,
        getShowResult,
        getShowPdf,
        getPdf,
        getResultEtudiants,
        getListeEtudiantJury,
        updateDataNote,
        setResults,
        setParamsEtudiant,
        setResultEtudiants,
        setListeEtudiantJury,
        setTools,
        setShowResult,
        setResultsJury,
        generatePdf

    }




})
