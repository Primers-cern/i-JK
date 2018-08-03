<template>
  <div>
    <div class="wrapper" v-for="item in list" :key="item">
      <div class="itemImg">
        <img src="http://img1.qunarzz.com/sight/p0/1409/19/adca619faaab0898245dc4ec482b5722.jpg_150x150_06cb3e52.jpg">
      </div>
      <div class="Infos">
        <p class="name">this is title</p>
        <p class="price">￥9.9</p>
        <p class="inventory">库存: 999</p>
      </div>
      <div class="operateBox" :class="item">
        <slot></slot>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Goods",
  data () {
    return {
      list: [1,2,3]
    }
  }
}
</script>

<style lang="stylus" scoped>
  .wrapper
    background-color: #fff
    border: 2px solid #bbb
    border-radius: 10px
    overflow: hidden
    display: flex
    .itemImg
      width: 2rem
      height: 2rem
      img
        width: 100%
        height: 100%
    .Infos
      padding: .2rem
      flex: 1
      display: flex
      flex-direction: column
      justify-content: space-around
      .name
        font-size: .32rem
        font-weight: bold
      .price
        font-size: .28rem
        color: red
    .operateBox
      background-color: #bbb
      width: 1rem
      display: flex
      flex-direction: column
      justify-content: space-around
</style>