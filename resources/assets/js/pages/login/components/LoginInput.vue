<template>
  <div>
    <h3 class="title">登陆</h3>
    <div class="inputBox">
      <div class="inputItem">
        <label for="account">手机号</label>
        <input type="text" id="account" v-model="phone">
      </div>
      <div class="inputItem">
        <label for="password">密 码</label>
        <input type="password" id="password" v-model="password">
      </div>
    </div>

    <div class="links">
      <span class="regist">
        <router-link to="/regist">注册新店铺</router-link>
      </span>
      <span class="forget">
        <router-link to="/regist">忘记密码</router-link>
      </span>
    </div>

    <button class="loginBtn" @click="handleLogin">登录</button>

    <div class="errmsg" v-if="errmsg">{{errmsg}}</div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: "LoginInput",
  data () {
    return {
      phone: '',
      password: '',
      errmsg: ''
    }
  },
  methods: {
    handleLogin () {//执行登陆
      if (this.checkForm()) {// 本地验证
        axios.post('/merLogin', this.packData())
          .then((res) => {
            const data = res.data
            if (data && data.result_code == 'success') {
              let accInfo = {}
              accInfo.account = data.phone
              accInfo.shopList = data.shopList
              this.$emit('logined', accInfo)
            } else {
              this.errmsg = '账号或密码错误'
            }
          })
      } 
    },
    checkForm () {
      const phone = this.phone
      const password = this.password

      if (phone && password) {
        let reg = /^1\d{10}$/ //手机验证
        if (reg.test(phone)) {
          return true //通过验证
        } else {
          this.errmsg = '请填写正确手机号'
          return false
        }
      } else {
        this.errmsg = '账号或密码不能为空'
        return false
      }
    },
    packData () {
      let loginMsg = {}
      loginMsg.secret = '73715fefabc2195fa0e14239'
      loginMsg.tbname = 'mer_user'
      loginMsg.phone = this.phone
      loginMsg.password = this.password

      return loginMsg
    }
  },
  watch: {
    errmsg () { //自动隐藏错误信息
      if (this.errmsg) {
        setTimeout(() => {
          this.errmsg = ''
        },2000)
      }
    }
  }
}
</script>

<style lang="stylus" scoped>
  @import '../../../../styles/mixins'
  
  .title
    text-align: center
  .inputBox
    margin-top: 40px
    .inputItem
      inputItem()
      label
        itemLabel()
      input
        itemInput()
  .links
    overflow: hidden
    padding: 0 34px
    font-size: 12px
    .regist
      float: left
    .forget
      float: right
  .loginBtn
    blueBigBtn()
    margin-top: 60px
  .errmsg
    errmsg()
</style>