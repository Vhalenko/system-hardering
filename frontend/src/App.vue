<template>
  <div id="app">
    <NavBar v-if="authStore.isLoggedIn" />
    <main :class="{ 'with-nav': authStore.isLoggedIn }">
      <RouterView />
    </main>
  </div>
</template>

<script setup>
import { useAuthStore } from './stores/auth'
import NavBar from './components/Navbar.vue'

const authStore = useAuthStore()
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500&display=swap');

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --ink:     #0f0e0d;
  --paper:   #f5f2ee;
  --panel:   #1a1814;
  --accent:  #e8382a;
  --accent2: #f5a623;
  --muted:   #7a746c;
  --border:  #d9d4cc;
  --radius:  6px;
  --font-display: 'Bebas Neue', sans-serif;
  --font-body:    'DM Sans', sans-serif;
}

body {
  background: var(--paper);
  color: var(--ink);
  font-family: var(--font-body);
  font-size: 15px;
  line-height: 1.6;
  min-height: 100vh;
}

main { min-height: 100vh; }
main.with-nav { padding-top: 64px; }

input, select, textarea {
  font-family: var(--font-body);
  font-size: 15px;
  width: 100%;
  padding: 10px 14px;
  border: 1.5px solid var(--border);
  border-radius: var(--radius);
  background: #fff;
  color: var(--ink);
  outline: none;
  transition: border-color .2s;
}
input:focus, select:focus, textarea:focus { border-color: var(--accent); }

.btn {
  font-family: var(--font-display);
  font-size: 18px;
  letter-spacing: .04em;
  padding: 10px 28px;
  border: none;
  border-radius: var(--radius);
  cursor: pointer;
  transition: opacity .15s, transform .1s;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}
.btn:hover { opacity: .88; }
.btn:active { transform: scale(.97); }
.btn-primary { background: var(--accent); color: #fff; }
.btn-secondary { background: var(--panel); color: #fff; }
.btn-ghost { background: transparent; color: var(--ink); border: 1.5px solid var(--border); font-family: var(--font-body); font-size: 14px; padding: 8px 16px; }
.btn-danger { background: #c0392b; color: #fff; }
.btn-sm { font-size: 14px; padding: 6px 16px; font-family: var(--font-body); }

.card {
  background: #fff;
  border: 1.5px solid var(--border);
  border-radius: var(--radius);
  padding: 24px;
}

.badge {
  display: inline-block;
  font-size: 11px;
  font-weight: 500;
  padding: 2px 10px;
  border-radius: 99px;
  text-transform: uppercase;
  letter-spacing: .06em;
}
.badge-super_admin { background: #fde8e6; color: var(--accent); }
.badge-admin       { background: #fff3e0; color: #b06000; }
.badge-friend      { background: #e8f5e9; color: #2e7d32; }

.page { padding: 40px 32px; max-width: 1100px; margin: 0 auto; }
.page-title {
  font-family: var(--font-display);
  font-size: 52px;
  line-height: 1;
  margin-bottom: 32px;
  letter-spacing: .02em;
}

table { width: 100%; border-collapse: collapse; }
thead th { text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: .08em; color: var(--muted); padding: 10px 16px; border-bottom: 2px solid var(--border); }
tbody td { padding: 14px 16px; border-bottom: 1px solid var(--border); vertical-align: middle; }
tbody tr:last-child td { border-bottom: none; }
tbody tr:hover td { background: #faf8f5; }

.error-msg { color: var(--accent); font-size: 13px; margin-top: 6px; }
.form-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 18px; }
.form-group label { font-size: 12px; font-weight: 500; text-transform: uppercase; letter-spacing: .07em; color: var(--muted); }
</style>