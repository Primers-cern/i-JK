import Vue from 'vue'
import router from 'vue-router'
import Login from '../pages/login/Login.vue'
import Regist from '../pages/regist/Regist.vue'
import UpdateInfo from '../pages/regist/UpdateInfo.vue'



Vue.use(router)

export default new router({
  routes: [
    {
      path: '/',
      name: 'Login',
      component: Login
    },
    {
      path: '/Regist',
      name: 'Regist',
      component: Regist
    },
    {
      path: '/updateinfo',
      name: 'UpdateInfo',
      component: UpdateInfo
    }
  ]
})
