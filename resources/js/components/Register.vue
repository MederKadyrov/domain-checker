<template>
    <div class="register-container">
        <div class="form-box">
            <h1 class="form-title">Регистрация</h1>

            <input v-model="name" type="text" placeholder="Имя" class="form-input" />
            <input v-model="email" type="email" placeholder="Email" class="form-input" />
            <input v-model="password" type="password" placeholder="Пароль" class="form-input" />
            <input v-model="password_confirmation" type="password" placeholder="Подтвердите пароль" class="form-input" />

            <button @click="register" class="form-button">Зарегистрироваться</button>

            <div v-if="error" class="form-error">{{ error }}</div>
            <div v-if="token" class="form-success">Токен: {{ token }}</div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const name = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')
const error = ref(null)
const token = ref(null)

const register = async () => {
    error.value = null
    token.value = null

    try {
        const response = await axios.post('/api/register', {
            name: name.value,
            email: email.value,
            password: password.value,
            password_confirmation: password_confirmation.value,
        })

        token.value = response.data.token
        localStorage.setItem('auth_token', token.value)

        const userRes = await axios.get('/api/user', {
            headers: {
                Authorization: `Bearer ${token.value}`
            }
        })

        localStorage.setItem('user_email', userRes.data.email)
        window.location.href = '/'
    } catch (err) {
        error.value = 'Ошибка регистрации. Проверьте данные.'
    }
}
</script>

<style scoped>
.register-container {
    min-height: 100vh;
    background: #f3f4f6;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.form-box {
    background: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 400px;
}

.form-title {
    text-align: center;
    font-size: 24px;
    margin-bottom: 20px;
    color: #333;
}

.form-input {
    width: 90%;
    padding: 10px 14px;
    margin-bottom: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 16px;
}

.form-button {
    width: 100%;
    background: #2563eb;
    color: white;
    padding: 12px;
    font-size: 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.3s;
}

.form-button:hover {
    background: #1e40af;
}

.form-error {
    color: #dc2626;
    text-align: center;
    margin-top: 10px;
}

.form-success {
    color: #16a34a;
    text-align: center;
    margin-top: 10px;
}
</style>
