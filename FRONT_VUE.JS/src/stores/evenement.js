import { defineStore } from 'pinia'
import { ref,computed } from 'vue'
import axios from 'axios'
import { useToasting } from "@/stores/toasting"
import { useRouter } from 'vue-router'

export const useEvenement = defineStore('evenement', () => {

    const Toasting=useToasting()
    const router= useRouter()

    const evenements=ref([]);
    const pending=ref({delete:{}})

    const getEvenements=computed(()=>evenements.value)
    const getPending=computed(()=>pending.value)

    const fetchEvenement=()=>{

        if(evenements.value.length)
            return

        pending.value.fetch=true

        axios.get('/evenement/get-evenement')
        .then((response)=>{
            evenements.value=response.data
            pending.value.fetch=false
        })
        .catch((error)=>{
            pending.value.fetch=false
            Toasting.errorDefault('Erreur événement',error)
        })

    }


    const showEvenement=(evenement,model)=>{
        pending.value.show=true

        axios.get('/evenement/show-evenement/'+evenement)
        .then((response)=>{
            model.value.titre=response.data.titre
            model.value.contenu=response.data.contenu
            model.value.is_active=response.data.is_active
            pending.value.show=false
        })
        .catch((error)=>{
            pending.value.show=false
            Toasting.error('Erreur événement',"évènement introuvable")
            router.push({name:'error-404'})
        })

    }


    const saveEvenement=(payload)=>{

        pending.value.save=true

        axios.post('/evenement/store-evenement',payload)
        .then((response)=>{
            evenements.value.unshift(response.data)
            pending.value.save=false
            Toasting.success('Ajout événement',"Ajout événement succès")
            router.push({name:'gestion-evenement'})
        })
        .catch((error)=>{
            pending.value.save=false
            Toasting.errorDefault('Ajout événement',error)
        })

    }

    const updateEvenement=(evenement,payload)=>{

        pending.value.update=true

        axios.post(`/evenement/update-evenement/${evenement}`,payload)
        .then((response)=>{
            pending.value.update=false
            evenements.value.forEach((el,index)=>{
                if(el.id==evenement)
                    evenements.value.splice(index,1,response.data)
            })
            Toasting.success('Mis à jour événement',"Mis à jour événement succès")
        })
        .catch((error)=>{
            pending.value.update=false
            Toasting.errorDefault('Mis à jour événement',error)
        })

    }

    const updateStatus=(evenement,status)=>{
        
        pending.value.update_status=true

        axios.post('/evenement/update-status-evenement/'+evenement,{is_active:status})
        .then((response)=>{
            pending.value.update_status=false
            Toasting.success('Mis à jour événement',"Mis à jour statut événement succès")
        })
        .catch((error)=>{
            pending.value.update_status=false
            Toasting.errorDefault('Mis à jour événement',error)
        })

    }

    const deleteEvenement=(evenement)=>{

        if(!confirm('Voulez vous vraiment supprimer cette évènement ?'))
            return

        pending.value.delete[evenement]=true

        axios.delete(`/evenement/delete-evenement/${evenement}`)
        .then((response)=>{
            evenements.value.forEach((el,index)=>{
                if(el.id==evenement)
                    evenements.value.splice(index,1)
            })
            pending.value.delete[evenement]=false
            Toasting.success('Suppréssion événement',"Suppréssion événement succès")
        })
        .catch((error)=>{
            pending.value.delete[evenement]=false
            Toasting.errorDefault('Suppréssion évènement',error)
        })

    }


    return {
        getEvenements,
        getPending,
        fetchEvenement,
        showEvenement,
        saveEvenement,
        updateEvenement,
        updateStatus,
        deleteEvenement
    }

})