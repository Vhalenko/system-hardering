<template>
  <div class="page">
    <RouterLink to="/comics" class="back-link">← Back to library</RouterLink>
    <h1 class="page-title">{{ isEdit ? 'Edit comic' : 'Add comic' }}</h1>

    <div class="card form-card">
      <form @submit.prevent="handleSubmit">
        <div class="form-group">
          <label>Series</label>
          <input v-model="form.serie" placeholder="e.g. Batman" required />
        </div>
        <div class="form-group">
          <label>Issue number</label>
          <input v-model.number="form.number" type="number" min="1" placeholder="e.g. 1" required />
        </div>
        <div class="form-group">
          <label>Title</label>
          <input v-model="form.title" placeholder="e.g. The Dark Knight Returns" required />
        </div>

        <p v-if="error" class="error-msg">{{ error }}</p>

        <div class="form-actions">
          <RouterLink to="/comics" class="btn btn-ghost">Cancel</RouterLink>
          <button type="submit" class="btn btn-primary" :disabled="loading">
            {{ loading ? 'Saving…' : (isEdit ? 'Save changes' : 'Add comic') }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../services/api'

const route  = useRoute()
const router = useRouter()

const isEdit  = computed(() => !!route.params.id)
const loading = ref(false)
const error   = ref('')
const form    = ref({ serie: '', number: '', title: '' })

onMounted(async () => {
  if (!isEdit.value) return
  try {
    const data = await api.get(`/comics/${route.params.id}`)
    form.value = { serie: data.comic.serie, number: data.comic.number, title: data.comic.title }
  } catch (e) {
    error.value = e.message
  }
})

async function handleSubmit() {
  error.value   = ''
  loading.value = true
  try {
    if (isEdit.value) {
      await api.put(`/comics/${route.params.id}`, form.value)
    } else {
      await api.post('/comics', form.value)
    }
    router.push('/comics')
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.back-link {
  display: inline-block;
  color: var(--muted);
  text-decoration: none;
  font-size: 14px;
  margin-bottom: 16px;
  transition: color .15s;
}
.back-link:hover { color: var(--ink); }

.form-card { max-width: 540px; }

.form-actions {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
  margin-top: 8px;
}
</style>