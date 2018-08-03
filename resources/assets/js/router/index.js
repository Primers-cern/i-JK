import Vue from 'vue'
import router from 'vue-router'
import Login from '../pages/login/Login'
import Regist from '../pages/login/Regist'
import Certifi from '../pages/login/Certifi'
import EditGoods from '../pages/merchant/EditGoods'
import Test from '../pages/merchant/test'
import GoodsManage from '../pages/merchant/GoodsManage'
import add_goods_group from '../pages/merchant/add_goods_group'
import goods_group_list from '../pages/merchant/goods_group_list'


Vue.use(router)

export default new router({
  routes: [
    {
      path: '/login',
      name: 'Login',
      component: Login
    },
    {
      path: '/regist',
      name: 'Regist',
      component: Regist
    },
    {
      path: '/certifi',
      name: 'Certifi',
      component: Certifi
    },
    {
      path: '/merchant/:id',
      name: 'AddGoods',
      component: EditGoods
    },
    {
      path: '/merchant',
      name: 'UpdateGoods',
      component: EditGoods
    },
    {
      path: '/test',
      name: 'Test',
      component: Test
    },
    {
      path: '/goodsmanage',
      name: 'GoodsManage',
      component: GoodsManage
    },
    {
      path: '/add_goods_group',
      name: add_goods_group,
      component: add_goods_group
    },
    {
      path: '/goods_group_list',
      name: goods_group_list,
      component: goods_group_list
    }
  ]
})
