import { defineStore } from 'pinia'
import { ref,computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useToasting } from '@/stores/toasting'
import { useDataIO } from '@/stores/data_io'
import { useStatistique } from '@/stores/statistique'

import axios from 'axios'

export const useOperation = defineStore('operation', () => {
    
    const Toasting = useToasting()
    const Statistique=useStatistique()

    const route= useRoute()

    const ValidationParUe=ref([])
    const ValidationParSemestre=ref([])
    const admis=ref([])
    const redoublants=ref([])
    const pending=ref({})
    const tools=ref({});
    const ues_jury=ref([]);
    const critere_admis=ref({})
    const critere_validations=ref({})
    const public_result=ref({})
    const DataIO=useDataIO()


    //getters

    const getPending=computed(()=>pending.value)
    const validations=computed(()=>ValidationParUe.value)
    const par_semestres=computed(()=>ValidationParSemestre.value)
    const getAdmis=computed(()=>admis.value)
    const getRedoublants=computed(()=>redoublants.value)
    const uesJury=computed(()=>ues_jury.value)
    const getCritere=computed(()=>critere_admis.value)
    const getCritereValidation =computed(()=>critere_validations.value)
    const getPublicResult=computed(()=>public_result.value)
    //setter
    const setTools=(payload)=>{
        if(!payload.parcour_id)
            payload.parcour_id=route.params.parcour
        tools.value=payload
    }
    //action

    const setValidationParUe=()=>{

        pending.value.validation_ue=true
        
        axios.post('/operation/liste-validation-ue',tools.value)
            .then((response)=>{
                ValidationParUe.value=response.data
                pending.value.validation_ue=false
            }).catch((error)=>{
                pending.value.validation_ue=false
                Toasting.errorDefault('Etudiants',error)
            })

    }

    const setValidationParMatiere=()=>{

        pending.value.validation_ue=true
        
        axios.post('/operation/liste-validation-matiere',tools.value)
            .then((response)=>{
                ValidationParUe.value=response.data
                pending.value.validation_ue=false
            }).catch((error)=>{
                pending.value.validation_ue=false
                Toasting.errorDefault('Etudiants',error)
            })

    }


    const setValidationParSemestre=()=>{
        pending.value.validation_par_semestre=true
        if(!tools.value.parcour_id)
            tools.value.parcour_id=route.params.parcour

        axios.post('/operation/liste-validation-par-semestre',tools.value)
            .then((response)=>{
                ValidationParSemestre.value=response.data
                pending.value.validation_par_semestre=false
            }).catch((error)=>{
                pending.value.validation_par_semestre=false
                Toasting.errorDefault('Etudiants',error)
            })

    }

    const fetchAdmis = (parcour) => {

        pending.value.liste_admis=true

        axios.get('/operation/fetch-admis/'+parcour)
            .then((response)=>{
                admis.value=response.data
                pending.value.liste_admis=false
            }).catch((error)=>{
                pending.value.liste_admis=false
                Toasting.errorDefault('Liste passage',error)
            })
        
    }

    const fetchRedoublant = (parcour) => {

        pending.value.liste_admis=true

        axios.get('/operation/fetch-redoublant/'+parcour)
            .then((response)=>{
                redoublants.value=response.data
                pending.value.liste_admis=false
            }).catch((error)=>{
                pending.value.liste_admis=false
                Toasting.errorDefault('Liste passage',error)
            })
        
    }


    const setAdmis=(payload)=>{
        payload.parcour_id=route.params.parcour
        pending.value.liste_admis=true
        pending.value.critere_admis=true
        
        axios.post('/operation/liste-admis',payload)
            .then((response)=>{
                admis.value=response.data
                pending.value.liste_admis=false
                pending.value.critere_admis=false
                Toasting.success('Passage',"Enregistrement succès")
            }).catch((error)=>{
                pending.value.liste_admis=false
                pending.value.critere_admis=false
                Toasting.errorDefault('Etudiants',error)
            })

    }

    const setV=(payload)=>{
        pending.value.set_v=true
        payload.parcour_id=route.params.parcour
        
        axios.post('/operation/set-critere-validation',payload)
            .then((response)=>{
                Toasting.success("critère V",'critère "V" succès')
                pending.value.set_v=false
                critere_validations.value.v=response.data.find(el=>el.type=='v')||{}
            }).catch((error)=>{
                pending.value.set_v=false
                Toasting.errorDefault('critère V',error)
            })

    }

    const setVPC=(payload)=>{

        pending.value.set_vpc=true
        payload.parcour_id=route.params.parcour
        
        axios.post('/operation/set-critere-validation',payload)
            .then((response)=>{
                Toasting.success("critère VPC",'critère "VPC" succès')
                pending.value.set_vpc=false
                critere_validations.value.vpc=response.data.find(el=>el.type=='vpc')||{}
            }).catch((error)=>{
                pending.value.set_vpc=false
                Toasting.errorDefault('critère VPC',error)
            })

    }

    const fetchValidationSemestres=()=>{

        pending.value.fetch_validation=true
        
        axios.get('/operation/get-critere-validation/'+route.params.parcour)
            .then((response)=>{
                pending.value.fetch_validation=false
                critere_validations.value.v=response.data.find(el=>el.type=='v')||{}
                critere_validations.value.vpc=response.data.find(el=>el.type=='vpc')||{}
            }).catch((error)=>{
                pending.value.fetch_validation=false
                Toasting.errorDefault('critère validation',error)
            })

    }


    const setRedoubles=(payload)=>{

        pending.value.liste_admis=true
        pending.value.critere_redouble=true
        payload.parcour_id=route.params.parcour
        
        axios.post('/operation/liste-redoublants',payload)
            .then((response)=>{
                redoublants.value=response.data
                pending.value.liste_admis=false
                pending.value.critere_redouble=false
                Toasting.success('Passage',"Enregistrement succès")
            }).catch((error)=>{
                pending.value.liste_admis=false
                pending.value.critere_redouble=false
                Toasting.errorDefault('Etudiants',error)
            })

    }

    const setCriteres=()=>{

        pending.value.critere=true
        axios.get('/operation/critere-admis/'+route.params.parcour)
        .then((response)=>{
            critere_admis.value=response.data
            pending.value.critere=false
        }).catch((error)=>{
            pending.value.critere=false
            Toasting.errorDefault('Etudiants',error)
        })

    }

    const setUeJury=()=>{
        pending.value.ue_jury=true

        axios.get('/operation/ues-jury/'+route.params.parcour)
            .then((response)=>{
                pending.value.ue_jury=false
                ues_jury.value=response.data
            }).catch((error)=>{
                Toasting.errorDefault('Jury ues',error)
                pending.value.ue_jury=false
            })
    }

    const publicResult=(payload)=>{
        pending.value.public_result={}
        pending.value.public_result[payload.ue]=true
        axios.post(`/operation/public-result/${payload.ue}/${route.params.parcour||payload.parcour}`,)
            .then((response)=>{
                Toasting.success('Publication resultat',"publication de resultat succès")
                pending.value.public_result[payload.ue]=false

                ues_jury.value.forEach((el,index)=>{
                    if(el.id==payload.ue)
                        ues_jury.value[index].ue_publics=['public']
                })

                if (payload.parcour) //si admin
                    Statistique.getStatistiqueAjoutNote()
                
            }).catch((error)=>{
                Toasting.errorDefault('Publication resultat',error)
                pending.value.public_result[payload.ue]=false
            })
    }

    const publicResultRattrapage = (payload) => {

        pending.value.public_result_rattrapage={}
        pending.value.public_result_rattrapage[payload.ue]=true
        axios.post(`/operation/public-result-rattrapage/${payload.ue}/${route.params.parcour||payload.parcour}`,)
            .then((response)=>{
                Toasting.success('Publication resultat',"publication de resultat rattrapage succès")
                pending.value.public_result_rattrapage[payload.ue]=false

                ues_jury.value.forEach((el,index)=>{
                    if(el.id==payload.ue)
                        ues_jury.value[index].ue_publics[0].avec_ratrapage=1
                })

                if (payload.parcour) //si admin
                    Statistique.getStatistiqueAjoutNote()

            }).catch((error)=>{
                Toasting.errorDefault('Publication resultat',error)
                pending.value.public_result_rattrapage[payload.ue]=false
            })
        
    }


    const publicFinalResult=()=>{
        pending.value.public_final_result=true
        axios.post(`/operation/public-final-result/${route.params.parcour}`,)
            .then((response)=>{
                Toasting.success('Publication resultat',"publication de resultat succès")
                pending.value.public_final_result=false
                public_result.value.final=1
            }).catch((error)=>{
                Toasting.errorDefault('Publication resultat',error)
                pending.value.public_final_result=false
            })
    }

    const publicSemestreResult=()=>{
        pending.value.public_semestre_result=true
        axios.post(`/operation/public-result-semestre/${route.params.parcour}`,)
            .then((response)=>{
                Toasting.success('Publication resultat',"publication de resultat succès")
                pending.value.public_semestre_result=false
                public_result.value.semestre=1
            }).catch((error)=>{
                Toasting.errorDefault('Publication resultat',error)
                pending.value.public_semestre_result=false
            })
    }

    const cancelSemestreResult=()=>{
        pending.value.cancel_semestre_result=true
        axios.post(`/operation/cancel-result-semestre/${route.params.parcour}`,)
            .then((response)=>{
                Toasting.success('Publication resultat',"publication de resultat succès")
                pending.value.cancel_semestre_result=false
                public_result.value.semestre=0
            }).catch((error)=>{
                Toasting.errorDefault('Publication resultat',error)
                pending.value.cancel_semestre_result=false
            })
    }
    
    const getPublicSemestre=()=>{
        pending.value.get_semestre_public=true
        axios.get(`/operation/get-public-semestre/${route.params.parcour}`,)
            .then((response)=>{
                pending.value.get_semestre_public=false
                public_result.value.semestre=response.data
            }).catch((error)=>{
                Toasting.errorDefault('Erreur server',error)
                pending.value.get_semestre_public=false
            })
    }

    const getPublicFinal=()=>{
        pending.value.get_final_public=true
        axios.get(`/operation/get-public-final/${route.params.parcour}`,)
            .then((response)=>{
                pending.value.get_final_public=false
                public_result.value.final=response.data
            }).catch((error)=>{
                Toasting.errorDefault('Erreur server',error)
                pending.value.get_final_public=false
            })
    }


    const cancelFinalResult=()=>{
        pending.value.cancel_final_result=true
        axios.post(`/operation/cancel-final-result/${route.params.parcour}`,)
            .then((response)=>{
                Toasting.success('Annulation resultat',"Annulation de resultat succès")
                pending.value.cancel_final_result=false
                public_result.value.final=0
            }).catch((error)=>{
                Toasting.errorDefault('Annulation resultat',error)
                pending.value.cancel_final_result=false
            })
    }

    const cancelResult=(payload)=>{
        pending.value.cancel_result={}
        pending.value.cancel_result[payload.ue]=true
        axios.post(`/operation/cancel-result/${payload.ue}/${route.params.parcour||payload.parcour}`,)
            .then((response)=>{
                Toasting.success('Annulation resultat',"Annulation de resultat succès")
                pending.value.cancel_result[payload.ue]=false
                ues_jury.value.forEach((el,index)=>{
                    if(el.id==payload.ue)
                        ues_jury.value[index].ue_publics=[]
                })

            if (payload.parcour) //si admin
                Statistique.getStatistiqueAjoutNote()
                
            }).catch((error)=>{
                Toasting.errorDefault('Annulation resultat',error)
                pending.value.cancel_result[payload.ue]=false
            })
    }


    const cancelResultRattrapage=(payload)=>{
        pending.value.cancel_result_rattrapage={}
        pending.value.cancel_result_rattrapage[payload.ue]=true
        axios.post(`/operation/cancel-result-rattrapage/${payload.ue}/${route.params.parcour||payload.parcour}`,)
            .then((response)=>{
                Toasting.success('Annulation resultat',"Annulation de resultat rattrapage succès")
                pending.value.cancel_result_rattrapage[payload.ue]=false
                ues_jury.value.forEach((el,index)=>{
                    if(el.id==payload.ue)
                        ues_jury.value[index].ue_publics[0].avec_ratrapage=0
                })

                if (payload.parcour) //si admin
                    Statistique.getStatistiqueAjoutNote()

            }).catch((error)=>{
                Toasting.errorDefault('Annulation resultat',error)
                pending.value.cancel_result_rattrapage[payload.ue]=false
            })
    }

    


    const generatePdf=(type)=>{

        pending.value.pdf=true

        var payload=tools.value
        payload.type=type

        axios.post('/operation/pdf-ue-note-or-validation',payload,{
            responseType: 'blob' // Spécifie le type de réponse attendu comme un objet Blob
          })
        .then((response)=>{
            pending.value.pdf=false
            DataIO.download(response.data,"etudiant_note","pdf")
        }).catch((error)=>{
            pending.value.pdf=false
            Toasting.errorDefault('Etudiants',error)
        })

    }

    const getListEtudinatPdf=()=>{
        pending.value.pdf=true

        axios.get('/operation/liste-etudiant/'+route.params.parcour,{
            responseType: 'blob' // Spécifie le type de réponse attendu comme un objet Blob
          })
        .then((response)=>{
            pending.value.pdf=false
            DataIO.download(response.data,"etudiant_liste","pdf")
        }).catch(error=>{
            Toasting.errorDefault('Etudiants',error)
        })
    }


    const generateDefinitivePdf=(type)=>{

        pending.value.pdf=true

        axios.post('/operation/pdf-ue-note-or-validation-definitive',{type,parcour_id:route.params.parcour},{
            responseType: 'blob' // Spécifie le type de réponse attendu comme un objet Blob
          })
        .then((response)=>{
            pending.value.pdf=false
            DataIO.download(response.data,"validation_definitive","pdf")
        }).catch((error)=>{
            pending.value.pdf=false
            Toasting.errorDefault('Etudiants',error)
        })

    }
  
    return { 
        getPending,
        validations,
        getCritereValidation,
        par_semestres,
        getAdmis,
        getRedoublants,
        uesJury,
        getCritere,
        getPublicResult,
        setVPC,
        setV,
        fetchValidationSemestres,
        setAdmis,
        fetchAdmis,
        fetchRedoublant,
        setRedoubles,
        setUeJury,
        publicResult,
        publicResultRattrapage,
        publicFinalResult,
        publicSemestreResult,
        cancelSemestreResult,
        getPublicSemestre,
        getPublicFinal,
        cancelResult,
        cancelResultRattrapage,
        cancelFinalResult,
        setValidationParSemestre,
        setValidationParUe,
        setValidationParMatiere,
        setCriteres,
        generatePdf,
        generateDefinitivePdf,
        getListEtudinatPdf,
        setTools
    }
})
