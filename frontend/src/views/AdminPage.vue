<template>
  <div class="page">
    <div class="page-header">
      <h1 class="page-title">User Management</h1>
      <button class="btn btn-primary" @click="showForm = true">+ Add user</button>
    </div>

    <!-- Add user form -->
    <div v-if="showForm" class="card form-card">
      <h2 class="form-title">New user</h2>
      <form @submit.prevent="handleCreate">
        <div class="form-row">
          <div class="form-group">
            <label>Name</label>
            <input v-model="newUser.name" placeholder="Full name" required />
          </div>
          <div class="form-group">
            <label>Email</label>
            <input v-model="newUser.email" type="email" placeholder="email@example.com" required />
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Password</label>
            <input v-model="newUser.password" type="password" placeholder="••••••••" required />
          </div>
          <div class="form-group">
            <label>Role</label>
            <select v-model="newUser.role">
              <option v-for="r in allowedRoles" :key="r" :value="r">{{ r.replace('_', ' ') }}</option>
            </select>
          </div>
        </div>
        <p v-if="formError" class="error-msg">{{ formError }}</p>
        <div class="form-actions">
          <button type="button" class="btn btn-ghost" @click="cancelForm">Cancel</button>
          <button type="submit" class="btn btn-primary" :disabled="formLoading">
            {{ formLoading ? 'Creating…' : 'Create user' }}
          </button>
        </div>
      </form>
    </div>

    <!-- Users table -->
    <div v-if="loading" class="state-msg">Loading…</div>
    <div v-else-if="error" class="state-msg error-msg">{{ error }}</div>
    <div v-else class="card">
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="u in users" :key="u.id">
            <td>{{ u.name }}</td>
            <td class="td-email">{{ u.email }}</td>
            <td><span :class="`badge badge-${u.role}`">{{ u.role.replace('_', ' ') }}</span></td>
            <td>
              <button
                v-if="canDelete(u)"
                class="btn btn-danger btn-sm"
                @click="deleteUser(u)"
              >Delete</button>
              <span v-else class="no-action">—</span>
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

const users       = ref([])
const loading     = ref(true)
const error       = ref('')
const showForm    = ref(false)
const formLoading = ref(false)
const formError   = ref('')

const newUser = ref({ name: '', email: '', password: '', role: 'friend' })

// super_admin can only create admins; admin can create friends and admins
const allowedRoles = computed(() =>
  authStore.isSuperAdmin ? ['admin'] : ['friend', 'admin']
)

function canDelete(u) {
  if (u.id === authStore.user?.id) return false
  if (u.role === 'super_admin') return false
  if (authStore.isSuperAdmin) return true
  if (authStore.isAdmin && u.role !== 'super_admin') return true
  return false
}

async function fetchUsers() {
  loading.value = true
  try {
    const data = await api.get('/users')
    users.value = data.users
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}

function cancelForm() {
  showForm.value = false
  newUser.value  = { name: '', email: '', password: '', role: 'friend' }
  formError.value = ''
}

async function handleCreate() {
  formError.value   = ''
  formLoading.value = true
  try {
    await api.post('/users/create', newUser.value)
    cancelForm()
    fetchUsers()
  } catch (e) {
    formError.value = e.message
  } finally {
    formLoading.value = false
  }
}

async function deleteUser(u) {
  if (!confirm(`Delete user "${u.name}"?`)) return
  try {
    await api.delete(`/users/delete.php?id=${u.id}`)
    users.value = users.value.filter(x => x.id !== u.id)
  } catch (e) {
    alert(e.message)
  }
}

onMounted(fetchUsers)
</script>

<style scoped>
.page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 32px;
}
.page-title { margin-bottom: 0; }

.form-card { margin-bottom: 24px; max-width: 100%; }
.form-title { font-family: var(--font-display); font-size: 24px; margin-bottom: 20px; }

.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

.form-actions {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
  margin-top: 8px;
}

.td-email { color: var(--muted); font-size: 13px; }
.no-action { color: var(--border); }
.state-msg { padding: 48px; text-align: center; color: var(--muted); }
</style>