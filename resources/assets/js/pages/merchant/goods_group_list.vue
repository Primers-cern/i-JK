<template>
  <div>
     <ul>
       <li v-for="item of groupList">{{item}}</li>
     </ul>
  </div>
</template>

<script>
import axios from 'axios' 

export default {
  name : "goods_group_list",
  data : function(){
      return {
        groupList : [1,2,3]
      }
  }
 
}
</script>

<style lang="stylus" scoped>
 
</style>