import VueRouter from 'vue-router'
// Pages
import Home from './pages/Home'
import Register from './pages/Register'
import Login from './pages/Login'
import AdminDashboard from './pages/admin/Dashboard'
import Profile from "./pages/user/Profile";
import Newsfeed from "./pages/user/Newsfeed";

// Routes
const routes = [
  {
    path: '/',
    name: 'home',
    component: Home,
    meta: {
      auth: undefined,
      redirect: {
        name: 'login'
      }
    }
  },
  {
    path: '/register',
    name: 'register',
    component: Register,
    meta: {
      auth: false
    }
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: {
      auth: false
    }
  },
  // USER ROUTES
  {
    path: '/profile',
    name: 'profile',
    component: Profile,
    meta: {
      auth: true
    }
  },
  {
    path: '/newsfeed',
    name: 'newsfeed',
    component: Newsfeed,
    meta: {
       auth: true
     }
    },
  // ADMIN ROUTES
  {
    path: '/admin',
    name: 'admin.dashboard',
    component: AdminDashboard,
    meta: {
      auth: {
        roles: -1,
        redirect: {
          name: 'login'
        },
        forbiddenRedirect: '/403'
      }
    }
  },
]
const router = new VueRouter({
  history: true,
  mode: 'history',
  routes,
})
export default router
