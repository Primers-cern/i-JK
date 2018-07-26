<template>
  <div>
    <p>当前登陆账号：<span class="loginedAcc">{{account}}</span></p>
    <button class="changeAcc" @click="handleChangeAcc">切换账号</button>
  </div>
</template>

<script>
export default {
  name: "LoginAcount",
  props: {
    account: String
  },
  methods: {
    handleChangeAcc () {
      let setEmpty = {}
      setEmpty.account = ''
      setEmpty.shopList = []

      this.$emit('changeAcc', setEmpty)
    }
  }
}
</script>

<style lang="stylus" scoped>
  @import '../../../../styles/mixins'

  .loginedAcc
    color: #00bcd4
    text-decoration: underline
  .changeAcc
    blueBigBtn()
</style>