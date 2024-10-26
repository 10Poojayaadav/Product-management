<template>
   <v-app>
    <div class="page">
      <div class="main">
        <v-main class="body">
          <div class="content">
            <Header/>
            <div class="content-area">
              <div class="container-md">
                <div class="mb-2">
                  <div class="hero-section" id="hero-section">
                    <div class="hero-image" @click="navigateData(first_row)">
                      <img class="image" :src="first_row.card_image" />
                    </div>
                  </div>
                </div>
                <div class="nested-tabs">
                  <div class="sq-tabs">
                    <TabPage :type="type"/>
                     <VideoPage v-if="openVideo" :id="v_id" @close="closeVideo"/>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </v-main>   
      </div>
    </div>
  </v-app>
</template>
<script>
import VideoPage from "../common/VideoPage.vue";
import Header from "../layout/Header.vue";
import axios from "../../router/axios.js";
import TabPage from "../../components/TabPage.vue";
export default {
  data: () => ({
    openVideo:false,
    tab: null,
    childtab: null,
    first_row: {},
    pray_list: [],
    library_list:[],
    type:"pray-list",
  }),
  components: {
    Header,
    TabPage,
    VideoPage,
  },
  mounted() {
    this.prayListing();
  },
  methods: {
    closeVideo(){
       this.openVideo=false;
    },
    prayListing() 
    {
      axios
        .post("pray-list")
        .then((response) => {
          console.log(response);
          this.pray_list = response.data.data;
          this.first_row = this.pray_list[0];
          this.pray_list.splice(0, 1);
        })
        .catch((error) => {
          console.error("Error fetching data:", error);
        });
    },
    navigateData(card) 
    {
      if (card.media_type == "video") {
         this.v_id=card.id;
         this.openVideo=true;
      }
    },
  },
};
</script>





