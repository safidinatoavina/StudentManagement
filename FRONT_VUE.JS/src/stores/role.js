import { defineStore } from 'pinia'
import { ref,computed } from 'vue'

import axios from 'axios'

export const useRole = defineStore('role', () => {
    
    const roles=ref([])
    

    //getters
    
    const getRoles=computed(()=>roles.value)



    //setters

    const setRoles=()=>{
        axios.get('/role/tous')
        .then((response)=>{
            roles.value=response.data
        })
    }

  
    return { 
        getRoles,
        setRoles
    }
})
