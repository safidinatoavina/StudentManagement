<template>
    <div class="my-2">
        <h3 class="text-center text-info text-decoration-underline">Edit TP</h3>
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6">
                    <input v-model="TPModel.tp" type="text" placeholder="TP" class="form-control">
                </div>
                <div class="col-12 col-md-6">
                    <AutoComplete  v-model="TPModel.professeur"
                        :suggestions="suggestionUser" 
                        @complete="searchUser($event)" 
                        placeholder="Professeur"
                        :dropdown="true" optionLabel="personne.nom" forceSelection>

                            <template #item="slotProps">
                                <div class="country-item">
                                    <div class="ml-2">{{slotProps.item.personne.nom}} {{ slotProps.item.personne.prenom||'' }}</div>
                                </div>
                            </template>

                    </AutoComplete>
                </div>
            </div>


        </div>

        <div class="modal-footer mt-3">
            <button type="button" :id="`edit-tp-${tp.id}`" class="btn btn-secondary" @click="refreshTP()" data-bs-dismiss="modal">
                Annuler
            </button>
            <button 
            type="button" class="btn btn-primary" @click="Data.updateTP(TPModel)"
            >
                Enregistrer
                <Spinner v-if="Data.pendings.update_tp?.[tp.id]" />
            </button>
        </div>
    </div>
</template>

<script setup>
import Spinner from '../../annimate/Spinner.vue'
import AutoComplete from 'primevue/autocomplete'
import { ref, watchEffect } from 'vue'
import { useData } from '@/stores/data'
import { useAdmin } from '@/stores/admin'



const Data=useData()
const Admin=useAdmin()
const TPModel=ref({})

const suggestionUser=ref([])

const props=defineProps({
    tp: Object
})


    const searchUser=(event)=>{

        setTimeout(() => {

            const prof_list=(Admin.getAdmins.filter(e=>e.roles.find(el=>el.id===3)))
            
            if (!event.query.trim().length) {
                suggestionUser.value = [...prof_list];
            }
            else {
                suggestionUser.value = prof_list.filter((user) => {

                    let resultNom=user.personne.nom.toLowerCase().includes(event.query.toLowerCase());
                    let resultPrenom=user.personne.prenom.toLowerCase().includes(event.query.toLowerCase());

                    return resultNom || resultPrenom

                });
            }
        }, 150);
    }

    


    const refreshTP=()=>{
        TPModel.value=JSON.parse(JSON.stringify(props.tp))
    }

    watchEffect(()=>{
        refreshTP()
    })

</script>

<style scoped>

div.row{
    margin-top: 1rem;
}

</style>

