import { createApp } from 'vue'
import { createPinia } from 'pinia'
import axios from 'axios'
import App from './App.vue'
import router from './router'
import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import CKEditor from '@ckeditor/ckeditor5-vue';

import './assets/style.css'
import 'primevue/resources/themes/saga-blue/theme.css'       //theme
import 'primevue/resources/primevue.min.css '                //core css
import 'primeicons/primeicons.css '

//  config axios
axios.defaults.baseURL = import.meta.env.VITE_API_URL
//


const app = createApp(App)

app.use(createPinia())
app.use(router)
app.use(PrimeVue)
app.use(ToastService)
app.use(CKEditor)
app.mount('#app')

// app.config.errorHandler = (err, instance, info) => {
//     console.log(err)
// }
