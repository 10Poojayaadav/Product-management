<template>
   <v-app>
    <div class="page">
      <div class="main">
        <v-main class="body">
          <div class="content">
          <Header/>
            <div class="pray screen">
              <video
                    :src="first_row.media"
                    type="video/mp4"
                    autoplay
                    muted
                  ></video>
              <div class="content_wrapper">
                <h1>
                  Proclaim
                </h1>
                <div class="content">
                  <p>Proclaim Jesus and your true identity in Him daily. Believe what He says about you to keep you on track to living a life that thrives.
                  </p>
                </div>
                <div class="prayer-functions">
                  <div class="function" v-for="(v1, index) in pray_list">
                    <div class="card" >
                      <img :src="v1.card_image" alt="" height="100%" @click="navigateData(v1)">
                    </div>
                  </div>
                </div>
                <button class="play-button" @click="navigateData(first_row)">
                <img src="/src/assets/play.png" alt="Play Icon">
                Play Video
              </button>
              </div>
            </div>
            </div>
             <VideoPage v-if="openVideo" :id="v_id" @close="closeVideo"/>
          <AudioPage v-if="openAudio" :id="a_id"  @close="closeAudio"/>
        </v-main>   
      </div>
    </div>
  </v-app>
</template>
<script>
import Header from "../layout/Header.vue";
import axios from "../../router/axios.js";
import VideoPage from "../common/VideoPage.vue";
import AudioPage from "../common/AudioPage.vue";
export default {
  data: () => ({
    openVideo:false,
    openAudio:false,
    tab: null,
    childtab: null,
    first_row: {},
    pray_list: [],
    library_list:[],
    type:"pray-list",
  }),
  components: {
    Header,
     VideoPage,
    AudioPage,
    // TabPage,
    // VideoPage,
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
        .post("proclaim-list")
        .then((response) => {
          console.log(response,"<<<<<<<<<<<<<<<<,");
          this.pray_list = response.data.data;
          this.first_row = this.pray_list[0];
          this.pray_list.splice(0, 1);
        })
        .catch((error) => {
          console.error("Error fetching data:", error);
        });
    },
     navigateData(card) {
      if (card.content_type == "library") {
      if(card.library_type=="old" || card.library_type== null && media_type== "library" ){
        this.$router.push({
          name: "library",
          params: { id: card.id, section: "proclaim"  },
          query: { title:card.title },
        });
      }
      else{
        this.$router.push({
          name: "new-library",
          params: { id: card.id, section: "proclaim"  },
          query: { title:card.title },
        });
      }} 
       else {
        if (card.media_type == "audio") {
          this.a_id=card.id;
          this.openAudio=true;
        }
        if (card.media_type == "video") {
         
            this.v_id=card.id;
            this.openVideo=true;
        }
        if (card.media_type == "pdf") {
          this.$router.push({
           name: "pdf",
            params: { id: card.id, section:'proclaim' }
             });
        }
        if (card.media_type == "slider") {
          this.$router.push({
            name: "slider",
            params: { id: card.id, section: "Proclaim" },
          });
        }
        if (card.media_type == "hyperlink") {
           if(card.media!=null){ window.open(card.media,'_blank'); }
           else {  this.prayListing(); }
        }
      }
    }
  },
};
</script>





