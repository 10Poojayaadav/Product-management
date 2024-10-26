<template>
  <v-app>
    <div class="page">
      <div class="main">
        <v-main class="body">
          <div class="library-content">
            <Header />
            <div class="pray screen">
              <div class="message-sidebar pane" style="overflow-y:auto; " v-on:scroll="scrollLibararyView($event)" id="library-view">
                <div class="back_btn">
                  <v-btn>
                    <img src="/src/assets/back-arrow.svg" @click="backPage" alt="">
                    {{ title }}
                  </v-btn>
                </div>
              <div class="library_list_wrapper" >
                <div v-for="(v1, index) in library_list" :key="index" :class="{ active: activeIndex === index }" :data-id="v1.id" >
                  <img :src="v1.banner_image" class="sidebar-image" @click="navigateData(v1, 'l_list', index)" id="sidebar">
                </div>
              </div>

              </div>
            <div class="library-container" id="gridpage" v-if="grid" style="padding:0; margin:0;" v-on:scroll="scrollGridView($event)">
              <!-- Image page import -->
              <div v-if="imagePage">
                <ImagesPage v-if="imagePage":id="imageId"/>
              </div>
              <div class="library_content2" v-else-if="pdfPage" style="height:100%; width:100%; overflow-y:hidden; ">
                <!-- pdf page import -->
                <div class="grip_photos">
                  <Pdf v-if="pdfPage && pdfId" :id="pdfId" />
                </div>
              </div>
              <div class="library_content" v-else-if="sliderPage" style="height:100%; width:100%; ">
               <!-- slider page import -->
                <SliderPage v-if="sliderPage && sliderId" :id="sliderId" />
              </div>
              <div class="library_content"  v-else-if="newLibrary" style="padding:10px; ">
                <!-- new library -->
                <div v-for="(v1, index) in new_library_list" :key="index" class="library">
                  <h2 class="library-title">{{ v1.title }}</h2>
                 <!-- Vue Swiper -->
                 <swiper 
                      ref="librarySwiper"
                      :slides-per-view="getSlidesPerView(v1)" 
                      :space-between="getSpaceBetween(v1)"
                      :centeredSlides="false"
                      :autoplay="{
                        delay: 2500,
                        disableOnInteraction: false,
                      }"
                      :modules="modules"
                      class="librarySwiper"
                    >
                      <swiper-slide
                        v-for="(item, itemIndex) in v1.for_childs"
                        :key="itemIndex"
                        @click="navigateData(item)"
                      > 
                        <img :src="item.banner_image" alt="" :data-size="v1.image_type">
                      </swiper-slide>
                    </swiper>
               </div> 
              </div>
              
              <div v-else>
               <!-- <div class="grip-first-img" @click="navigateData(first_row)">
                 <img class="pray-first-image" :src="first_row.banner_image" alt="">
               </div> -->
            <div class="backbtn">
              <p class="prayer-title">{{ first_row.library2.title }}</p>
            </div>
               <div class="grip-photos-row">
                 <div v-for="(v1, index) in grid_list" :key="index" class="library-content-wrapper">
                   <img :src="v1.banner_image" class="library_content-image" @click="navigateData(v1)">
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
import SliderPage from "../common/SliderPage.vue";
import VideoPage from "../common/VideoPage.vue";
import ImagesPage from "../common/ImagesPage.vue";
import AudioPage from "../common/AudioPage.vue";
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';

