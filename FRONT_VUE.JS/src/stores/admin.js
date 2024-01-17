import { defineStore } from 'pinia'
import { ref,computed } from 'vue'

import axios from 'axios'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useUser } from '@/stores/user'
import { useToasting } from '@/stores/toasting'

export const useAdmin = defineStore('admin', () => {

    const Auth=useAuthStore()
    const User=useUser()
    
    const admins=ref([])

    const printToast=ref({})

    const Toasting=useToasting()

    const pending=ref({
        delete_role:{}
    })

    const errors=ref({})

    const router=useRouter()
    const route=useRoute()

    //getter

    const getAdmins=computed(()=>admins.value)
    const getPending=computed(()=>pending.value)
    const getErrors=computed(()=>errors.value)


    const getAdmin=(id)=>{

        return admins.value.find(el=>el.id==id)

    }



    //setter

    const setPrintToast=(option,value)=>{
        printToast.value[option]=value
    }

    const setAdmins=()=>{

        pending.value.list_admin=true

        axios.get('/admins')
        .then((response)=>{
            
            admins.value=response.data
            pending.value.list_admin=false

        })
        .catch((error)=>{
            Toasting.errorDefault("Admin",error);
            pending.value.list_admin=false
        })
    }

    const setAdminList=(payload)=>{
        admins.value=payload
    }

    const deleteAdmin=(id)=>{

        pending.value.delete_pending=true

        axios.delete(`/admins/delete/${id}`).then((result)=>{
    
            pending.value.delete_pending=false
            admins.value.forEach((el,index)=>{
                if(el.id===id)
                    admins.value.splice(index,1)
            })

            User.getListUsers()

            Toasting.success("Suppression", "Suppression succès")
    
            //fermer le modal
            document.getElementById(`close-modal-${id}`).click()
        })
        .catch((error)=>{
            pending.value.delete_pending=false
            Toasting.errorDefault("suppression",error);
        })
    }

    const deleteRole=(user_id,role_id,cin)=>{

        let c=null
    
        admins.value.forEach((element,index)=>{
                if(element.id==user_id){
                    admins.value[index].roles.forEach((el,i)=>{
                        if(el.id==role_id)
                            c=confirm(`Vous voulez vraiment supprimer le role '${el.type}' pour '${element.personne.nom+' '+element.personne.prenom}' ?`)
                    })
                }
            })
    
        if(!c)
            return
    
        pending.value.delete_role[role_id]=true
        pending.value.delete_role[user_id]=true
        pending.value.delete_role[cin]=true

    
        axios.delete(`/role/delete/${user_id}/${role_id}`)
            .then((response)=>{
                admins.value.forEach((element,index)=>{
                    if(element.id==user_id){
                        admins.value[index].roles.forEach((el,i)=>{
                            if(el.id==role_id)
                                admins.value[index].roles.splice(i,1)
                        })
                    }
                })

                if(user_id==Auth.getUser.id){
                    Auth.refresh()
                }
    
                pending.value.delete_role[role_id]=false
                pending.value.delete_role[user_id]=false
                pending.value.delete_role[cin]=false

                
            })
            .catch((error)=>{

                Toasting.errorDefault("suppression",error);
                pending.value.delete_role[role_id]=false
                pending.value.delete_role[user_id]=false
                pending.value.delete_role[cin]=false

            })
    
     }



     const createtAdmin=(data)=>{

        pending.value.add_admin=true
        errors.value={}
        axios.post('/admins/create',data,{
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          })
        .then((response)=>{

            User.getListUsers()

            admins.value=response.data
            pending.value.add_admin=false

            printToast.value.created=true

            router.push({name:'gestion-admin'})

        })
        .catch((error)=>{
            Toasting.errorDefault("Création admin",error);
            if(error.response?.status==422){
                console.log(error)
                errors.value=error.response.data?.errors
            }
            pending.value.add_admin=false
        })
     }


     const updateAdmin=(data,callback=()=>{},callback_error=()=>{},id=null)=>{

        pending.value.update_admin=true
        errors.value={}

        var user_id=id||route.params.id

        axios.post(`/admins/update/${id||route.params.id}`,data,{
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          })
        .then((response)=>{

            User.getListUsers()

            admins.value=response.data
            pending.value.update_admin=false

            printToast.value.updated=true

            callback()

            if(user_id==Auth.getUser.id){
                Auth.refresh()
            }


        })
        .catch((error)=>{
            Toasting.errorDefault("Mis à jour Admin",error);
            if(error.response?.status==422){
                errors.value=error.response.data?.errors
            }
            pending.value.update_admin=false

            callback_error()
        })
     }




  
    return { 

        printToast,
        getAdmins,
        getPending,
        getErrors,
        getAdmin,
        setAdmins,
        deleteAdmin,
        deleteRole,
        createtAdmin,
        updateAdmin,
        setPrintToast,
        setAdminList
    }
})

