import { defineStore } from 'pinia'
import { ref,computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useToasting } from '@/stores/toasting'

import axios from 'axios'

export const useData = defineStore('data', () => {

    const data_faculte=ref(null)

    const pending=ref({})
    const errors=ref({})
    const Auth=useAuthStore()
    const auth_parcour = ref({})
    const nombre_ue_oblis = ref([]);
    const Toasting=useToasting()

    const mentions=computed(()=>data_faculte.value?.mentions)
    const grades=computed(()=>data_faculte.value?.grades)
    const parcours=computed(()=>data_faculte.value?.parcours)
    const annees=computed(()=>data_faculte.value?.annees)
    const ues=computed(()=>data_faculte.value?.ues)
    const matiers=computed(()=>data_faculte.value?.matiers)
    const tps    = computed(()=> data_faculte.value.tps)
    const en_cours=computed(()=>data_faculte.value?.en_cours)
    const semestres=computed(()=>data_faculte.value?.semestres)
    const sessions=computed(()=>data_faculte.value?.sessions)
    const all_data=computed(()=>data_faculte.value||{})
    const JuryParcour = computed(() => auth_parcour.value)
    const getNombreUeObli=computed(()=>nombre_ue_oblis.value)



    const pendings=computed(()=>pending.value)
    const error =computed(()=>errors.value)


    const setDataFaculte=()=>{

        //verifier si le donnéé a ete déja pret
        //juste pour eviter de repeter la requete
        if(data_faculte.value?.parcours)
            return

        pending.value.loading_data=true

        axios.get('/data-faculte/all')
        .then((response)=>{
            data_faculte.value=response.data
            pending.value.loading_data=false
        })
        .catch((error)=>{
            pending.value.loading_data=false
        })
    }

    const addAnnee=(data,callback=()=>{})=>{
        pending.value.add_annee=true
        axios.post('/data-faculte/add-annee',data)
        .then((response)=>{
            data_faculte.value.annees=response.data
            pending.value.add_annee=false
            errors.value.annee=undefined
            callback()
        })
        .catch((error)=>{
            errors.value.annee=error.response?.data?.errors
            pending.value.add_annee=false
        })
    }

    const updateAnnee=(data,callback=()=>{})=>{


        let is_ok=true 

        data_faculte.value.annees.forEach(element=>{

            if(element.statut=='1'){
                if(element.id==data.id && data.statut!=1){
                    alert('Il faut une annee universitaire actif')
                    is_ok=false
                }
            }

        })

        if(!is_ok)
            return false


        pending.value.update_annee=true
        axios.post(`/data-faculte/update-statut-annee/${data.id}`,data)
            .then((response)=>{
                data_faculte.value.annees=response.data.annees
                data_faculte.value.en_cours=response.data.en_cours
                pending.value.update_annee=false
                errors.value.annee=undefined
                callback()
            }).catch((error)=>{
                errors.value.annee=error.response?.data?.errors
                pending.value.update_annee=false
            })

    }

    const updateOrCreateEnCours=(data)=>{

        pending.value.en_cours=true

        axios.post('/data-faculte/gestion-active',data)
        .then((response)=>{
            data_faculte.value.en_cours=response.data
            pending.value.en_cours = false
            Toasting.success('Gestion','succès')
        })
        .catch((error)=>{
            Toasting.errorDefault("Gestion",error);
            pending.value.en_cours=false
        })
    }

    const setAnnees=(payload)=>{
        if(data_faculte.value===null){
            data_faculte.value={
                annees: payload
            }
        }
    }


    const addUe=(data,callback=()=>{})=>{
        
        pending.value.add_ue=true
        axios.post('/data-faculte/add-ue',data)
        .then((response)=>{
            data_faculte.value.ues.push(response.data)
            pending.value.add_ue=false
            errors.value.ue=undefined
            callback()
        })
        .catch((error)=>{
            errors.value.ue=error.response?.data?.errors
            pending.value.add_ue=false
        })
    }

    const addTP=(data,callback=()=>{})=>{
        
        pending.value.add_tp=true
        axios.post('/data-faculte/add-tp',data)
        .then((response)=>{
            data_faculte.value.tps.push(response.data)
            pending.value.add_tp=false
            errors.value.tp=undefined
            callback()
            Toasting.success('Ajout TP',"Ajout TP succès")
        })
        .catch((error)=>{
            errors.value.tp=error.response?.data?.errors
            pending.value.add_tp=false
            Toasting.errorDefault("Ajout TP",error)
        })
    }

    const updateTP=(data,callback=()=>{})=>{
        
        pending.value.update_tp={}
        pending.value.update_tp[data.id]=true

        let payload={
            user_id:data.professeur.id,
            tp     :data.tp
        }

        axios.post('/data-faculte/update-tp/'+data.id,payload)
        .then((response)=>{
            data_faculte.value.tps.forEach((el,index)=>{
                if(el.id==data.id){
                    data_faculte.value.tps[index]=response.data
                }
            })
            pending.value.update_tp[data.id]=false
            errors.value.tp=undefined
            callback()
            let close=document.getElementById(`edit-tp-${data.id}`)
            close?.click()
            Toasting.success('Mis à jout TP',"Mis à jout TP succès")
        })
        .catch((error)=>{
            console.log(error)
            errors.value.tp=error.response?.data?.errors
            pending.value.update_tp[data.id]=false
            Toasting.errorDefault("Mis à jout TP",error)
        })
    }

    const setUes=(ues)=>{
        data_faculte.value.ues=ues;
    }

    const addMention=(data,callback=()=>{})=>{
        pending.value.add_mention=true

        axios.post('/data-faculte/add-mention',data)
            .then((response)=>{
                data_faculte.value.mentions.push(response.data)
                pending.value.add_mention=false
                errors.value.mention=undefined
                callback()
            })
            .catch((error)=>{
                errors.value.mention=error.response?.data?.errors
                pending.value.add_mention=false
            })
    }

    const updateMention=(id,data,callback=()=>{})=>{
        pending.value.update_mention=true

        axios.post(`/data-faculte/update-mention/${id}`,data)
            .then((response)=>{
                data_faculte.value.mentions.forEach((el,index)=>{
                    if(el.id==id){
                        data_faculte.value.mentions[index].mention=data.mention
                        data_faculte.value.mentions[index].abreviation=data.abreviation
                    }
                })
                pending.value.update_mention=false
                errors.value.mention=undefined
                callback()
            })
            .catch((error)=>{
                errors.value.mention=error.response?.data?.errors
                pending.value.update_mention=false
            })
    }

    const addGrade=(data,callback=()=>{})=>{
        pending.value.add_grade=true
        axios.post('/data-faculte/add-grade',data)
            .then((response)=>{

                data_faculte.value.grades.push(response.data)
                pending.value.add_grade=false
                errors.value.grade=undefined
                callback()
            })
            .catch((error)=>{
                errors.value.grade=error.response?.data?.errors
                pending.value.add_grade=false
            })
    }


    const addParcour=(data,callback=()=>{})=>{
        pending.value.add_parcour=true
        axios.post('/data-faculte/add-parcour',data)
            .then((response)=>{
                data_faculte.value.parcours.push(response.data)
                pending.value.add_parcour=false
                errors.value.parcour=undefined
                callback()

            })
            .catch((error)=>{
                errors.value.parcour=error.response?.data?.errors
                pending.value.add_parcour=false
            })
    }

    const updateParcour=(data,callback=()=>{})=>{

        pending.value.update_parcour=true
        let payload={}
        payload.parcour=data.parcour
        payload.abreviation=data.abreviation
        payload.jury_id=data.jury?.id
        payload.responsable_id=data.responsable?.id

        axios.post(`/data-faculte/update-parcour/${data.id}`,payload)
            .then((response)=>{
                data_faculte.value.parcours.forEach((element,index)=>{
                    if(element.id===data.id){
                        data_faculte.value.parcours.splice(index,1,response.data)
                    }
                })
                pending.value.update_parcour=false
                errors.value.parcour=undefined
                callback()
            })
            .catch((error)=>{
                errors.value.parcour=error.response?.data?.errors
                pending.value.update_parcour=false
            })
    }

    const getJuryParcour=()=>{

        pending.value.get_jury_parcour=true
        axios.get(`/data-faculte/get-parcour-jury/${Auth.getUser.id}`)
            .then((response)=>{
                pending.value.get_jury_parcour=false
                auth_parcour.value=response.data
            })
            .catch((error)=>{
                pending.value.get_jury_parcour=false
                Toasting.errorDefault("Parcours",error);
            })

    }

    const setMatieres=(payload)=>{
        data_faculte.value.matiers=payload
    }


    const addMatiere=(data,callback=()=>{})=>{

        pending.value.add_matiere=true

        axios.post('/data-faculte/add-matiere',data)
        .then((response)=>{
            data_faculte.value.matiers.push(response.data)
            pending.value.add_matiere=false
            errors.value.matiere=undefined
            callback()
        })
        .catch((error)=>{
            Toasting.errorDefault('Ajout ECUE',error)
            errors.value.matiere = error.response?.data?.errors
            pending.value.add_matiere=false
        })
    }

    const editMatiere=(data)=>{

        const payload={}

        payload.user_id       =  data.professeur?.id
        payload.matiere       =  data.matiere 
        payload.coefficient   =  data.coefficient 
        payload.ue_id         =  data.ue?.id
        payload.semestre_id   =  data.semestre?.id
        payload.parcour_id    =  data.parcour?.id

        pending.value.edit_matiere={}
        pending.value.edit_matiere[data.id]=true

        axios.put('/data-faculte/update-matiere/'+data.id,payload)
        .then((response)=>{
            data_faculte.value.matiers.forEach((el,index)=>{
                if(el.id==data.id){
                    data_faculte.value.matiers[index]=response.data
                }
            })
            Toasting.success('Mis à jourECUE',"Mis à jour matière succès")
            let close=document.getElementById(`edit-matiere-${data.id}`)
            close?.click()
            pending.value.edit_matiere[data.id]=false
            errors.value.matiere=undefined
        })
        .catch((error)=>{
            Toasting.errorDefault('Edit ECUE',error)
            pending.value.edit_matiere[data.id]=false
        })
    }

    const editUE=(data)=>{

        const payload={}

        payload.ue            =  data.ue
        payload.credit        =  data.credit
        payload.semestre_id   =  data.semestre?.id
        payload.parcour_id    =  data.parcour?.id

        pending.value.edit_ue={}
        pending.value.edit_ue[data.id]=true

        axios.put('/data-faculte/update-ue/'+data.id,payload)
        .then((response)=>{
            data_faculte.value.ues.forEach((el,index)=>{
                if(el.id==data.id){
                    data_faculte.value.ues[index]=response.data
                }
            })
            Toasting.success('Mis à jour UE',"Mis à jour UE succès")
            let close=document.getElementById(`edit-ue-${data.id}`)
            close?.click()
            pending.value.edit_ue[data.id]=false
            errors.value.matiere=undefined
        })
        .catch((error)=>{
            Toasting.errorDefault('Edit UE',error)
            pending.value.edit_ue[data.id]=false
        })
    }


    const setStatusMatiere=(matiere_id,value)=>{

        pending.value.edit_matiere_status={}
        pending.value.edit_matiere_status[matiere_id]=true

        axios.put('/data-faculte/update-matiere-status/'+matiere_id,{status:value})
        .then((response)=>{
            Toasting.success('Mis à jour statut matière',"Mis à jour statut matière succès")
            pending.value.edit_matiere_status[matiere_id]=false
            errors.value.matiere=undefined
        })
        .catch((error)=>{
            Toasting.errorDefault('Edit statutECUE',error)
            pending.value.edit_matiere_status[matiere_id]=false
        })
    }


    const deleteAnnee=(id,callback=()=>{})=>{

        let c = confirm('vous voulez vraiment le supprimer? ceci va suprimer tous ce qui est en relation avec cette valeur comme le note lié à cette année')
        if(!c)
            return

        pending.value.del_annee={}
        pending.value.del_annee[id]=true
        axios.delete('/data-faculte/delete-annee/'+id)
            .then((response)=>{
                data_faculte.value.annees.forEach((element,index) => {
                    if(element.id==id){
                        data_faculte.value.annees.splice(index,1)
                    }
                });

                pending.value.del_annee[id]=false

                callback()
            })
            .catch((error)=>{
                //error
            })
    }


    // action

    const deleteMention=(id,callback=()=>{})=>{

        let c = confirm('vous voulez vraiment le supprimer?')
        if(!c)
            return

        pending.value.del_mention={}
        pending.value.del_mention[id]=true
        axios.delete('/data-faculte/delete-mention/'+id)
            .then((response)=>{
                data_faculte.value.mentions.forEach((element,index) => {
                    if(element.id==id){
                        data_faculte.value.mentions.splice(index,1)
                    }
                });

                pending.value.del_mention[id]=false

                callback()
            })
            .catch((error)=>{
                //error
            })
    }

    const deleteGrade=(id,callback=()=>{})=>{

        let c = confirm('vous voulez vraiment le supprimer?')
        if(!c)
            return

        pending.value.del_grade={}
        pending.value.del_grade[id]=true
        axios.delete('/data-faculte/delete-grade/'+id)
            .then((response)=>{
                data_faculte.value.grades.forEach((element,index) => {
                    if(element.id==id){
                        data_faculte.value.grades.splice(index,1)
                    }
                });

                pending.value.del_grade[id]=false

                callback()
            })
            .catch((error)=>{
                //error
            })
    }

    const deleteParcour=(id,callback=()=>{})=>{
        let c = confirm('vous voulez vraiment le supprimer?')
        if(!c)
            return

        pending.value.del_parcour={}
        pending.value.del_parcour[id]=true
        axios.delete('/data-faculte/delete-parcour/'+id)
            .then((response)=>{
                data_faculte.value.parcours.forEach((element,index) => {
                    if(element.id==id){
                        data_faculte.value.parcours.splice(index,1)
                    }
                });
                pending.value.del_parcour[id]=false
                callback()
            })
            .catch((error)=>{
                //error
            })
    }

    const deleteUe=(id,callback=()=>{})=>{
        let c = confirm('vous voulez vraiment le supprimer?')
        if(!c)
            return

        pending.value.del_ue={}
        pending.value.del_ue[id]=true
        axios.delete('/data-faculte/delete-ue/'+id)
            .then((response)=>{
                data_faculte.value.ues.forEach((element,index) => {
                    if(element.id==id){
                        data_faculte.value.ues.splice(index,1)
                    }
                });
                pending.value.del_ue[id]=false
                callback()
            })
            .catch((error)=>{
                //error
            })
    }




    const deleteMatiere=(id,callback=()=>{})=>{

        let c = confirm('vous voulez vraiment le supprimer?')
        if(!c)
            return

        pending.value.del_matiere={}
        pending.value.del_matiere[id]=true
        axios.delete('/data-faculte/delete-matiere/'+id)
            .then((response)=>{

                data_faculte.value.matiers.forEach((element,index) => {
                    if(element.id==id){
                        data_faculte.value.matiers.splice(index,1)
                    }
                });

                pending.value.del_matiere[id]=false

                callback()

            })
            .catch((error)=>{
                //error
            })
    }

    const deleteTP=(id,callback=()=>{})=>{

        let c = confirm('vous voulez vraiment le supprimer?')
        if(!c)
            return

        pending.value.del_tp={}
        pending.value.del_tp[id]=true
        axios.delete('/data-faculte/delete-tp/'+id)
            .then((response)=>{

                data_faculte.value?.tps?.forEach((element,index) => {
                    if(element.id==id){
                        data_faculte.value?.tps?.splice(index,1)
                    }
                });

                pending.value.del_tp[id]=false

                callback()

            })
            .catch((error)=>{
                //error
            })
    }


    const setNombreUeOptionObli = (data) => {

        pending.value.set_option = true
        
        axios.post('/data-faculte/set-nombre-ue-obi-parmi-option',data)
            .then((response)=>{
                pending.value.set_option = false
                nombre_ue_oblis.value=response.data
                Toasting.success('nombre ue obli parmi options','succès')
            })
            .catch((error)=>{
                Toasting.errorDefault('nombre ue obli parmi options',error)
                pending.value.set_option=false
            })
        
        
    }

    const getNombreUeOptionObli = (data) => {

        pending.value.get_option = true
        
        axios.get('/data-faculte/get-nombre-ue-obi-parmi-option',{params:data})
            .then((response) => {
                nombre_ue_oblis.value=response.data
                pending.value.get_option = false
            })
            .catch((error)=>{
                Toasting.errorDefault('nombre ue obli parmi options',error)
                pending.value.get_option=false
            })
        
        
    }



    return {
        data_faculte,
        mentions,
        grades,
        parcours,
        annees,
        matiers,
        tps,
        pendings,
        ues,
        en_cours,
        error,
        semestres,
        sessions,
        all_data,
        JuryParcour,
        getNombreUeObli,
        setAnnees,
        deleteTP,
        setStatusMatiere,
        updateMention,
        setDataFaculte,
        addMention,
        addGrade,
        addTP,
        addParcour,
        updateParcour,
        updateTP,
        addAnnee,
        updateAnnee,
        addMatiere,
        editMatiere,
        editUE,
        getJuryParcour,
        updateOrCreateEnCours,
        setNombreUeOptionObli,
        getNombreUeOptionObli,
        addUe,
        setUes,
        setMatieres,
        deleteMention,
        deleteParcour,
        deleteGrade,
        deleteAnnee,
        deleteUe,
        deleteMatiere

    }

})