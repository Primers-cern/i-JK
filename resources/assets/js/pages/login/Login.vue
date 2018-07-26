<template>
  <div>
    <div class="loginBox">
      <login-input v-if="!this.account" @logined="handleLoginState"></login-input>
      <login-acount v-else :account="account" @changeAcc="handleLoginState"></login-acount>
    </div>
    <div class="shopBox">
      <p class="title">—————— 已绑定店铺 ——————</p>
      <shop-list :shopList="shopList"></shop-list>
    </div>
  </div>
</template>

<script>
import LoginInput from './components/LoginInput'
import LoginAcount from './components/LoginAcount'
import ShopList from './components/ShopList'

export default {
  name: "Login",
  components: {
    LoginInput,
    LoginAcount,
    ShopList
  },
  data () {
    return {
      account: '',
      shopList: [],
    }
  },
  methods: {
    handleLoginState (value) { //登录状态改变
      this.account = value.account
      this.shopList = value.shopList
    }
  },
  mounted () {
    // 获取cookie内容，确认是否自动登陆
  }
}
</script>

<style lang="stylus" scoped>
  .shopBox
    .title
      font-size: 12px
      text-align: center
      color: #999
</style>