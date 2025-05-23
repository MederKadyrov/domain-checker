<template>
    <div class="login-container">
        <div class="form-box">
            <h1 class="form-title">Вход</h1>

            <input
                v-model="email"
                type="email"
                placeholder="Email"
                class="form-input"
            />

            <input
                v-model="password"
                type="password"
                placeholder="Пароль"
                class="form-input"
            />

            <button @click="login" class="form-button">
                Войти
            </button>

            <div v-if="error" class="form-error">{{ error }}</div>
            <div v-if="token" class="form-success">Токен: {{ token }}</div>

            <div class="form-link">
                Ещё нет аккаунта?
                <a href="/register">Зарегистрироваться</a>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const email = ref('')
const password = ref('')
const error = ref(null)
const token = ref(null)

const login = async () => {
    error.value = null
    token.value = null

    try {
        const response = await axios.post('/api/login', {
            email: email.value,
            password: password.value
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
        error.value = 'Ошибка входа. Проверьте данные.'
    }
}
</script>

<style scoped>
.login-container {
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
    background: #10b981;
    color: white;
    padding: 12px;
    font-size: 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.3s;
}

.form-button:hover {
    background: #059669;
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

.form-link {
    margin-top: 16px;
    text-align: center;
    font-size: 14px;
}

.form-link a {
    color: #2563eb;
    text-decoration: none;
}

.form-link a:hover {
    text-decoration: underline;
}
</style>
