<template>
    <header class="header">
        <h1 class="header-title">Проверка доменов</h1>

        <div class="header-actions">
            <span v-if="email" class="user-info">Вы вошли как {{ email }}</span>

            <template v-if="isLoggedIn">
                <button @click="logout" class="link-button">Выйти</button>
            </template>
            <template v-else>
                <a href="/login" class="link-button">Войти</a>
            </template>
        </div>
    </header>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const isLoggedIn = ref(false)
const email = ref(null)

onMounted(() => {
    const token = localStorage.getItem('auth_token')
    const userEmail = localStorage.getItem('user_email')

    isLoggedIn.value = !!token
    email.value = userEmail
})

const logout = () => {
    localStorage.removeItem('auth_token')
    localStorage.removeItem('user_email')
    isLoggedIn.value = false
    email.value = null
    window.location.href = '/login'
}
</script>

<style scoped>
.header {
    background: #f9fafb;
    padding: 16px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #e5e7eb;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.header-title {
    font-size: 20px;
    font-weight: 700;
    color: #1f2937;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 16px;
}

.user-info {
    color: #4b5563;
    font-size: 14px;
}

.link-button {
    background: none;
    border: none;
    color: #2563eb;
    cursor: pointer;
    font-size: 14px;
    padding: 4px 8px;
    text-decoration: none;
    transition: color 0.3s ease;
}

.link-button:hover {
    color: #1d4ed8;
    text-decoration: underline;
}
</style>
