<template>
  <div>
    <form>
      <div>
        <label>联系人姓名</label>
        <input type="text" id="shopowner" placeholder="请输入真实姓名" v-model="infomations.shopowner">
      </div>
      <div>
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
      <div>
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
      <div>
        <label>商户详细地址</label>
        <input type="text" id="addr" placeholder="店铺具体的地址信息" v-model="infomations.addr">
      </div>
      <div>
        <label>商户LOGO图</label>
        <input type="file" ref="logo" accept="image/*">
      </div>
      <div>
        <label>商户介绍</label>
        <textarea id="introduce" cols="30" rows="5" v-model="infomations.introduce"></textarea>
      </div>
      <div>
        <label>商户营业执照</label>
        <input type="file" ref="license" accept="image/*">
        <p>选填，执照用以验证资质，未验证商户功能将受限制</p>
      </div>

      <button class="submitBtn" @click="handleSubmit">完成</button>
      <a href="###" class="skip">忽略跳过</a>
    </form>

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
        addr: '',
        introduce: ''
      },
      shopTypes: []
    }
  },
  methods: {
    handleSubmit () {
      // 添加表头
      let config = {
        headers: {'Content-Type': 'multipart/form-data'}
      }
      // 发送
      axios.post('/MerchantUser/updateMerchant', this.packData(), config)
      .then((response) => {
        console.log(response)
      })
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
    packData () {
      // 添加配置项
      const info = this.infomations
      info.secret = '73715fefabc2195fa0e14239'
      info.tbname = 'merchant_base'
      info.where = 'merchant_id=2'

      // 新建表单，插入数据
      let updateForm = new FormData()
      for (var i in info) {
        updateForm.append(i, info[i])
      }
       // 插入图片
      let license = this.$refs.license.files[0]
      let logo = this.$refs.logo.files[0]
      updateForm.append('license', license)
      updateForm.append('merchant_logo', logo)

      return updateForm
    }
  },
  watch: {
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
  
  .submitBtn
    blueBigBtn()
  .skip
    display: block
    text-align: center
    text-decoration: none
    color: #0084f5
    font-size: 12px
</style>