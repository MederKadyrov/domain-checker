<template>
    <div class="p-4 max-w-xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Проверка доменов</h1>

        <textarea
            v-model="input"
            rows="6"
            class="w-full border p-2 mb-4"
            placeholder="Введите домены, через запятую или с новой строки..."
        ></textarea>

        <button
            @click="checkDomains"
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
        >
            Проверить
        </button>

        <div v-if="loading" class="mt-4 text-gray-500">Проверка...</div>

        <ul v-if="results.length" class="mt-4 space-y-2">
            <li
                v-for="(result, index) in results"
                :key="index"
                class="border p-2 rounded bg-gray-50"
            >
                <strong>{{ result.domain }}</strong>: {{ result.status }}
            </li>
        </ul>

        <div v-if="error" class="mt-4 text-red-500">
            Ошибка при проверке: {{ error }}
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const input = ref('')
const results = ref([])
const loading = ref(false)
const error = ref(null)

const checkDomains = async () => {
    const domains = input.value
        .split(/[\n,]+/)
        .map(d => d.trim())
        .filter(d => d)

    if (!domains.length) return

    loading.value = true
    error.value = null
    results.value = []

    try {
        const response = await axios.post('/api/domain-check', { domains }, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('auth_token')}`
            }
        })
        results.value = response.data.results
    } catch (err) {
        console.error('Ошибка при проверке доменов:', err)
        error.value = 'Не удалось проверить домены. Попробуйте снова.'
    } finally {
        loading.value = false
    }
}
</script>

<style scoped>
/* Обёртка */
div {
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    padding: 24px;
    margin-top: 40px;
}

/* Заголовок */
h1 {
    text-align: center;
    color: #2563eb; /* синий-500 */
}

/* Текстовое поле */
textarea {
    font-family: monospace;
    font-size: 14px;
    border: 1px solid #d1d5db; /* gray-300 */
    border-radius: 6px;
    padding: 10px;
    resize: vertical;
    outline: none;
    transition: border 0.3s, box-shadow 0.3s;
    display: block;
    width: 80%;
    margin: 0 auto;
}
textarea:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.3);
}

/* Кнопка */
button {
    font-weight: 600;
    transition: background-color 0.3s;
    display: block;
    width: 200px;
    margin: 0 auto;
    font-size: 16px;
    border: none;
    cursor: pointer;
    margin-top: 30px;
    padding: 20px 30px;
}
button:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.4);
}

/* Результаты */
ul {
    list-style: none;
    padding: 0;
}
li {
    background-color: #f9fafb;
    border: 1px solid #e5e7eb;
    padding: 12px 16px;
    border-radius: 6px;
}
li strong {
    color: #111827;
}

/* Сообщения */
.text-gray-500 {
    text-align: center;
    font-style: italic;
}

.text-red-500 {
    text-align: center;
    font-weight: 600;
    margin-top: 12px;
}
</style>

