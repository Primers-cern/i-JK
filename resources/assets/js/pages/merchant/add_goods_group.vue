<template>
  <div>
     <div class="group">
     	<span>分类名称</span><input type="text" name="group" v-model="groupName">
     	<button  @click="handleBtn">保存</button>
     </div>
  </div>
</template>

<script>
import axios from 'axios' 

export default {
  name: "add_goods_group",
  data:function(){
  	 return {groupName:""};
  },
  methods:{
   handleBtn:function(){
   	var $this=this;
  	axios.post('/merchant/add_goods_group', {
    tbname: 'goods_category',
    category: this.groupName,
    secret:'73715fefabc2195fa0e14239',
  })
  .then(function (response) {
  	response.data.errmsg=="success"?$this.$router.push("/goods_group_list"):alert("添加失败");
    
  })
  .catch(function (error) {
    console.log(error);
  });
   }
  }
 
}
</script>

<style lang="stylus" scoped>
 
</style>