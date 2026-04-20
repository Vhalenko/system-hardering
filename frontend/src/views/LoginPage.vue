<template>
  <div class="login-layout">
    <div class="login-panel">
      <div class="login-brand">COMIX</div>
      <p class="login-sub">Your comic book library</p>

      <form @submit.prevent="handleLogin" class="login-form">
        <div class="form-group">
          <label>Email</label>
          <input v-model="email" type="email" placeholder="you@example.com" required autofocus />
        </div>
        <div class="form-group">
          <label>Password</label>
          <input v-model="password" type="password" placeholder="••••••••" required />
        </div>

        <p v-if="error" class="error-msg">{{ error }}</p>

        <button type="submit" class="btn btn-primary login-btn" :disabled="loading">
          {{ loading ? 'Signing in…' : 'Sign in' }}
        </button>
      </form>
    </div>

    <div class="login-cover">
      <div class="cover-text">
        <span>BAM!</span>
        <span>POW!</span>
        <span>ZAP!</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const router = useRouter()

const email    = ref('')
const password = ref('')
const error    = ref('')
const loading  = ref(false)

async function handleLogin() {
  error.value   = ''
  loading.value = true
  try {
    await authStore.login(email.value, password.value)
    router.push('/comics')
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.login-layout {
  display: grid;
  grid-template-columns: 420px 1fr;
  min-height: 100vh;
}

.login-panel {
  padding: 64px 48px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  background: #fff;
  border-right: 1.5px solid var(--border);
}

.login-brand {
  font-family: var(--font-display);
  font-size: 64px;
  letter-spacing: .1em;
  color: var(--accent);
  line-height: 1;
  margin-bottom: 8px;
}

.login-sub {
  color: var(--muted);
  font-size: 14px;
  margin-bottom: 48px;
}

.login-form { display: flex; flex-direction: column; }

.login-btn {
  margin-top: 8px;
  width: 100%;
  justify-content: center;
  font-size: 20px;
  padding: 14px;
}
.login-btn:disabled { opacity: .6; cursor: not-allowed; }

.login-cover {
  background: var(--panel);
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  position: relative;
}

.cover-text {
  display: flex;
  flex-direction: column;
  gap: 0;
}

.cover-text span {
  font-family: var(--font-display);
  font-size: clamp(80px, 12vw, 160px);
  letter-spacing: .05em;
  line-height: .9;
  color: transparent;
  -webkit-text-stroke: 2px rgba(255,255,255,.12);
  user-select: none;
}
.cover-text span:nth-child(2) { -webkit-text-stroke-color: rgba(232,56,42,.25); padding-left: 40px; }
.cover-text span:nth-child(3) { -webkit-text-stroke-color: rgba(245,166,35,.2); }
</style>