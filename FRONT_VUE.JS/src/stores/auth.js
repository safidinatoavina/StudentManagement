import { defineStore } from 'pinia'
import { ref,computed } from 'vue'
import { useRouter } from 'vue-router'
import { useToasting } from '@/stores/toasting'
import axios from 'axios'

export const useAuthStore = defineStore('auth', () => {
    

    const Toasting=useToasting()

    const user=ref(null)
    
    const token=ref(null)

    const router=useRouter()

    const matieres=ref([])

    const matieres_tp=ref([])

    const pending=ref({})

    const captcha=ref({})


    //getters
    
    const getToken=computed(()=>token.value)

    const getPending=computed(()=>pending.value)

    const getCaptcha=computed(()=>captcha.value)

    const getUser=computed(()=>user.value)

    const isAuth=computed(()=>{
        return token.value!==null && user.value!==null
    })

    const getMatieres=computed(()=>matieres.value)

    const getMatieresTP=computed(()=>matieres_tp.value)

    const Responsable=computed(()=>user.value.parcour_responsables)
    const Parcours = computed(()=>user.value.parcours)

    const isAdmin=computed(()=>!!user.value?.roles.find(role=>role.id==1))
    const isJury=computed(()=>!!user.value?.roles.find(role=>role.id==2))
    const isProfesseur=computed(()=>!!user.value?.roles.find(role=>role.id==3))
    const isSecretaire=computed(()=>!!user.value?.roles.find(role=>role.id==4))
    const isReinscription=computed(()=>!!user.value?.roles.find(role=>role.id==5))
    const isResponsableParcour=computed(()=>!!user.value?.roles.find(role=>role.id==6))
    const isOperateur=computed(()=>!!user.value?.roles.find(role=>role.id==7))


    //setters


    const setUser=(data)=>{
        user.value=data
    }

    const refreshCaptcha=()=>{
        pending.value.captcha=true
        axios.defaults.baseURL = import.meta.env.VITE_BACKEND_URL;
        axios.get("/captcha/api/flat")
            .then((response)=>{
                captcha.value=response.data
                pending.value.captcha=false

                setTimeout(()=>{
                    refreshCaptcha()
                },160*1000)

            }).catch((error)=>{
                pending.value.captcha=false
            })

        axios.defaults.baseURL = import.meta.env.VITE_API_URL;


    }


    const setToken=(key)=>{
        token.value=key
        axios.defaults.headers.common['Authorization'] = `Bearer ${key}`
        //TODO: il faut crypter d'abord 
        window.localStorage.setItem('token',key)
    }

    const setMatieres=()=>{
        axios.get('/professeur/matieres')
            .then((response)=>{
                matieres.value=response.data
            })
            .catch((error)=>{
                Toasting.errorDefault("Matiere",error);
            })
    }


    const setMatieresTP=()=>{
        axios.get('/professeur/matieres-tp')
            .then((response)=>{
                matieres_tp.value=response.data
            })
            .catch((error)=>{
                Toasting.errorDefault("Matiere TP",error);
            })
    }


    //actions

    const logout=()=>{

        //user.value=null

        axios.post('/logout')
            .then((response) => {
                token.value=null
                window.localStorage.removeItem('token')
                router.push({name:'home'})
            })
            .catch((error) => {
            token.value=null
            window.localStorage.removeItem('token')
            router.push({name:'home'})    
            })

    }

    const useProfesseur=()=>{

        router.push({name:'home-prof'})
    }

    const refresh=()=>{

        axios.get('/auth')
        .then((response)=>{
          setUser(response.data)
        })
        .catch((error)=>{
          logout()    
        })

    }






  
    return { 
        isAuth,
        getToken,
        getUser,
        getMatieres,
        getMatieresTP,
        isAdmin,
        isJury,
        isProfesseur,
        isSecretaire,
        isReinscription,
        isResponsableParcour,
        isOperateur,
        Responsable,
        Parcours,
        getPending,
        getCaptcha,
        refresh,
        refreshCaptcha,
        setUser,
        setToken,
        logout,
        useProfesseur,
        setMatieres,
        setMatieresTP
    }
})