export default {
  data: () => ({
     activeIndex: 0,
    v1: {
      for_childs: [] // Your data structure containing images
    },
    newLibrary:false,
    pdfPage: false,
    imagePage:false,
    sliderPage:false,
    tab: null,
    childtab: null,
    first_row: {},
    pray_list: [],
    grid_list: [],
    library_list: [],
    type: "pray-list",
    cardId: null,
    pdfId: "",
    imageId:"",
    sliderId: null,
    title: "",
    grid:false,
    openVideo:false,
    openAudio:false,
    gridTitle:'',
    libraryPage:1,
    library_last_page:"",
    new_library_list:[],
    media:"",
    prePage:"",
    gridpage:1,
    grid_last_page:1,
    queryId:null,
    section: null,
  }),
  components: {
    Header,
    Pdf,
    Swiper,
    SwiperSlide,
    VideoPage,
    AudioPage,
    SliderPage,
    ImagesPage,
  },
 mounted() {
    this.section = this.$route.params.section; 
    this.title = this.$route.query.title;
    this.queryId = this.$route.query.id;
    this.library();
     const previousUrl = localStorage.getItem('previousUrl');
    if (previousUrl) {
      const url = new URL(previousUrl);
      const id = url.searchParams.get('id');
    }
  },
  watch: {
  '$route.params.id': function(newId) {
    this.library();
    }
  },

  props: {
    id: {
      type: String,
      default: () => '',
    }
  },

  methods: {
    backPage() {
      if(this.section == "pray"){
        this.$router.push({
          name:"pray"
        })
      }
     else if (this.section == "proclaim"){
      this.$router.push({
          name:"proclaim"
        })
     }
     else if (this.section == "meditate"){
      this.$router.push({
          name:"meditate"
        })
     }
   else if (this.section == "fellowship"){
      this.$router.push({
          name:"fellowship"
        })
     }
    else{
    const previousUrl = localStorage.getItem('previoussUrl');
    if(previousUrl){
    window.location.href = previousUrl; 
    }
    else{
      this.$router.go(-1);
    }
    }

    },
    closeVideo() {
      this.openVideo = false;
    },
    closeAudio(){
      this.openAudio=false;
    },
    setupObserver() {
    const observer = new MutationObserver((mutations) => {
      const items = document.querySelectorAll('#library-view .library_list_wrapper > div');
      if (items.length > 0) {
        this.scrollToSelected(this.activeIndex);
        observer.disconnect(); 
      }
    });

    const targetNode = document.getElementById('library-view');
    observer.observe(targetNode, { childList: true, subtree: true });
  },
   library() {
      axios
        .post("lord-pray-content-list", {
          id: this.$route.params.id,
          type: "pray",
          page: this.libraryPage,
        })
        .then((response) => {
          console.log(response);
          this.library_list = response?.data?.data?.data;
          this.library_last_page = response?.data?.data?.data?.last_page;
            if (this.library_list.length > 0) {
             if (this.queryId) {
              for(let i = 0 ; i<this.library_list.length ; i++ ){            
               if(this.library_list[i].id == this.queryId ){    
                console.log(this.library_list[i].id);          
                this.navigateData(this.library_list[i],'l_list', i); 
                  this.setupObserver(); // Start observing
                break;

                }
              }
            }
            else if (this.library_list[0].content_type == "hyperlink") {
              this.navigateData(this.library_list[1], 'l_list', 1);
             }
             else {
              this.navigateData(this.library_list[0], 'l_list', 0);
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
    scrollGridView(event) {
      var obj = document.getElementById("gridpage");
      if ((parseInt(obj.scrollTop + 5) >= (obj.scrollHeight - obj.offsetHeight)) 
      && this.gridpage != this.grid_last_page) {
          this.gridpage++;
           axios
            .post("lord-pray-content-list", {
              id: this.cardId,
              type: "pray",
              page: this.gridpage,
            })
             .then((response) => {
              if(this.grid_last_page != 1)
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
    saveCurrentUrl() {
    localStorage.setItem('previousUrl', window.location.href);
    localStorage.setItem('activeIndex', this.activeIndex);
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
  scrollToSelected(itemIndex) {
    console.log("Selected Item Index: " + itemIndex);

    const sidebar = document.querySelector('#library-view');
    const items = document.querySelectorAll('#library-view .library_list_wrapper > div');
    
    console.log("Number of items: " + items.length);
    if (itemIndex < 0 || itemIndex >= items.length) {
      console.error("Invalid item index");
      return;
    }
    const selectedItem = items[itemIndex];
    const sidebarRect = sidebar.getBoundingClientRect();
    const itemRect = selectedItem.getBoundingClientRect();
    const itemTopRelativeToSidebar = itemRect.top - sidebarRect.top + sidebar.scrollTop;
    console.log("Item Rect Top:", itemRect.top);
    console.log("Sidebar Rect Top:", sidebarRect.top);
    console.log("Scroll Top:", sidebar.scrollTop);
    console.log("Item Top Relative to Sidebar:", itemTopRelativeToSidebar);
    const sidebarHeight = sidebar.offsetHeight;
    const itemHeight = selectedItem.offsetHeight;
    const scrollPosition = itemTopRelativeToSidebar - (sidebarHeight / 2) + (itemHeight / 2);
    console.log("Calculated Scroll Position:", scrollPosition);
    const maxScrollTop = sidebar.scrollHeight - sidebar.clientHeight;
    const boundedScrollPosition = Math.max(0, Math.min(scrollPosition, maxScrollTop));
    console.log("Bounded Scroll Position:", boundedScrollPosition);
    sidebar.scrollTo({
      top: boundedScrollPosition,
      behavior: 'smooth',
    });
  },

    
 
  navigateData(card, source, index) { 
    this.activeIndex = index;    
    this.saveCurrentUrl();
    const url = new URL(window.location.href);
    url.searchParams.set('id', card.id);
    window.history.pushState({}, '', url.toString()); 
    this.$nextTick(() => {
      this.scrollToTop(); 
    });
    if(card.content_type == "media"){
        if(card.media_type == "audio"){
          this.a_id=card.id;
          this.openAudio=true;
        }
        else if(card.media_type == "video"){
          this.v_id=card.id;
          this.openVideo=true;
        }
        else if(card.media_type == "pdf"){
          if (card.media_type == "pdf" && source === "l_list") {
            this.grid = true;
            this.pdfPage = true;
            this.pdfId = card.id;         
          }
          if (card.media_type == "pdf" && source !== "l_list") {
            this.pdfId = card.id;
            this.$router.push({
              name: "library2",
              params: { id: this.cardId, section: "pray" },
              query: { pdfId: this.pdfId, gridTitle:this.gridTitle},
            });
          }
        }
        else if(card.media_type == "image"){
          if (card.media_type == "image" && source === "l_list") {
            console.log("Image media:", card.media);
            this.media = card.media;
            this.imagePage = true;
            this.imageId = card.id
            this.grid = true;
            this.pdfPage = false;
          }
          if (card.media_type == "image" && source !== "l_list") {
            this.imageId = card.id;
            this.$router.push({
              name: "library2",
              params: { id: this.cardId, section: "pray" },
              query: { imageId: this.imageId, gridTitle:this.gridTitle},
            });
          }
        }
    }
    else if(card.content_type == "slider"){
        this.grid = true;
        this.sliderPage = true;
        this.sliderId = card.id;
    }
    else if(card.content_type == "library"){
        this.gridTitle = card.title;
        this.grid = true;
        this.pdfPage = false;
        this.sliderPage = false;
        this.cardId = card.id;
        if(card.library_type=="old" || card.library_type== null && source == 'l_list'){
            this.newLibrary = false;
            this.gridpage = 1;
            axios
              .post("lord-pray-content-list", {
                id: card.id,
                type: "pray",
                page:this.grid_page,
              })
              .then((response) => {
                console.log("response>>>>>>>>>>>>>>>>>>.",response);
                this.grid_list = response.data.data.data;
                this.grid_last_page = response.data.data.data.last_page;
                this.first_row = this.grid_list[0];
                if(this.grid_list[0].content_type == "hyperlink"){
                   this.grid_list.splice(0, 1);
                }
                
              })
              .catch((error) => {
                console.error("Error fetching data:", error);
              });
        }
        else if(card.library_type=="new" && source == 'l_list'){
          this.newLibrary = true;
          this.sliderPage = false;
          axios
            .post("lord-pray-content-list", {
              id: card.id,
              type: "pray",
            })
            .then((response) => {
              console.log("response",response);
              this.new_library_list = response.data.data.data;
            })
            .catch((error) => {
              console.error("Error fetching data:", error);
            });
        }
        else if(card.library_type=="old" && source == null){
          this.$router.push({
            name: "library",
            params: { id: card.library_id, section: "new-library" },
            query: { title:card.title},
          });
        }
        else{
           this.$router.push({
            name: "library",
            params: { id: card.library_id, section: "new-library" },
            query: { title:card.title},
          });
        }
    }
    else if(card.content_type == "hyperlink"){
        window.open(card.media, '_blank');
    }
    else{
        //nothing in card related anything
    }

    },
    getSlidesPerView(item) {
     return 3;
    },
    getSpaceBetween(){
      return 20;
    }
  },
};
</script>
<style scoped>
.active img {
  padding: 5px;   
  border: 3px solid #2c97eb;
}
</style>
