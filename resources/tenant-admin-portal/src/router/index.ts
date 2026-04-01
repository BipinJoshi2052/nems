import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory('/dashboard'),
  scrollBehavior(to, from, savedPosition) {
    return savedPosition || { left: 0, top: 0 }
  },
  routes: [
    {
      path: '/',
      name: 'Dashboard',
      component: () => import('../views/Dashboard.vue'),
      meta: {
        title: 'Dashboard',
      },
    },
    {
      path: '/calendar',
      name: 'Calendar',
      component: () => import('../views/Others/Calendar.vue'),
      meta: {
        title: 'Calendar',
      },
    },
    {
      path: '/profile',
      name: 'Profile',
      component: () => import('../views/Others/UserProfile.vue'),
      meta: {
        title: 'Profile',
      },
    },
    {
      path: '/profile/:type/:id',
      name: 'user-profile',
      component: () => import('../views/Others/UserProfile.vue'),
      meta: {
        title: 'User Profile',
        requiresAuth: true,
      },
    },
    {
      path: '/form-elements',
      name: 'Form Elements',
      component: () => import('../views/Forms/FormElements.vue'),
      meta: {
        title: 'Form Elements',
      },
    },
    {
      path: '/basic-tables',
      name: 'Basic Tables',
      component: () => import('../views/Tables/BasicTables.vue'),
      meta: {
        title: 'Basic Tables',
      },
    },
    {
      path: '/line-chart',
      name: 'Line Chart',
      component: () => import('../views/Chart/LineChart/LineChart.vue'),
    },
    {
      path: '/bar-chart',
      name: 'Bar Chart',
      component: () => import('../views/Chart/BarChart/BarChart.vue'),
    },
    {
      path: '/alerts',
      name: 'Alerts',
      component: () => import('../views/UiElements/Alerts.vue'),
      meta: {
        title: 'Alerts',
      },
    },
    {
      path: '/avatars',
      name: 'Avatars',
      component: () => import('../views/UiElements/Avatars.vue'),
      meta: {
        title: 'Avatars',
      },
    },
    {
      path: '/badge',
      name: 'Badge',
      component: () => import('../views/UiElements/Badges.vue'),
      meta: {
        title: 'Badge',
      },
    },

    {
      path: '/buttons',
      name: 'Buttons',
      component: () => import('../views/UiElements/Buttons.vue'),
      meta: {
        title: 'Buttons',
      },
    },

    {
      path: '/images',
      name: 'Images',
      component: () => import('../views/UiElements/Images.vue'),
      meta: {
        title: 'Images',
      },
    },
    {
      path: '/videos',
      name: 'Videos',
      component: () => import('../views/UiElements/Videos.vue'),
      meta: {
        title: 'Videos',
      },
    },
    {
      path: '/blank',
      name: 'Blank',
      component: () => import('../views/Pages/BlankPage.vue'),
      meta: {
        title: 'Blank',
      },
    },

    {
      path: '/error-404',
      name: '404 Error',
      component: () => import('../views/Errors/FourZeroFour.vue'),
      meta: {
        title: '404 Error',
      },
    },

    {
      path: '/signin',
      name: 'Signin',
      component: () => import('../views/Auth/Signin.vue'),
      meta: {
        title: 'Signin',
        requiresAuth: false,
      },
    },
    {
      path: '/signup',
      name: 'Signup',
      component: () => import('../views/Auth/Signup.vue'),
      meta: {
        title: 'Signup',
        requiresAuth: false,
      },
    },
    {
      path: '/setup',
      name: 'Onboarding',
      component: () => import('../views/Pages/Onboarding/OnboardingWizard.vue'),
      meta: {
        title: 'School Setup',
        requiresAuth: true,
        roles: ['admin'],
      },
    },
    {
      path: '/staff',
      name: 'Staff',
      component: () => import('../views/Pages/Staff/StaffListView.vue'),
      meta: {
        title: 'Staff Management',
        requiresAuth: true,
      },
    },
    {
      path: '/students',
      name: 'Students',
      component: () => import('../views/Pages/Students/StudentListView.vue'),
      meta: {
        title: 'Student Directory',
        requiresAuth: true,
      },
    },
    {
      path: '/parents',
      name: 'parents',
      component: () => import('@/views/Pages/Users/ParentListView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/settings/school',
      name: 'SchoolSettings',
      component: () => import('../views/Pages/Settings/SchoolSettingsView.vue'),
      meta: {
        title: 'School Settings',
        requiresAuth: true,
      },
    },
  ],
})

export default router

import { useAuthStore } from '../stores/auth'

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()

  if (to.meta.requiresAuth !== false) {
    if (!authStore.isAuthenticated) {
      await authStore.refresh()
      if (!authStore.isAuthenticated) {
        window.location.href = '/login'
        return
      }
    }

    // Role-based protection
    const userRole = authStore.user?.role
    const requiredRoles = to.meta.roles as string[] | undefined

    if (requiredRoles && userRole && !requiredRoles.includes(userRole)) {
      // Redirect to dashboard (their home) if they don't have permission
      next({ name: 'Dashboard' })
      return
    }

    // Onboarding redirect
    if (userRole === 'admin' && authStore.user?.is_setup_complete === false && to.name !== 'Onboarding') {
      next({ name: 'Onboarding' })
      return
    }
  }

  document.title = `${to.meta.title} | NEMS`
  next()
})
