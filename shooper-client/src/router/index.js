import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/store/auth'

const router = createRouter({
  scrollBehavior(to, from, savedPosition) {
    return { top: 0 }
  },
  history: createWebHistory(),
  routes: [
    {
      path: '/:pathMatch(.*)*',
      name: 'Not Found',
      component: () => import('@/views/Errors/404.vue')
    },
    {
      path: '/',
      name: 'CustomerView',
      component: () => import('@/layouts/Views/CustomerLayout.vue'),
      children: [
        {
          path: '/user',
          name: 'User',
          children: [
            {
              path: 'profile/:id/:name',
              name: 'UserProfile',
              component: () => import('@/layouts/Views/ProfileLayout.vue'),
              children: [
                {
                  path: '',
                  name: 'Personal Data',
                  component: () => import('@/views/Profile/PersonalDataView.vue')
                },
                {
                  path: 'lists',
                  name: 'Lists',
                  component: () => import('@/views/Profile/ListsView.vue')
                },
                {
                  path: 'watchlist',
                  name: 'Watchlist',
                  component: () => import('@/views/Profile/WatchlistView.vue')
                }
              ]
            }
          ],
          meta: {
            requiresAuth: true
          }
        },
        {
          path: '',
          name: 'Home',
          component: () => import('@/views/Customer/Home/HomeView.vue')
        },
        {
          path: 'product/:id/:name',
          name: 'Product',
          component: () => import('@/views/Customer/Product/ProductView.vue')
        },
        {
          path: '/listing',
          name: 'Listing Page',
          children: [
            {
              path: ':id/:category',
              component: () => import('@/views/Customer/ProductListing/ProductListingView.vue')
            },
            {
              path: ':search',
              name: 'ListingSearch',
              component: () => import('@/views/Customer/ProductListing/ProductListingView.vue')
            }
          ]
        }
      ]
    },
    {
      path: '/auth',
      name: 'Authentication',
      children: [
        {
          path: 'signin',
          name: 'SignIn',
          component: () => import('@/views/Auth/SignIn.vue')
        },
        {
          path: 'signup',
          name: 'SignUp',
          component: () => import('@/views/Auth/SignUp.vue')
        }
      ],
      meta: {
        requiresSignout: true
      }
    },
    {
      path: '/admin',
      name: 'AdministratorView',
      component: () => import('@/layouts/Views/AdminLayout.vue'),
      children: [
        {
          path: '',
          name: 'AdminDashboard',
          component: () => import('@/views/Admin/Dashboard/DashboardView.vue')
        },
        {
          path: 'user',
          name: 'UserCRUD',
          component: () => import('@/views/Admin/User/UserView.vue')
        },
        {
          path: 'product',
          name: 'ProductCRUD',
          component: () => import('@/views/Admin/Product/ProductView.vue')
        },
        {
          path: 'product/url',
          name: 'ProductUrlCRUD',
          component: () => import('@/views/Admin/ProductUrl/ProductUrlView.vue')
        },
        {
          path: 'category',
          name: 'CategoryCRUD',
          component: () => import('@/views/Admin/Category/CategoryView.vue')
        },
        {
          path: 'store',
          name: 'StoreCRUD',
          component: () => import('@/views/Admin/Store/StoreView.vue')
        },
        {
          path: 'brand',
          name: 'BrandCRUD',
          component: () => import('@/views/Admin/Brand/BrandView.vue')
        }
      ],
      meta: {
        requiresAuth: true,
        requiresAdmin: true,
        transition: 'slide-left'
      }
    }
  ]
})

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()
  try {
    await authStore.getUser()
  } catch (error) {}
  if (to.meta.requiresAuth && !authStore.status) {
    next('/auth/signin')
  } else if (to.meta.requiresSignout && authStore.status) {
    next('/')
  } else if (to.meta.requiresAdmin && authStore?.user?.role?.id != 1) {
    next('/:pathMatch(.*)*')
  } else {
    next()
  }
})

export default router
