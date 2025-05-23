import { createApp } from 'vue'
import DomainChecker from './components/DomainChecker.vue'

const app = createApp({})
app.component('domain-checker', DomainChecker)
app.mount('#app')
