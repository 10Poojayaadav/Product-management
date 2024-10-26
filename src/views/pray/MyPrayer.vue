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
                  Pray
                </h1>
                <div class="content">
                  <p>Pray to God all the time. Talk, listen, ask, and respond to God to live out Jesus’ promise of an abundant life.</p>
                </div>
                <div class="prayer-functions">
                  <div class="function" v-for="(v1, index) in pray_list">
                    <div class="card"  @click="navigateData(v1)">
                      <template v-if="v1.content_type == 'library'">
                      <img :src="v1.card_image" alt="" height="100%"> 
                      </template>
                       <template v-else>
                      <img :src="v1.card_image" alt="" height="100%" style="opacity:70%"> 
                      </template>
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
          console.log(response,"response of pray");
          this.pray_list = response.data.data;
          this.first_row = this.pray_list[0];
          console.log("first_row", this.first_row);
          this.pray_list.splice(0, 1);
        })
        .catch((error) => {
          console.error("Error fetching data:", error);
        });
    },
    navigateData(card) {
      if(card.card_type == "my_prayer"){

      }
      else if(card.card_type == "answered_prayer"){

      }
      else if(card.card_type == "create_prayer"){

      }
      else if(card.content_type == "media"){
        if(card.media_type == "audio"){
          this.a_id=card.id;
          this.openAudio=true;
        }
        else if(card.media_type == "video"){
          this.v_id=card.id;
          this.openVideo=true;
        }
        else if(card.media_type == "pdf"){
          const libraryTitle = card.library[0].title;
          this.$router.push({
            name: "library2",
            params: { id: card.library_id, section: "pray" },
            query: { pdfId: card.id , gridTitle:libraryTitle},
          });
        }
        else if(card.media_type == "image"){
          const libraryTitle = card.library[0].title;
          this.$router.push({
            name: "library2",
            params: { id: card.library_id, section: "pray" },
            query: { imageId: card.id , gridTitle:libraryTitle},
          });
        }
      }
      else if(card.content_type == "slider"){
        const libraryTitle = card.library[0].title;
          this.$router.push({
            name: "library2",
            params: { id: card.library_id, section: "pray" },
            query: { imageId: card.id , gridTitle:libraryTitle},
        });
      }
      else if(card.content_type == "library"){
        if(card.library_type == "old"){
          this.$router.push({
            name: "library",
            params: { id: card.id, section: "pray"  },
            query: { title:card.title},
          });
        }
        else{
          this.$router.push({
            name: "new-library",
            params: { id: card.id, section: "pray"  },
            query: { title:card.title },
          });
        }
      }
      else if(card.content_type == "hyperlink"){
        if(card.media!=null){
          window.open(card.media,'_blank'); 
        }
        else{
          this.prayListing(); 
        }
      }
      else{
        //nothing in card related anything
      }
















      // if (card.content_type == "library") {
      //   if(card.library_type=="old" || card.library_type== null ){
      //     this.$router.push({
      //       name: "library",
      //       params: { id: card.id, section: "pray"  },
      //       query: { title:card.title},
      //     });
      //   }
      //   else{
      //     this.$router.push({
      //       name: "new-library",
      //       params: { id: card.id, section: "pray"  },
      //       query: { title:card.title },
      //     });
      //    }
      // } 
      //  else {
      //   if (card.media_type == "audio") {
      //     this.a_id=card.id;
      //     this.openAudio=true;
      //   }
      //   if (card.media_type == "video") {
         
      //       this.v_id=card.id;
      //       this.openVideo=true;
      //   }
      //   if (card.media_type == "pdf") {
      //     const libraryTitle = card.library[0].title;
      //     this.$router.push({
      //       name: "library2",
      //       params: { id: card.library_id, section: "pray" },
      //       query: { pdfId: card.id , gridTitle:libraryTitle},
      //     });
      //   }
      //   if (card.media_type == "slider") {
      //     this.$router.push({
      //       name: "slider",
      //       params: { id: card.id, section: "pray" },
      //     });
      //   }
      //   if (card.media_type == "hyperlink") {
      //      if(card.media!=null){ window.open(card.media,'_blank'); }
      //      else {  this.prayListing(); }
      //   }
      // }
    },
  }
};
</script>





