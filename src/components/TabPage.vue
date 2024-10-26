<template>
  <v-tabs v-model="tab" class="parent-tabs" >
    <v-tab :value="index" v-for="(v1, index) in pray_list" @click="navigateData(v1)" 
    :key="index" :class="v1.id == l_id ? 'v-slide-group-item--active' : ''"  :disabled="(v1.card_type == 'my_prayer' || v1.card_type == 'answered_prayer' || v1.card_type == 'create_prayer') ? true : false">{{v1.title}}</v-tab>
  </v-tabs>
  <v-card-text class="child-tabs-contents">
    <v-window v-model="childtab">
        <SubTabPage v-if="show" :id="l_id" />
    </v-window>
  </v-card-text>
</template>
<script>   
import axios from "../router/axios.js";
import SubTabPage from "./SubTabPage.vue";

export default {
  data()
  {
    return {
      l_id:null,
      tab: null,
      childtab: null,
      pray_list:[],
      show:false,
    };
  },
  props:{
    type:{
      type:String,
      default:() => 'pray',
    }
  },
  components:{
    SubTabPage,
  },
  mounted() {
    this.prayListing();
  },
  methods: {
    prayListing() 
    {
      axios
        .post(this.type)
        .then((response) => {
          console.log(">>>>>>>>>>>>>>>>>>>>>>>", response);
          this.pray_list = response.data.data;
          this.pray_list.splice(0, 1);
          this.navigateData(this.pray_list[0]);
        })
        .catch((error) => {
          console.error("Error fetching data:", error);
        });
    },
    navigateData(card)
    {
      this.show = true;
      if(card.content_type == 'library')
      {
        this.l_id = card.id;
        this.show = true;
      }
      else {
        if (card.media_type == "audio") {
          this.$router.push({
            name: "audio",
            params: { id: card.id, section: "pray" },
          });
        }
        if (card.media_type == "video") {
          this.$router.push({
            name: "video",
            params: { id: card.id, section: "pray" },
          });
        }
        if (card.content_type == "pdf") {
          this.$router.push({
            name: "pdf",
            params: { id: card.id, section: "pray" },
          });
        }
        if (card.media_type == "slider") {
          this.$router.push({
            name: "slider",
            params: { id: card.id, section: "pray" },
          });
        }
        if (card.media_type == "hyperlink") {
           if(card.media!=null){ window.open(card.media,'_blank'); }
           else {  this.prayListing(); }
         }
      }
    },
  }
}
   
</script>