<template>
  <div>
    <div>
      <label for="account">手机号</label>
      <input type="text" id="account" v-model="phone">
    </div>
    <div>
      <label for="password">密码</label>
      <input type="password" id="password" v-model="password">
    </div>

    <span>
      <router-link to="/regist">注册新店铺</router-link>
    </span>
    <span>
      <router-link to="/regist">忘记密码</router-link>
    </span>

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
    handleLogin () {
      if (this.checkForm()) {// 本地验证
        axios.post('/merLogin', this.packData())
          .then((res) => {
            let data = res.data
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
      let phone = this.phone
      let password = this.password

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
  
  .loginBtn
    blueBigBtn()
  .errmsg
    position: fixed
    top: 0
    left: 0
    right: 0
    text-align: center
    color: #fff
    padding: 4px 0
    background-color: rgba(0,0,0,.7)
</style>