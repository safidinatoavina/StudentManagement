import { defineStore } from 'pinia'
import { ref,computed } from 'vue'

import axios from 'axios'

export const useUser = defineStore('user', () => {
    
    const users=ref([])
    
    const professeurs=ref([])



    //getters
    
    const getProfesseurs=computed(()=>professeurs.value)

    const getUsers=computed(()=>users.value)



    //setters

    const getListUsers=()=>{
        axios.get('/admins')
        .then((response)=>{
            users.value=response.data
            professeurs.value=response.data

        })
    }





  
    return { 
        getProfesseurs,
        professeurs,
        getUsers,
        getListUsers
    }
})

