<template>
  <div>
    GoodsManage
    <goods class="goods">
      <button>编辑</button>
      <button>下架</button>
      <button>删除</button>
    </goods>
  </div>
</template>

<script>
import Goods from './components/Goods'
import axios from 'axios'

export default {
  name: "GoodsManage",
  components: {
    Goods
  },
  data () {
    return {
      goodsList: []
    }
  }

}
</script>

<style lang="stylus" scoped>
  .goods
    button
      height: 30%
      border-radius: .1rem
      margin: 0 .1rem
</style>