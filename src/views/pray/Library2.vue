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
                  <v-btn @click="backPage">
                    <img src="/src/assets/back-arrow.svg" alt="">
                    {{ gridTitle }}
                  </v-btn>
                </div>
                <div class="library_list_wrapper">
                <div v-for="(v1, index) in library_list" :key="index"  :class="['library_list', { active: activeIndex === index }]">
                  <img :src="v1.banner_image" class="sidebar-image" @click="navigateData(v1, index)">
                </div>
              </div>
              </div>
              <div class="library_content2" style="overflow-y:hidden;">
                <div v-if ="pdfPage" class="grip_photos" style="padding: 0;">
                  <Pdf v-if="pdfPage && pdfId" :id="pdfId" />
                </div>
                <!-- Image page import -->
                <div v-else-if="imagePage">
                  <ImagesPage v-if="imagePage":id="imageId"/>
                </div>
               <!-- slider page import -->
               <div v-else-if="sliderPage" style="height:100%; width:100%;">
                  <SliderPage v-if="sliderPage && sliderId" :id="sliderId" class="min-h-screen"/>
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
import ImagesPage from "../common/ImagesPage.vue";
import SliderPage from "../common/SliderPage.vue";

export default {
  data() {
    return {
      pdfPage: false,
      openVideo: false,
       imagePage:false,
       sliderPage:false,
      tab: null,
      childtab: null,
      first_row: {},
      pray_list: [],
      grid_list: [],
      library_list: [],
      mediaList: {},
      type: "pray-list",
      cardId: null,
      pdfId: null,
      imageId:null,
      sliderId: null,
      gridTitle: "",
      libraryPage:1,
      library_last_page:"",
      openVideo:false,
      openAudio:false,
      prePage:"",
      section:null,
       activeIndex: null,
    };
  },
  components: {
    Header,
    Pdf,
    VideoPage,
    AudioPage,
    ImagesPage,
    SliderPage,
  },
  created() {
    this.pdfId = this.$route.query.pdfId;
    this.imageId = this.$route.query.imageId;
    this.sliderId = this.$route.query.sliderId;
    this.pdfPage = !!this.pdfId;
    this.imagePage = !!this.imageId; 
    this.sliderPage = !!this.sliderId;// Set pdfPage to true if pdfId exists
  },
  mounted() {
    this.section = this.$route.params.section;
    this.gridTitle = this.$route.query.gridTitle;
    this.library();
  },
  methods: {
    backPage() {
    const previousUrl = localStorage.getItem('previousUrl');
    if(previousUrl){
    window.location.href = previousUrl; 
    }
    else{
       this.$router.go(-1);
    }
    // if (previousUrl && this.section == "pray") {
    //   window.location.href = previousUrl;
    // } else if(previousUrl && this.section == "proclaim") {
    //    this.$router.go(-1);// Navigate to a default page if no previous URL is found
    // }
    },
    closeVideo() {
      this.openVideo = false;
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

          const queryId = this.$route.query.pdfId; // or parseInt() if id is a number
         
          for(let i = 0 ; i<this.library_list.length ; i++ ){            

               if(this.library_list[i].id == queryId ){              
                   this.navigateData(this.library_list[i], i);
               }
          }
        })
        .catch((error) => {
          console.error("Error fetching data:", error);
        });
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
  scrollToTop() {
    const sidebar = document.querySelector('#library-view');
    const items = document.querySelectorAll('#library-view .library_list_wrapper > div');
    items.forEach((item, index) => {
      item.addEventListener('click', () => {
        const sidebarHeight = sidebar.offsetHeight;
        const itemHeight = item.offsetHeight;
        const itemRect = item.getBoundingClientRect();
        const sidebarRect = sidebar.getBoundingClientRect();
        const itemTopRelativeToSidebar = itemRect.top - sidebarRect.top;
        const scrollPosition = itemTopRelativeToSidebar - (sidebarHeight / 2) + (itemHeight / 2);
        sidebar.scrollTop += scrollPosition ;
       
      });
    });

  },
  navigateData(card, index) {
    this.activeIndex = index;
    const url = new URL(window.location.href);
    this.$nextTick(() => {
        this.scrollToTop(); // Scroll to top after updating the DOM
      });
    // Resetting all states
    this.imagePage = false;
    this.pdfPage = false;
    this.sliderPage = false; // Resetting sliderPage state
    this.openAudio = false;
    this.openVideo = false;

    if (card.media_type === "audio") {
      this.a_id = card.id;
      this.openAudio = true;
      url.searchParams.delete('pdfId');
      url.searchParams.delete('sliderId');
    } else if (card.media_type === "video") {
      this.v_id = card.id;
      this.openVideo = true;
      url.searchParams.delete('pdfId');
      url.searchParams.delete('sliderId');
    } else if (card.media_type === "image") {
      this.imagePage = true;
      this.imageId = card.id;
      url.searchParams.delete('pdfId');
      url.searchParams.delete('sliderId');
    } else if (card.media_type === "pdf") {
      this.pdfPage = true;
      this.pdfId = card.id;
      url.searchParams.set('pdfId', card.id);
      url.searchParams.delete('sliderId');
    } else if (card.content_type === "slider") {
      console.log("Slider media detected:", card);
      this.sliderPage = true;
      this.sliderId = card.id;
      url.searchParams.set('sliderId', card.id);
      url.searchParams.delete('pdfId');
    } else if (card.media_type === "hyperlink") {
      if (card.media != null) {
        window.open(card.media, "_blank");
      } else {
        this.proclaim();
      }
    }

    // Push the updated URL to history
    window.history.pushState({}, '', url.toString());

    if (card.content_type === "library") {
      this.cardId = card.id;
      axios
        .post("lord-pray-content-list", {
          id: card.id,
          type: "pray",
        })
        .then((response) => {
          this.grid_list = response.data.data.data;
        })
        .catch((error) => {
          console.error("Error fetching data:", error);
        });
    }
  },
  },
};
</script>
<style scoped>
.library_list.active img {
  padding: 5px;
  border: 3px solid #2c97eb;
}
</style>