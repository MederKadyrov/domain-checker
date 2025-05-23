import { createApp } from 'vue'
import DomainChecker from './components/DomainChecker.vue'
import LoginForm from './components/Login.vue'
import RegisterForm from './components/Register.vue'
import Header from './components/Header.vue'


const app = createApp({
    mounted() {
        const token = localStorage.getItem('auth_token')
        const path = window.location.pathname

        if (!token && path === '/') {
            window.location.href = '/login'
        }
    }
})
app.component('domain-checker', DomainChecker)
app.component('login-form', LoginForm)
app.component('register-form', RegisterForm)
app.component('site-header', Header)
app.mount('#app')
