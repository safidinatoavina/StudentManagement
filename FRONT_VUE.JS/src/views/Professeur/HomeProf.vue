<template>
	<div class="mt-5">
        <div class="my-3 text-center">
            <img
                class="rounded-circle"
                :src="AuthStore.getUser?.personne.photo?.url||'/img/user.jpg'"
                alt=""
                style="width: 200px; height: 200px"
            />
        </div>
        <h3 class="text-center text-decoration-underline text-primary text-capitalize">
            {{ AuthStore.getUser?.personne.nom  }} {{ AuthStore.getUser?.personne?.prenom||'' }}
        </h3>
        <div class=" container mt-5 text-center" v-if="Data.en_cours&&Data.annees">
            <h4>{{ Data.annees?.valeur }}</h4>
            <div class="row">
                <div class="col">
                    <strong>Semestre: </strong>{{ Data.en_cours?.semestre.semestre  }}
                </div>
                <div class="col">
                    <strong>Session: </strong> {{ Data.en_cours?.session.session  }}
                </div>
            </div>
        </div>
	</div>
</template>

<script setup>

import { useAuthStore } from '@/stores/auth'
import { useData } from '@/stores/data'


const AuthStore=useAuthStore()
const Data=useData()


</script>   