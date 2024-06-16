import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'

import PrimeVue from 'primevue/config'

import 'primevue/resources/themes/aura-light-green/theme.css'
import 'primevue/resources/primevue.min.css'

import ToastService from 'primevue/toastservice'
import Tooltip from 'primevue/tooltip'

import ConfirmationService from 'primevue/confirmationservice'
import { BootstrapIconsPlugin } from 'bootstrap-icons-vue'

import ClickOutside from '@/directive/ClickOutsideDirective'

import './index.css'

const pinia = createPinia()
const app = createApp(App)
app.use(pinia)

app.directive('click-outside', ClickOutside)
app.directive('tooltip', Tooltip)

app.use(router)
app.use(PrimeVue)
app.use(ToastService)
app.use(ConfirmationService)
app.use(BootstrapIconsPlugin)

app.mount('#app')
