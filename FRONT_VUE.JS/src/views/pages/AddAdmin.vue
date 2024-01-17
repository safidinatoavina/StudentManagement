<template>
    <div class="m-3">

        <section class="form-group m-2">

            <h5 class="text-info text-decoration-underline">
                Identité de l'admin
            </h5>

            <div class="row">
                <div class="col-12 col-md-6 my-2">
                    <label for="nom">Nom</label>
                    <input :class="[Admin.getErrors['nom']?'is-invalid':'']"
                    v-model="admin.nom" id="nom" type="text" class="form-control">
                    <error-form :errors="Admin.getErrors" field="nom" />
                </div>
                <div class="col-12 col-md-6 my-2">
                    <label for="prenom">Prénom(s)</label>
                    <input :class="[Admin.getErrors['prenom']?'is-invalid':'']"
                    v-model="admin.prenom" id="prenom" type="text" class="form-control">
                    <error-form :errors="Admin.getErrors" field="prenom" />
                </div>

                <div class="col-12 col-md-6 my-2">
                    <label for="date">Date de  naissance</label>
                    <input :class="[Admin.getErrors['date_naissance']?'is-invalid':'']" 
                    v-model="admin.date_naissance" id="date" type="date" class="form-control">
                    <error-form :errors="Admin.getErrors" field="date_naissance" />
                </div>
                <div class="col-12 col-md-6 my-2">
                    <label for="naissance">Lieu de naissance</label>
                    <input :class="[Admin.getErrors['lieu_naissance']?'is-invalid':'']"
                    v-model="admin.lieu_naissance" id="naissance" type="text" class="form-control">
                    <error-form :errors="Admin.getErrors" field="lieu_naissance" />
                </div>

                <div class="col-12 col-md-6 my-2">
                    <label for="address">Address</label>
                    <input :class="[Admin.getErrors['address']?'is-invalid':'']"
                    v-model="admin.address" id="address" type="text" class="form-control">
                    <error-form :errors="Admin.getErrors" field="address" />
                </div>
                <div class="col-12 col-md-6 my-2">
                    <label for="cin">N° CIN</label>
                    <input :class="[Admin.getErrors['cin']?'is-invalid':'']" 
                    v-model="admin.cin" id="cin" type="text" class="form-control">
                    <error-form :errors="Admin.getErrors" field="cin" />
                </div>

                <div class="col-12 my-2">
                    <label for="role">Role(s)</label><br>
                    <AutoComplete v-model="role" 
                    :multiple="true"
                    :suggestions="suggestionRole" 
                    @complete="searchRole($event)" 
                    placeholder=""
                    :dropdown="true" optionLabel="type" forceSelection>

                        <template #item="slotProps">
                            <div class="country-item">
                                <div class="ml-2">{{slotProps.item.type}}</div>
                            </div>
                        </template>

                    </AutoComplete>

                    <error-form :errors="Admin.getErrors" field="roles" />

                </div>

                <div class="col-12 col-md-6 my-2">

                    <div class="preview-photo mt-3 position-relative border"  v-show="imageUrl">
                        <svg @click="deletePhoto"
                        class="bi bi-x-square-fill border border-2  text-danger rounded-circle p-0 ms-2 position-absolute close-image"
                        style="top:0px;cursor:pointer"
                        xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z"/>
                        </svg>
                        <img :src="imageUrl" class="w-100"  alt="preview photo" >
                    </div>

                    <div v-show="false">
                        <input id="photo" @change="previewPhoto" type="file" class="form-control" accept="image/*">
                    </div>
                    <div class="my-3">
                        <label class="btn btn-primary" for="photo">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-upload" viewBox="0 0 16 16">
                                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/>
                            </svg>
                            <span class="ms-2">Photo</span>
                        </label>
                        <error-form :errors="Admin.getErrors" field="photo" />
                    </div>

                </div>

            </div>


            <h5 class="text-info text-decoration-underline">
                Authentification information
            </h5>

            <div class="row mt-3">

                <div class="col-12 col-md-6">
                    <label for="login">Login</label>
                    <input :class="[Admin.getErrors['login']?'is-invalid':'']"
                    v-model="admin.login" id="login" type="text" class="form-control">
                    <error-form :errors="Admin.getErrors" field="login" />
                </div>

                <div class="col-12 col-md-6 position-relative">
                    <label for="password">Password</label>
                    <input :class="[Admin.getErrors['password']?'is-invalid':'']"
                    v-model="admin.password" id="password" :type="password_visible?'text':'password'" class="form-control">
                    <span v-show="!password_visible" @click="password_visible=true"
                    class="position-absolute eye-seen" >
                        <icon-eye />
                    </span>
                    <span v-show="password_visible"  @click="password_visible=false"
                    class="position-absolute eye-seent">
                        <icon-eye-seent />
                    </span>

                    <error-form :errors="Admin.getErrors" field="password" />

                </div>

            </div>



        </section>



        <div class="mt-3">
            <button @click="Admin.createtAdmin(formData)"
             class="btn btn-primary">
             <span>Enregistrer</span>
             <spinner v-if="Admin.getPending.add_admin"></spinner>

             </button>
        </div>

    </div>
</template>

<script setup>
import ErrorForm from '../../components/message/ErrorForm.vue'
import IconEyeSeent from '../../components/icons/IconEyeSeent.vue'
import IconEye from '../../components/icons/IconEye.vue'

import Spinner from '../../components/annimate/Spinner.vue'
import AutoComplete from 'primevue/autocomplete'
import { ref } from "@vue/reactivity"
import { useRole } from '@/stores/role'
import { useEtudiant } from '@/stores/etudiant'
import { useAdmin } from '@/stores/admin'
import { computed, onBeforeMount, watchEffect } from '@vue/runtime-core'

const Role=useRole()

const admin=ref({})
const role=ref([])
const suggestionRole=ref([])

const Admin=useAdmin()
const imageUrl=ref('')
const password_visible=ref(false)

var formData = new FormData()


const addAdmin=()=>{


   // admin.value.parcour_id=parcour.value.id
    
    Admin.createtAdmin(admin.value)

}

const deletePhoto=()=>{
    imageUrl.value=''
    admin.value.photo=undefined
}

const searchRole=(event)=>{
        setTimeout(() => {
            if (!event.query.trim().length) {
                suggestionRole.value = [...Role.getRoles];
            }
            else {
                suggestionRole.value = Role.getRoles.filter((role) => {
                    return role.type.toLowerCase().includes(event.query.toLowerCase());
                });
            }
        }, 150);
    }


    
const previewPhoto=(event) =>{

    const file = event.target.files[0]
    if (!file) {
    return
    }
    const reader = new FileReader()
    reader.onload = e => {
    imageUrl.value = e.target.result
    admin.value.photo=file
    }
    reader.readAsDataURL(file)

    }

watchEffect(()=>{

    formData = new FormData()

    let sortedEntries = Object.entries(admin.value).sort((a, b) => a[0].localeCompare(b[0]));
    sortedEntries.forEach(([key, value]) => {
        formData.append(key,value)
    });


    role.value.forEach(el=>{
        formData.append('roles[]',el.id)
    })
})

onBeforeMount(()=>{
    Role.setRoles()
})

</script>

<style scoped>
.preview-photo{
    max-width: 150px;
}
.close-image{
    right: -13px;
    top: -10px!important;
}

.eye-seen,.eye-seent{
    top: 34px;
    right: 22px;
    cursor:pointer;
}
</style>




