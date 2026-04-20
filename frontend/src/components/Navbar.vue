<template>
  <nav class="navbar">
    <RouterLink to="/comics" class="brand">COMIX</RouterLink>

    <div class="nav-links">
      <RouterLink to="/comics" class="nav-link">Library</RouterLink>
      <RouterLink v-if="authStore.isAdmin" to="/admin" class="nav-link">Users</RouterLink>
      <RouterLink v-if="authStore.isAdmin" to="/comics/new" class="nav-link nav-link--accent">+ Add comic</RouterLink>
    </div>

    <div class="nav-user">
      <span class="nav-username">{{ authStore.user?.name }}</span>
      <span :class="`badge badge-${authStore.role}`">{{ authStore.role?.replace('_', ' ') }}</span>
      <button class="btn btn-ghost btn-sm" @click="handleLogout">Logout</button>
    </div>
  </nav>
</template>

<script setup>
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const router = useRouter()

async function handleLogout() {
  await authStore.logout()
  router.push('/login')
}
</script>

<style scoped>
.navbar {
  position: fixed;
  top: 0; left: 0; right: 0;
  z-index: 100;
  height: 64px;
  background: var(--panel);
  display: flex;
  align-items: center;
  padding: 0 32px;
  gap: 32px;
}

.brand {
  font-family: var(--font-display);
  font-size: 28px;
  letter-spacing: .12em;
  color: var(--accent);
  text-decoration: none;
  flex-shrink: 0;
}

.nav-links {
  display: flex;
  align-items: center;
  gap: 4px;
  flex: 1;
}

.nav-link {
  color: #b0aa9f;
  text-decoration: none;
  padding: 6px 14px;
  border-radius: var(--radius);
  font-size: 14px;
  font-weight: 500;
  transition: color .15s, background .15s;
}
.nav-link:hover, .nav-link.router-link-active { color: #fff; background: rgba(255,255,255,.08); }
.nav-link--accent { color: var(--accent2) !important; }
.nav-link--accent:hover { background: rgba(245,166,35,.12) !important; }

.nav-user {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-left: auto;
}

.nav-username { color: #e0dbd3; font-size: 14px; }

.btn-ghost {
  color: #e0dbd3;  /* light color to show on dark navbar */
  border-color: rgba(255,255,255,.2);
}
</style>