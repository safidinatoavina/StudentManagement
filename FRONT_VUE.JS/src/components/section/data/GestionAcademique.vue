<template>
    <div class="container">
        <h4 class="my-3 text-center text-primary text-decoration-underline">
            En cours
        </h4>
        <table class="table text-center" v-if="!Data.pendings.en_cours">
            <thead>
                <tr>
                    <th scope="col">Année universitaire</th>
                    <th scope="col">Semestre</th>
                    <th scope="col">Session</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>

                    <td>{{ Data.en_cours?.annee?.valeur }}</td>
                    <td>
                        <div v-if="!edit">
                            {{ Data.en_cours?.semestre?.semestre || 'aucun' }}
                        </div>
                        <div v-else>
                            <select v-model="data_actif.semestre_id" class="form-select" aria-label="Default select example">
                                <option v-for="semestre in Data.semestres" 
                                :key="'semestre_'+semestre.id" 
                                :value="semestre.id"
                                >
                                    {{ semestre.semestre }}
                                </option>
                            </select>                        
                        </div>
                    </td>
                    <td>
                        <div  v-if="!edit">
                            {{ Data.en_cours?.session?.session || 'aucun' }}
                        </div>
                        <div v-else>
                            <select v-model="data_actif.session_id"  class="form-select" aria-label="Default select example">
                                <option v-for="session in Data.sessions" 
                                :key="'session'+session.id" 
                                :value="session.id"
                                >
                                    {{ session.session }}
                                </option>
                            </select>                      
                        </div>
                    </td>
                    <td>
                        <div v-if="!edit">
                            <icon-edit @click="ToggleEdit" class="text-primary" style="cursor:pointer"/>
                        </div>
                        <div v-else>
                            <button @click="updateOrCreate"
                            class="btn btn-primary btn-sm m-1 py-1"
                            >Enregistrer 
                            </button>
                            <button class="btn btn-secondary btn-sm py-1" @click="cancelData">Annuler</button>
                        </div>
                    </td>

                </tr>
            </tbody>
        </table>

        <div v-else class="text-primary my-5">
            <Loading />
        </div>

        <h4 class="my-3 text-center text-primary text-decoration-underline">
            Gestion
        </h4>
    </div>
</template>


<script setup>
import Loading from '@/components/annimate/Loading.vue'
import IconEdit from '../../icons/IconEdit.vue'
import { useData } from '@/stores/data'
import { ref } from '@vue/reactivity'

const Data=useData()
const edit=ref(false)
const data_actif=ref({})

const updateOrCreate=()=>{

    data_actif.value.id=Data.en_cours?.id||null
    Data.updateOrCreateEnCours(data_actif.value)
    edit.value=false
}

const ToggleEdit=()=>{
    data_actif.value.session_id=Data.en_cours?.session_id||undefined
    data_actif.value.semestre_id=Data.en_cours?.semestre_id||undefined
    edit.value=!edit.value
}

const cancelData=()=>{
    data_actif.value={}
    edit.value=false
}
</script>


