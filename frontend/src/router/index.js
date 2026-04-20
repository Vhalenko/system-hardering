import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

import LoginPage     from '../views/LoginPage.vue'
import ComicsPage    from '../views/ComicsPage.vue'
import ComicFormPage from '../views/ComicsFormPage.vue'
import AdminPage     from '../views/AdminPage.vue'
import NotFound      from '../views/NotFound.vue'

const routes = [
  { path: '/',        redirect: '/comics' },
  { path: '/login',   component: LoginPage,     meta: { guest: true } },
  { path: '/comics',  component: ComicsPage,    meta: { auth: true } },
  { path: '/comics/new',       component: ComicFormPage, meta: { auth: true, role: 'admin' } },
  { path: '/comics/:id/edit',  component: ComicFormPage, meta: { auth: true, role: 'admin' } },
  { path: '/admin',   component: AdminPage,     meta: { auth: true, role: 'admin' } },
  { path: '/:pathMatch(.*)*',  component: NotFound },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to) => {
  const auth = useAuthStore()

  if (to.meta.guest && auth.isLoggedIn) return '/comics'
  if (to.meta.auth  && !auth.isLoggedIn) return '/login'

  if (to.meta.role === 'admin' && !auth.isAdmin) return '/comics'

  return true
})

export default router