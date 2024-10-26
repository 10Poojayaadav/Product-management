<template>
  <v-tabs v-model="tab" class="parent-tabs" >
    <v-tab  v-for="(v1, index) in fellow_list" :key="index">
      <span @click="navigateData(v1)">{{v1.title}}</span>
    </v-tab>
  </v-tabs>
  <v-card-text class="parent-tab-contents">
      <div v-if="get_data == 'group'">
        <!-- <GroupPage /> -->
      </div>
      <div v-else-if="get_data == 'friends'">
        <!-- <FriendPage /> -->
      </div>
  </v-card-text>
</template>
<script>
import axios from "../../router/axios.js";
import GroupPage from "./Group.vue";
import FriendPage from "./FriendPage.vue";

export default {
  data()
  {
    return {
      tab:null,
      fellow_list:[],
      isLoading:true,
      get_data:"",
    };
  },
  components :{
    GroupPage,
    FriendPage
  },
  mounted() {
    this.fellowList();
  },
  methods: {
   fellowList() {
      axios
        .post("fellowship-list")
        .then((response) => {
          this.fellow_list = response.data.data;
          this.fellow_list.splice(0, 1);
          this.navigateData(this.fellow_list[0]);
          this.isLoading = false;
        })
        .catch((error) => {
          console.error("Error fetching data:", error);
        });
    },
    navigateData(card)
    {
      if(card.card_type == 'group') { this.get_data = 'group'; }
      else if(card.card_type == 'friends') { this.get_data = 'friends'; }
      else if(card.card_type == 'messages') {this.$router.push({name:'messages'}); }
      else { }
    }
  }
}
   
</script>