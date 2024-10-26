<template>
  <v-app>
    <div class="page">
      <div class="main">
        <v-main class="body">
          <div class="content">
            <Header />
            <div class="pray screen">
              <div class="message-sidebar pane" style="overflow-y:auto" v-on:scroll="scrollLibararyView($event)" id="library-view">
                <div class="back_btn">
                  <v-btn  @click="backPage">
                    <img src="/src/assets/back-arrow.svg" alt="">
                    {{ title }}
                  </v-btn>
                </div>
                <div v-for="(v1, index) in library_list" :key="index"  >
                  <img :src="v1.banner_image" class="sidebar-image" @click="navigateData(v1, 'l_list')">
                </div>
              </div>
              <div class="library_content2" v-if="grid" v-on:scroll="scrollListView($event)" id="list-view">
                <div class="library_content"  v-if="pdfPage && pdfId">
                  <Pdf v-if="pdfPage && pdfId" :id="pdfId"/>
                </div>
                <div v-else style="padding:30px;" >
                 <div v-for="(chunk, chunkIndex) in chunkedGridList" :key="chunkIndex" class="grid-chunk">
                  <div class="grid2x2" v-for="(v1, index) in chunk" :key="index">
                    <div class="grid-item">
                      <img :src="v1.banner_image"  @click="navigateData(v1)">
                    </div>
                  </div>
                </div>
                </div>
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
import Pdf from "../common/Pdf.vue";
import VideoPage from "../common/VideoPage.vue";
import AudioPage from "../common/AudioPage.vue";

export default {
  data: () => ({
    pdfPage: false,
    openVideo: false,
    tab: null,
    childtab: null,
    first_row: {},
    pray_list: [],
    grid_list: [],
    library_list: [],
    type: "pray-list",
    cardId: null,
    pdfId: null,
    title: "",
    grid:false,
    gridTitle:'',
    page:1,
    last_page:"",
    openVideo:false,
    openAudio:false,
    libraryPage:1,
    library_last_page:"",
  }),
  components: {
    Header,
    Pdf,
    VideoPage,
    AudioPage,
  },
  mounted() {
    this.title = this.$route.query.title;
    this.library();
  },
   computed: {
    chunkedGridList() {
      const chunkSize = 6;
      const chunks = [];
      for (let i = 0; i < this.grid_list.length; i += chunkSize) {
        chunks.push(this.grid_list.slice(i, i + chunkSize));
      }
      console.log(chunks,"chunkschunkschunkschunks");
      return chunks;
    },
  },
  props: {
    id: {
      type: String,
      default: () => '',
    }
  },
  methods: {
     closeVideo(){
       this.openVideo=false;
    },
    closeAudio(){
      this.openAudio=false;
    },
    backPage() {
      this.$router.go(-1);
    },
    closeVideo() {
      this.openVideo = false;
    },
    pdfPages(card) {
      this.pdfPage = true;
      this.pdfId = card.id;
    },
    library() {
      axios
        .post("lord-pray-content-list", {
          id: this.$route.params.id,
          type: "pray",
          page: this.libraryPage,
        })
        .then((response) => {
          this.library_list = response.data.data.data;
          this.library_last_page= response.data.data.data.last_page;
          if (this.library_list.length > 0) {
            this.navigateData(this.library_list[0], 'l_list');
          }
        })
        .catch((error) => {
          console.error("Error fetching data:", error);
        });
    },
    scrollListView(event) {
      console.log(this.page , this.last_page, "pooja yadav");
      var obj = document.getElementById("list-view");
      if ((parseInt(obj.scrollTop + 2) >= (obj.scrollHeight - obj.offsetHeight)) 
      && this.page != this.last_page) {
          this.page++;
          axios
           .post("lord-pray-content-list", {
            id: this.cardId,
            type: "pray",
            page: this.page,
            })
            .then((response) => {
              var arr = response.data.data.data;
              if(arr.length == []){
                return false;
              }
              if(this.page != 1)
              {
                var arr = response.data.data.data;
                this.grid_list = this.grid_list.concat(arr);
              }
              else
              {
                this.grid_list = response.data.data.data;
              }
            })
            .catch(async (error) => {
              console.log("Error fetching data:", error);
              if(error.response.data.status_code == 401)
              {
               await logOut(error);
              }
            }); 
          }
    },
    scrollLibararyView(event) {
      var obj = document.getElementById("library-view");
      if ((parseInt(obj.scrollTop + 2) >= (obj.scrollHeight - obj.offsetHeight)) 
      && this.libraryPage != this.library_last_page) {
          this.libraryPage++;
           axios
            .post("lord-pray-content-list", {
              id: this.$route.params.id,
              type: "pray",
              page: this.libraryPage,
            })
                .then((response) => {
              if(this.library_last_page != 1)
              {
                var arr = response.data.data.data;
                this.library_list = this.library_list.concat(arr);
              }
              else
              {
                this.library_list = response.data.data.data;
              }
            })
            .catch(async (error) => {
              console.log("Error fetching data:", error);
              if(error.response.data.status_code == 401)
              {
               await logOut(error);
              }
            }); 
          }
      },
    navigateData(card, source) {
    if (card.content_type == "library") {
        this.gridTitle = card.title;
        this.grid = true;
        this.pdfPage = false;
        this.cardId = card.id;
        // axios
        //   .post("lord-pray-content-list", {
        //     id: card.id,
        //     type: "pray",
        //     page:this.page,
        //   })
        //   .then((response) => {
        //     console.log("responseresponseresponse",response);
        //     this.grid_list = response.data.data.data;
        //     this.last_page = response.data.data.data.last_page;
        //   })
        //   .catch((error) => {
        //     console.error("Error fetching data:", error);
        //   });

          const page1Request = axios.post("lord-pray-content-list", {
            id: card.id,
            type: "pray",
            page:this.page, 
          });

          const page2Request = axios.post("lord-pray-content-list", {
            id: card.id,
            type: "pray",
            page:this.page+1, 
          });

          Promise.all([page1Request, page2Request])
            .then((responses) => {
              const page1Data = responses[0].data.data.data || [];
              const page2Data = responses[1].data.data.data || [];
              this.grid_list = [...page1Data, ...page2Data];
              this.last_page = Math.max(
                responses[0].data.data.last_page || 1,
                responses[1].data.data.last_page || 1
          );
              this.page = 2;
          console.log('grid_list List:', this.grid_list);
          })
          .catch((error) => {
            console.error("Error fetching data:", error);
          });
      } 
      else {
        if (card.media_type == "audio") {
          this.a_id=card.id;
          this.openAudio=true;
        }
        if (card.media_type == "video") {
         
            this.v_id=card.id;
            this.openVideo=true;
        }
        if (card.media_type == "pdf" && source === "l_list") {
          this.pdfPage = true;
          this.pdfId = card.id;
        }
        if (card.media_type == "pdf" && source !== "l_list") {
          this.pdfId = card.id;
          this.$router.push({
            name: "library2",
            params: { id: this.cardId, section: "meditate" },
            query: { pdfId: this.pdfId, gridTitle:this.gridTitle},
          });
        }
        if (card.media_type == "slider") {
          this.$router.push({
            name: "slider",
            params: { id: card.id, section: "meditate" },
          });
        }
        if (card.media_type == "hyperlink") {
          if (card.media != null) {
            window.open(card.media, '_blank');
          } else {
            this.proclaim();
          }
        }
      }
    },
  },
};
</script>
