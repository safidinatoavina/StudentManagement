<template>


    <div class="bg-light rounded p-4 p-sm-5" ref="login_from">
        <div class="mb-3 text-center">
            <h3 class="text-primary text-decoration-underline">Espace enseignant</h3>
        </div>
        <div class="form-floating mb-3">
            <input v-model="form.login" 
            type="text" class="form-control" id="login-input" placeholder="login"
            >
            <label for="login-input">Identifiant</label>

        </div>
        <div class="form-floating position-relative">
            <input v-model="form.password" 
                :type="password_visible?'text':'password'" class="form-control" id="password-input" placeholder="Password"
            >
            <span v-show="!password_visible" @click="password_visible=true"
            class="position-absolute eye-seen" >
                <icon-eye />
            </span>
            <span v-show="password_visible"  @click="password_visible=false"
            class="position-absolute eye-seent">
                <icon-eye-seent />
            </span>
            <label for="password-input">Mot de passe</label>

        </div>
        <div class="form-floating">
            <Spinner class-color="text-primary" v-if="authStore.getPending.captcha" />
            <img class="my-2 img-captcha" v-else :src="authStore.getCaptcha.img" alt="captcha"  >
            <div class="btn btn-primary ms-1 p-1" @click="authStore.refreshCaptcha()">
                <RefreshIcon/>
            </div>
            <div>
                <input type="text" class="text-center" v-model="form.captcha" placeholder="Captcha" >
            </div>
        </div>

        <div v-if="error.message"
        class="ms-1 text-danger error-data mt-2"
        >{{ error.message }}</div>

        <button @click="submit"
        type="submit" class="btn btn-primary py-3 w-100 mb-4 mt-4"
        :disabled="pending"
        >
            
            <div>
                <span>Connecter</span>
                <Spinner v-if="pending" />

            </div>

        </button>
        

    </div>



</template>

<script setup>
import IconEyeSeent from '../../icons/IconEyeSeent.vue'
import IconEye from '../../icons/IconEye.vue'
import { reactive, ref } from '@vue/reactivity'
import { useAuthStore } from '@/stores/auth'
import axios from 'axios'
import Spinner from '@/components/annimate/Spinner.vue';
import RefreshIcon from '../../icons/RefreshIcon.vue';
import { onMounted } from 'vue';


const authStore=useAuthStore()
const password_visible=ref(false)

const form = reactive({
    login:'',
    password:'',
    captcha:'',
    key: '',
    sensitive: ''
})

const login_from=ref('')

const pending=ref(false)

const error=reactive({
    message:null
})

const submit=()=>{

    pending.value=true
    error.message=null

    form.key=authStore.getCaptcha.key
    form.sensitive=authStore.getCaptcha.sensitive

    axios.post('/login',form )
    .then((response)=>{
        
        authStore.setUser(response.data.user)
        authStore.setToken(response.data.auth_token)
        pending.value=false
        document.querySelector('div.modal-backdrop.fade.show').remove()
        document.querySelector('body.modal-open')?.removeAttribute("style")
        document.querySelector('body.modal-open')?.removeAttribute("class")

        authStore.useProfesseur()

    })
    .catch((errors)=>{

        authStore.refreshCaptcha()
        
        let message=''

        for(let i in errors.response.data?.errors||{}){
            errors.response?.data?.errors[i].forEach(element => {
                message+=element+' '
             });
        }
        
        error.message=message||errors.response?.data.message
        
        pending.value=false
    })

}


</script>


<style scoped>
.error-data{
    font-size: 13px;
}

.img-captcha{
    width:150px;
    height: 40px;
}

.eye-seen,.eye-seent{
    top: 14px;
    right: 13px;
    cursor:pointer;
}



</style>
