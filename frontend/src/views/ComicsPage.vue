<template>
  <div class="page">
    <div class="page-header">
      <h1 class="page-title">Comic Library</h1>
      <div class="header-actions">
        <input v-model="search" placeholder="Search title or series…" class="search-input" />
        <RouterLink v-if="authStore.isAdmin" to="/comics/new" class="btn btn-primary">+ Add comic</RouterLink>
      </div>
    </div>

    <div v-if="loading" class="state-msg">Loading…</div>
    <div v-else-if="error"  class="state-msg error-msg">{{ error }}</div>
    <div v-else-if="filtered.length === 0" class="state-msg">No comics found.</div>

    <div v-else class="card">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Series</th>
            <th>Issue</th>
            <th>Title</th>
            <th v-if="authStore.isAdmin">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="comic in filtered" :key="comic.id">
            <td class="td-id">{{ comic.id }}</td>
            <td class="td-serie">{{ comic.serie }}</td>
            <td class="td-number">{{ comic.number }}</td>
            <td>{{ comic.title }}</td>
            <td v-if="authStore.isAdmin" class="td-actions">
              <RouterLink :to="`/comics/${comic.id}/edit`" class="btn btn-ghost btn-sm">Edit</RouterLink>
              <button class="btn btn-danger btn-sm" @click="deleteComic(comic)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '../stores/auth'
import api from '../services/api'

const authStore = useAuthStore()
const comics  = ref([])
const search  = ref('')
const loading = ref(true)
const error   = ref('')

const filtered = computed(() => {
  const q = search.value.toLowerCase()
  if (!q) return comics.value
  return comics.value.filter(c =>
    c.title.toLowerCase().includes(q) ||
    c.serie.toLowerCase().includes(q)
  )
})

async function fetchComics() {
  loading.value = true
  try {
    const data = await api.get('/comics')
    comics.value = data.comics
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}

async function deleteComic(comic) {
  if (!confirm(`Delete "${comic.title}"?`)) return
  try {
    await api.delete(`/comics/${comic.id}`)
    comics.value = comics.value.filter(c => c.id !== comic.id)
  } catch (e) {
    alert(e.message)
  }
}

onMounted(fetchComics)
</script>

<style scoped>
.page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 32px;
  gap: 16px;
  flex-wrap: wrap;
}
.page-title { margin-bottom: 0; }

.header-actions { display: flex; gap: 12px; align-items: center; }

.search-input {
  width: 280px;
  padding: 9px 14px;
  border: 1.5px solid var(--border);
  border-radius: var(--radius);
  background: #fff;
  font-size: 14px;
}

.td-id     { color: var(--muted); font-size: 13px; width: 48px; }
.td-serie  { font-weight: 500; }
.td-number { color: var(--muted); width: 72px; }
.td-actions { display: flex; gap: 8px; }

.state-msg { padding: 48px; text-align: center; color: var(--muted); }
</style>