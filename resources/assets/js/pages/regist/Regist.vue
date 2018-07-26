<template>
  <div>
    <form>
      <div>
        <label for="merchant_name">商户名称</label>
        <input type="text" id="merchant_name" placeholder="请输入店铺名称" v-model="userData.merchant_name">
      </div>
      <div>
        <label for="phone">注册手机</label>
        <input type="number" id="phone" placeholder="该手机将成为后台登陆账号" v-model="userData.phone" ref="phone">
      </div>
      <div>
        <label for="code">验证码</label>
        <input type="number" id="code" v-model="code" ref="code">
        <button class="btn" @click="handleGetVerification" ref="codeBtn">{{getVeri}}</button>
      </div>
      <div>
        <label for="password">设置密码</label>
        <input type="password" id="password" placeholder="6位以上数字或字母" v-model="userData.password">
      </div>
      <div>
        <label for="confirmPassword">确认密码</label>
        <input type="password" id="confirmPassword" placeholder="请重复输入以确认" v-model="confirmPassword">
      </div>

      <div>
        <input type="checkbox" v-model="ifAgree">
        同意《
        <span class="showAgr" @click="handleShowAgr">即可传媒商户注册协议</span>
        》
      </div>

      <button class="btn nextBtn btnDisable" ref="nextBtn" @click="handleSubmit" disabled="true">下一步</button>
    </form>

    <!-- 错误提示 -->
    <div class="errmsg" v-if="errmsg">{{errmsg}}</div>

    <!-- 协议内容 -->
    <div class="agrBox" v-if="showAgr">
      <div class="agrText" v-html="agreement"></div>
      <button class="btn agrBtn" @click="handleShowAgr">我知道了</button>
    </div>

  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: "Regist",
  data () {
    return {
      getVeri: '获取验证码',
      userData: {
        merchant_name: '',
        phone: '',
        password: ''
      },
      code: '',
      getCode: '',
      OKCode: false,
      confirmPassword: '',
      ifAgree: false,
      showAgr: false,
      agreement: "这里是协议的内容HTML",
      errmsg: ''
    }
  },
  methods: {
    handleGetVerification (e) { //获取验证码
      
      let reg = /^1\d{10}$/ //手机验证
      if (this.userData.phone && reg.test(this.userData.phone)) {
        var $phone = {}
        $phone.phone = this.userData.phone
        axios.post('/sendCode', $phone) //发送数据
          .then((res) => {
            res = res.data
            if (res.reg_code) {
              this.getCode = res.reg_code //储存验证码
              this.changeCodeBtn() //改变按钮状态
            } else {
              this.errmsg = res.errmsg
            }
          })
      } else {this.errmsg = '请正确填写手机'}
    },
    changeCodeBtn () {// 改变样式
      const btn = this.$refs.codeBtn
      btn.classList.add("btnDisable")
      btn.disabled = true
      // 倒计时
      let timeRun = 5
      let interval = setInterval(()=>{
        this.getVeri = timeRun + 's'
        timeRun -= 1
        if (timeRun < 0) {
          clearInterval(interval)
          btn.classList.remove("btnDisable")
          btn.disabled = false
          this.getVeri = "获取验证码"
        }
      }, 1000)
    },
    handleShowAgr () { //显示协议
      this.showAgr = !this.showAgr
    },
    handleSubmit () { //提交表单
      if (this.checkForm()) {
        axios.post('/MerchantUser/addMerchant', this.packData())
          .then((res) => {
            if (res.data.errmsg == 'success') {
              this.$router.push("/updateinfo")
            }
          })
      }
    },
    packData () {
      var formData = {}
      formData.secret = '73715fefabc2195fa0e14239'
      formData.tbname = 'merchant_base'

      const data = this.userData //注入用户填写的信息
      for (let i in data) {
        formData[i] = data[i]
      }

      return formData
    },
    checkForm () { // 验证表单
      var empty = () => { //判断是否为空
        for (let i in this.userData) {
          if (!this.userData[i]) {
            return true;
            break;
      }}}
      //验证密码格式
      var reg = /^[a-zA-Z0-9]{6,15}$/
      if (reg.test(this.userData.password)) {
        //资料不为空，确认密码，正确验证码
        if (!empty() && this.confirmPassword && this.OKCode) { 
          if (this.confirmPassword == this.userData.password) {
            return true
          } else {
            this.errmsg = '请确保输入的密码一致'
            return false
          }
        } else {
          this.errmsg = '请正确填写资料'
          return false
        }
      } else {
        this.errmsg = '密码格式不正确'
        return false
      }
    }
  },
  watch: {
    ifAgree () {
      const btn = this.$refs.nextBtn
      // 切换 class 状态
      btn.classList.toggle("btnDisable")
      // 切换 disabled 状态
      btn.disabled = !this.ifAgree
    },
    errmsg () { //自动隐藏错误信息
      if (this.errmsg) {
        setTimeout(() => {
          this.errmsg = ''
        },2000)
      }
    },
    code () { //检测验证码正确
      if (this.getCode) {
        clearTimeout(timeout)
        var timeout = setTimeout(() => {
          if (this.code == this.getCode) {
            this.$refs.code.style.borderColor = 'green'
            this.$refs.phone.disabled = true //阻止修改手机
            this.OKCode = true
          } else {
            this.$refs.code.style.borderColor = 'red'
            this.OKCode = false
          }
        },500)
      }
    }
  },
  mounted () {
    // axios.get('url')
    //   .then((res) => {
    //     if (res.data.errmsg == 'success') {
    //       this.agreement = res.data.text
    //     }
    //   })
  }
}
</script>

<style lang="stylus" scoped>
  @import '../../../styles/mixins'

  .title
    font-size: 20px
    color: pink
  .btn
    baseBtn()
  .nextBtn,.agrBtn
    blueBigBtn()
  .btnDisable
    background-color: gray
  .showAgr
    color: #0084f5
    text-decoration: underline
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
