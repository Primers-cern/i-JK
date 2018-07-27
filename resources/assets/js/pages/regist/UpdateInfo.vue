<template>
  <div>
    <h3 class="title">商户认证</h3>

    <p class="reminder">
      说明：<br/>
      提交信息后将有专人进行审核，认证通过后可解除功能使用限制，信息一经认证后无法修改，请审慎填写！
    </p>

    <div class="inputItem">
      <label>联系人姓名</label>
      <input type="text" id="shopowner" placeholder="请输入真实姓名" v-model="infomations.shopowner">
    </div>
    <div class="inputItem">
      <label>商户类型</label>
      <select id="merchant_type" v-model="infomations.merchant_type">
        <option value="0" disabled selected>请选择</option>
        <option
          v-for= "item of shopTypes"
          :key= "item.id"
          :value= "item.id"
        >{{item.text}}</option>
      </select>
    </div>
    <!-- 地址选择插件 -->
    <div class="inputItem">
      <label>商户所在地区</label>
      <v-distpicker
        :province="infomations.province"
        :city="infomations.city"
        :area="infomations.area"
        @province="onChangeProvince"
        @city="onChangeCity"
        @area="onChangeArea"
      ></v-distpicker>
    </div>
    <div class="inputItem">
      <label>商户详细地址</label>
      <input type="text" id="addr" placeholder="店铺具体的地址信息" v-model="infomations.addr">
    </div>
    <div class="inputItem">
      <label>商户营业执照</label>
      <input type="file" ref="license" accept="image/*">
    </div>

    <!-- 错误提示 -->
    <div class="errmsg" v-if="errmsg">{{errmsg}}</div>

    <button class="submitBtn" @click="handleSubmit">提交审核</button>
    <a href="###" class="skip">暂不认证，跳过>></a>
  </div>
</template>

<script>
import axios from 'axios'
import VDistpicker from 'v-distpicker'

export default {
  name: "UpdateInfo",
  components: {
    VDistpicker
  },
  data () {
    return {
      infomations: {
        shopowner: '',
        merchant_type: 0,
        province: '',
        city: '',
        area: '',
        addr: ''
      },
      shopTypes: [],
      errmsg: ''
    }
  },
  methods: {
    handleSubmit () {
      if (this.checkForm()) {
        const config = { //设置配置，添加表头
          headers: {'Content-Type': 'multipart/form-data'}
        }
        axios.post('/MerchantUser/updateMerchant', this.packData(), config)
        .then((response) => {
          console.log(response)
        })
      }
    },
    onChangeProvince (data) {
      this.infomations.province = data.value
    },
    onChangeCity (data) {
      this.infomations.city = data.value
    },
    onChangeArea (data) {
      this.infomations.area = data.value
    },
    checkForm () {
      if (this.$refs.license.value) { //判断是否上传图片
        let empty = () => { //判断是否为空
          for (let i in this.infomations) {
            if (!this.infomations[i]) {
              return true;
              break;
        }}}
        if (!empty()) {
          return true
        } else {
          this.errmsg = '请完整并正确填写信息'
          return false
        }
      } else {
        this.errmsg = '请正确上传图片'
        return false
      }
    },
    packData () {
      // 添加配置项
      let info = this.infomations
      info.secret = '73715fefabc2195fa0e14239'
      info.tbname = 'merchant_base'
      info.where = 'merchant_id=2'

      // 新建表单，插入数据
      let updateForm = new FormData()
      for (let i in info) {
        updateForm.append(i, info[i])
      }
       // 插入图片
      let license = this.$refs.license.files[0]
      updateForm.append('license', license)

      return updateForm
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
  },
  mounted () {
    // 获取商户类型
    this.shopTypes = [
      {id: 5, text: "餐饮/美食"},
      {id: 4, text: "健身/休闲"},
      {id: 3, text: "畅玩/娱乐"},
      {id: 2, text: "精品/购物"},
      {id: 1, text: "其他"}]
  }
}
</script>

<style lang="stylus" scoped>
  @import '../../../styles/mixins'
  
  .title
    text-align: center
    color: #333
    margin-bottom: 30px
  .reminder
    font-size: 14px
    color: #999
  .inputItem
    inputItem()
    text-align: left
    label
      itemLabel()
      width: 100px
    input
      itemInput()
  .submitBtn
    blueBigBtn()
    margin-top: 100px
  .skip
    display: block
    text-align: center
    text-decoration: none
    color: gray
    font-size: 12px
  .errmsg
    errmsg()
  
</style>