<template>
  <div class="notfound">
    <div class="notfound-code">404</div>
    <p class="notfound-msg">This page doesn't exist.</p>
    <RouterLink to="/comics" class="btn btn-secondary">Back to library</RouterLink>
  </div>
</template>

<style scoped>
.notfound {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  gap: 16px;
  text-align: center;
}
.notfound-code {
  font-family: var(--font-display);
  font-size: 120px;
  line-height: 1;
  color: var(--border);
  letter-spacing: .05em;
}
.notfound-msg { color: var(--muted); font-size: 16px; }
</style>