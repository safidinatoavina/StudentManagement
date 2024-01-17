import { defineStore } from 'pinia'
import { ref,computed } from 'vue'
import { useRoute } from 'vue-router';
import { useToasting } from '@/stores/toasting'

import axios from 'axios'

export const useEtudiant = defineStore('etudiant', () => {
    
    const Toasting=useToasting()

    const etudiants=ref(null)
    const etudiantsJury=ref([])
    const pending=ref(false)
    const pending_jury=ref(false)
    const pending_action=ref({})
    const errors=ref([])
    const printToast=ref({})
    const route=useRoute()


    //getters
    
    const getEtudiants=computed(()=>etudiants.value)
    const pendingGetEtudiant=computed(()=>pending.value)
    const EtudiantJury=computed(()=>etudiantsJury.value)
    const getPendingAction=computed(()=>pending_action.value)

    const getListEtudiants=()=>{

        if(etudiants.value?.length)
            return

        pending.value=true

        axios.get('/etudiant/tous')
        .then((response)=>{
            etudiants.value=response.data
            etudiants.value.forEach((etudiant,index)=>{
                etudiants.value[index].parcour_list=getTextParcourList(etudiant)
            })
            pending.value=false
        })
        
    }

    const getTextParcourList=(etudiant)=>{

        let parcour_list='';
        etudiant.historiques.forEach(historique=>{
            parcour_list=parcour_list+' '+historique.parcour.abreviation+' '+historique.parcour.parcour
        })

        return parcour_list
    }


    const setEtudiantList=(data)=>{
        etudiants.value=data
    }

    const getEtudiantJury=()=>{

        pending_jury.value=true

        axios.get('/etudiant/jury')
            .then(response=>{
                etudiantsJury.value=response.data
                pending_jury.value=false
            })
            .catch(error=>{
                Toasting.errorDefault('Etudiants',error)
            })
    }

    //setters

    const setPrintToast=(option,value)=>{
        printToast.value[option]=value
    }

    const setEtudiant=(data,callback=()=>{})=>{

        pending.value=true

        axios.post('/etudiant/inscrit',data)
            .then((response)=>{
                etudiants.value.push(response.data)
                pending.value=false
                callback()
            })
            .catch((error)=>{
                pending.value=false
                errors.value=error.response?.data?.errors
                Toasting.errorDefault('Inscription',error)
            })
    }

    const editEtudiant=(data,callback=()=>{})=>{

        pending.value=true

        axios.post(`/etudiant/update/${route.params.id}`,data)
            .then((response)=>{
                etudiants.value.forEach((e,i)=>{
                    if(e.id==route.params.id){
                        etudiants.value[i]=response.data
                    }
                })
                pending.value=false
                callback()
            })
            .catch((error)=>{
                pending.value=false
                errors.value=error.response?.data?.errors
            })
    }

    //action 

    const deleteEtudiant=(personne,callback=()=>{})=>{


        pending_action.value[personne]=true




        axios.delete('/etudiant/delete/'+personne)
        .then((response)=>{

            let nom_prenom=''

            etudiants.value.forEach((element,index) => {
                if(element.personne.id==personne){
                    nom_prenom=`${element.personne.nom +' '+element.personne.prenom}`
                    etudiants.value.splice(index,1)
                }
            });

            callback(nom_prenom)

            pending_action.value[personne]=false

        })
        .catch((error)=>{
            Toasting.errorDefault('Etudiants',error)
        })

    }

    const deleteHistorique=(id,historiqueModel)=>{

        if(!confirm("voulez vous vraiment supprimer cette historique?"))
            return

        pending_action.value.delete_historique={}
        pending_action.value.delete_historique[id]=true

        axios.delete('/etudiant/delete-historique/'+id)
        .then((response)=>{

            Toasting.success('Suppression historique',"supperssion historique succès")

            historiqueModel.value=JSON.parse(JSON.stringify(response.data.historiques))

            pending_action.value.delete_historique[id]=false

        })
        .catch((error)=>{
            Toasting.errorDefault('Etudiants',error)

            pending_action.delete_historique[id]=false

        })
    }

    const saveNewHistorique=(new_historique,historiqueModel,model)=>{

        pending_action.value.create_historique=true

        const data={}

        data.status_id                  =    new_historique.value.status_id
        data.numeroInscription          =    new_historique.value.numeroInscription 
        data.parcour_id                 =    new_historique.value.parcour.id
        data.annee_universitaire_id     =    new_historique.value.annee_universitaire.id


        axios.post('/etudiant/create-historique/'+route.params.id,data)
        .then((response)=>{

            Toasting.success('Ajout historique',"Ajout historique succès")

            historiqueModel.value=JSON.parse(JSON.stringify(response.data.historiques))

            pending_action.value.create_historique=false
            model.value={}

        })
        .catch((error)=>{
            Toasting.errorDefault('Création historique',error)

            pending_action.value.create_historique=false

        })

    }

    //filter

    const getEtudiant=(id)=>{
        return etudiants.value?.find(el=>el.id==id)||{}
    }




    return { 
        etudiants,
        getEtudiants,
        pendingGetEtudiant,
        pending_action,
        getPendingAction,
        pending,
        printToast,
        EtudiantJury,
        getEtudiant,
        getEtudiantJury,
        setPrintToast,
        getListEtudiants,
        setEtudiant,
        editEtudiant,
        setEtudiantList,
        saveNewHistorique,
        deleteEtudiant,
        deleteHistorique
        
    }

})

