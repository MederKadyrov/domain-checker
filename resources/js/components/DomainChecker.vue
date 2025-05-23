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
        const response = await axios.post('/api/domain-check', { domains })
        results.value = response.data.results
    } catch (err) {
        console.error('Ошибка при проверке доменов:', err)
        error.value = 'Не удалось проверить домены. Попробуйте снова.'
    } finally {
        loading.value = false
    }
}
</script>
