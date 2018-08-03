<template>
  <div>
    <h3 class="title">编辑商品信息</h3>
    <div class="inputItem">
      <label>商品名称</label>
      <input type="text" id="name" placeholder="请输入商品名称" v-model="infomations.name">
    </div>
    <div class="inputItem">
      <label>商品分类</label>
      <select id="type" v-model="infomations.type">
        <option value="0" disabled selected>请选择</option>
        <option
          v-for= "item of goodTypes"
          :key= "item.id"
          :value= "item.id"
        >{{item.type}}</option>
      </select>
    </div>
    <div class="inputItem">
      <label>商品图片</label>
      <input type="file" ref="img" accept="image/*" @change="addImg">
    </div>
    <div class="imgBox">
      <img :src="infomations.src" ref="showImg">
    </div>
    <div class="inputItem">
      <label>商品介绍</label>
      <textarea id="des" cols="30" rows="10" placeholder="请输入商品的详情介绍" v-model="infomations.des"></textarea>
    </div>
    <div class="origin">
      <div class="inputItem">
        <label>库存</label>
        <input type="text" id="inventory" placeholder="99" v-model="infomations.inventory">
        <span class="unit">件</span>
      </div>
      <div class="inputItem">
        <label>价格</label>
        <input type="text" id="price" placeholder="保留几位小数？" v-model="infomations.price">
        <span class="unit">元</span>
      </div>
      <div class="showList">
        <div class="showItem">
          <span class="text">服务费率</span>
          <span class="num">{{C_priceList[0]}}%</span>
        </div>
        <div class="showItem">
          <span class="text">商户实收</span>
          <span class="num">{{C_priceList[1]}}元</span>
        </div>
        <div class="showItem">
          <span class="text">用户实际支付</span>
          <span class="num">{{C_priceList[2]}}元</span>
        </div>
      </div>
    </div>

    <span class="helpBtn" @click="isHelp= !isHelp">什么是智能推荐组合套餐？</span>
    <div class="helpContent" v-if="isHelp" v-html="helpContent"></div>

    <div class="isCombine">
      是否参与智能推荐组合套餐
      <span class="toggleBtn" :class="{'active': infomations.combine}" @click="infomations.combine= !infomations.combine"></span>
    </div>
    <div class="combine" v-if="infomations.combine">
      <div class="inputItem">
        <label>套餐折扣</label>
        <input type="text" id="discount" placeholder="保留几位小数？" v-model="infomations.discount">
        <span class="unit">折</span>
      </div>
      <div class="showList">
        <div class="showItem">
          <span class="text">用户享受折扣</span>
          <span class="num">{{C_discountList[0]}}%</span>
        </div>
        <div class="showItem">
          <span class="text">商户实收</span>
          <span class="num">{{C_discountList[1]}}元</span>
        </div>
        <div class="showItem">
          <span class="text">用户实际支付</span>
          <span class="num">{{C_discountList[2]}}元</span>
        </div>
      </div>
    </div>
    <!-- 错误提示 -->
    <div class="errmsg" v-if="errmsg">{{errmsg}}</div>

    <button class="submitBtn" @click="handleSubmit">保存</button>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: "EditGoods",
  data () {
    return {
      infomations: {
        id: '',
        name: '',
        type: 0,
        src: '',
        des: '',  
        inventory: '',
        price: '',
        combine: false,
        discount: ''
      },
      goodTypes: [],
      rates: '',
      isHelp: false,
      helpContent: '',
      errmsg: ''
    }
  },
  methods: {
    addImg () {//更新图片
      let img = this.$refs.img.files[0],//验证大小
          fileSize = Math.round((img.size/(1024*1024))*100)/100,//单位为MB,两位小数
          maxSize = 6
      if (fileSize > maxSize) {
        this.errmsg = '文件大小限制 6M 以内'
        return false
      }
      let updateForm = new FormData() // 新建表单，插入图片
      updateForm.append('img_url', img)
      //设置配置，添加表头，发送数据
      const config = {
          headers: {'Content-Type': 'multipart/form-data'}
        }
      axios.post('/merchant/goods_img_upload', updateForm, config)
        .then((res) => {
          let data = res.data
          if (data.errmsg == 'success') {
            this.errmsg = "图片上传成功"
            this.infomations.src = data.src
          } else {
            this.errmsg = data.errmsg
          }
        })
        .catch((err) => {
          console.log(err)
        })
    },
    handleSubmit () { //提交数据
      if (this.checkForm()) {
        axios.post('/merchant/goods_info_action', this.packData())
        .then((res) => {
          let errmsg = res.data.errmsg
          if (errmsg == 'success') {
            this.errmsg = '资料保存成功！'
          } else {
            this.errmsg = errmsg
          }
        })
      }
    },
    checkForm () { //检查表单数据
      const obj = this.infomations
      for (let i in obj) {
        if ( //库存、折扣、ID、组合除外
          i !== 'inventory' &&
          i !== 'discount' &&
          i !== 'id' &&
          i !== 'combine'
        ) { 
          if (!obj[i]) {
            this.errmsg = "请完整添加商品信息";
            return false
          }
        } else {
          if (i == 'price') { //验证价格格式
            let ret = /^[0-9]*$/;//数字
            if (obj[i] <= 0 || !ret.test(obj[i])) {
              this.errmsg = "请填写正确的价格格式";
              return false 
            }
          } else if (i == 'combine' && obj.combine) { //验证打折信息
            let ret = /^[0-9]+(.[0-9]{0,2})?$/,//数字，max两位小数
                discount = obj.discount
            if (!ret.test(discount) || discount >= 10) {
              this.errmsg = "请填写正确的折扣数";
              return false 
            }
          }          
        }
      }
      return true
    },
    packData () { //打包数据
      // 添加配置项
      let info = {},
          userData = this.infomations;
      info.secret = '73715fefabc2195fa0e14239';
      info.tbname = 'goods_info';
      if (!userData.id) { // 区分新增或更新
        info.action = 'add'
      } else {
        info.action = 'update'
        info.where = 'goods_id=' + userData.id
      }
      // 存入数据
      info.goodsname = userData.name
      info.category_id = userData.type
      info.img_url = userData.src
      info.introduction = userData.des
      info.stock = userData.inventory || '99';
      info.net_receipts = userData.price
      info.is_combination = userData.combine ? 1:2
      info.combination_discount = userData.combine ? userData.discount*0.1 : 1
     
      return info
    },
    getInitInfo () { //获取初始化数据
      axios.get('/goods_message', {
        params: {
          id: this.$route.params.id
        }
      })
        .then((res) => {
          let data = res.data
          if (data.errmsg == 'success') {
            this.rates = data.service_rate
            this.helpContent = data.combination_introduce
            // 构建分类数组
            let goodTypes = this.goodTypes,
                category = data.goods_category
            for (let i=0,len=category.length; i<len; i++) {
              goodTypes.push({}) //添加一个数组项
              goodTypes[i].id = category[i].category_id
              goodTypes[i].type = category[i].category
            }
            if (data.goods_info) {//编辑商品的情况下
              let _this = this.infomations,
                  info = data.goods_info
              //转换数据
              _this.id = info.goods_id
              _this.name = info.goodsname
              _this.type = info.category_id
              _this.src = info.img_url
              _this.des = info.introduction
              _this.inventory = info.stock
              _this.price = info.net_receipts
              _this.combine = info.is_combination == 2? false:true
              _this.discount = info.combination_discount*10
            }
          } else {
            this.errmsg = "数据好像出了点异常"
          }
        })
    }
  },
  mounted () {
    this.getInitInfo() //获取初始化数据
  },
  computed: {
    C_priceList () {  //计算下收付
      let rates = this.rates,
          price = this.infomations.price
      let  pList = [
        rates*100,
        (Number(price)).toFixed(1),
        (price*(1 + rates)).toFixed(2)
      ]
      return pList
    },
    C_discountList () { //计算组合下收付
      if (this.infomations.combine) { //有组合是才执行
        let price = this.infomations.price,
            discount = this.infomations.discount
        let dList = [
          (10-discount)*10,
          (price*(0.1*discount)).toFixed(1),
          (price*(1 + this.rates)*(0.1*discount)).toFixed(2)
        ]
        return dList
      }
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
  @import '../../../styles/mixins'
  
  .title
    text-align: center
    font-size: 24px
    line-height: 60px
    margin-bottom: 30px
  .inputItem
    inputItem()
    label
      itemLabel()
      width: 64px
    input
      itemInput()
      text-align: center
    textarea
      itemTextarea()
    select
      itemSelect()
    .unit
      background-color: #ddd
      font-size: 16px
      padding: 6px 14px
      height: 20px
      line-height: 20px
      width: 60px
      border-radius: 6px
  .imgBox
    img
      width: 100%
  .showList
    width: 80%
    margin: 0 auto
    border: 2px solid #ddd
    border-radius: 6px
    padding: 10px
    line-height: 20px
    .showItem
      display: flex
      justify-content: space-between
  .helpBtn
    display: block
    width: 200px
    line-height: 20px
    text-align: center
    margin: 0 auto
    color: #0084f5
    font-weight: bold
  .isCombine
    overflow: hidden
    border: 1px solid #999
    width: 80%
    margin: 0 auto
    padding: 5px
    line-height: 20px
    .toggleBtn
      float: right
      display: block
      height: 20px
      width: 40px
      border-radius: 40px
      border: 2px solid #eee
      box-shadow: inset 1px 1px 3px 0px #666
      background-color: #ffc0cb
      transition: all .2s
      &:after
        display: block
        content: ''
        width: 14px
        height: 14px
        margin: 3px
        border-radius: 20px
        background-color: #fff
        transition: all .2s
    .active
      background-color: #9cf59c
      &:after
        margin-left: 23px
  .errmsg
    errmsg()
  .submitBtn
    blueBigBtn()
</style>