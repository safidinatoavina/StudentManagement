import { defineStore } from 'pinia'
import { ref,computed } from 'vue'
import { useToast } from "primevue/usetoast";

export const useToasting = defineStore('toasting', () => {

    const Toast=useToast()

    const success=(title,message,life=3000)=>{
        Toast.add({
            severity:'success', summary: title, detail:message, life: life
        });
    }

    const error=(title,message,life=5000)=>{
        Toast.add({
            severity:'error', summary: title, detail:message, life: life
        });
    }

    const errorDefault=(title,error,life=5000)=>{

        let message=''

        for(let i in error.response?.data?.errors||{}){
            error.response?.data?.errors[i].forEach(element => {
                message+=element+' '
             });
        }

        Toast.add({
            severity:'error', summary: title, detail: message||error.response?.data.message , life: life
        });
    }

    return {
        success,
        error,
        errorDefault
    }

})
