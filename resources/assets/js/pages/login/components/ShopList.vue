<template>
  <div class="wrapper">
    <ul class="list">
      <li class="item" v-for="item of shopList" :key="item.merchant_id">
        <img class="itemImg" :src="item.merchant_logo">
        <p class="itemName">{{item.merchant_name}}</p>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: "ShopList",
  props: {
    shopList: Array
  },
  data () {
    return {
    }
  }
}
</script>

<style lang="stylus" scoped>
  .wrapper
    padding: 0
    margin: 0
    .list
      display: block
      padding: 0
      .item
        float: left
        overflow: hidden
        width: 46%
        height: 0
        padding-bottom: 54%
        margin: 0 2% 20px 2%
        background-color: #dfdfdf
        border-radius: 5px
        font-size: 0
        .itemImg
          display: block
          width: 80%
          margin: 20px auto 0 auto
        .itemName
          display: block
          font-size: 16px;
          line-height: 40px;
          text-align: center
          margin: 0
</style>