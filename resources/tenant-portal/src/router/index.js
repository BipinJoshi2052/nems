import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/dashboard',
      component: () => import('../layouts/AdminLayout.vue'), // Default
      meta: { requiresAuth: true },
      children: [
        {
          path: '',
          name: 'dashboard.home',
          component: () => import('../views/DashboardHome.vue'),
        },
        // Role-based routes can go here
      ],
    },
    {
      path: '/:pathMatch(.*)*',
      redirect: '/dashboard',
    },
  ],
});

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore();

  // If page requires auth
  if (to.meta.requiresAuth) {
    if (!authStore.isAuthenticated) {
      // Try refresh first
      await authStore.refresh();
      if (!authStore.isAuthenticated) {
        window.location.href = '/login';
        return;
      }
    }
  }

  // Check roles if defined in meta
  if (to.meta.roles && !to.meta.roles.includes(authStore.role)) {
    next('/dashboard');
    return;
  }

  next();
});

export default router;
