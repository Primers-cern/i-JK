<template>
  <div>
    <router-link to="/merchant/12">带有 ID 的测试链接</router-link>
    <router-link to="/merchant">不带 ID 的测试链接</router-link>
    <p>price</p>
    <input type="text" v-model="price">
    <p>discount</p>
    <input type="text" v-model="discount">
    <p>rates: {{rates}} %</p>
    <div class="box">
      <div v-for="(item, index) in newList" :key="index">{{item}}</div>
    </div>

    <button @click="booler= !booler">按钮状态是 {{booler}}</button>
    <p>{{isNo}}</p>
  </div>
</template>

<script>
export default {
  name: "Test",
  data () {
    return {
      price: '',
      discount: '10',
      rates: '8',
      booler: true
    }
  },
  computed: {
    newList () {
      let discount = this.discount,
          price = this.price,
          list = [
            (10-discount)*10,
            price*(0.1*discount),
            price*(1 + this.rates)*(0.1*discount)
          ]
      return list
    },
    isNo () {
      let aaa
      if (this.booler) {
        aaa = 1111
      } else {
        aaa = 2222
      }
      return aaa
    }
  },
  mounted () {
    let i = 1
    if (
      i !== 2 & 1 & 4 & 5
    ) {
      alert('??')
    }
  }
}
</script>

<style lang="stylus" scoped>
  a
    display: block
    line-height: 30px
  .box
    width: 200px
    background: pink
    color: #333
</style>
